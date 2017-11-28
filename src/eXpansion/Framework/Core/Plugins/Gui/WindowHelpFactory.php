<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Services\ChatCommands;
use FML\Controls\Frame;
use FML\Controls\Label;


/**
 * Class HelpFactory
 *
 * @package eXpansion\Framework\AdminGroups\Plugins\Window;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WindowHelpFactory extends WindowFactory
{
    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var ChatCommands */
    protected $chatCommands;

    /** @var ChatCommandDataProvider */
    protected $chatCommandDataPovider;

    /** @var WindowHelpDetailsFactory */
    protected $windowHelpDetailsFactory;

    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WindowFactoryContext $context,
        GridBuilderFactory $gridBuilderFactory,
        DataCollectionFactory $dataCollectionFactory,
        ChatCommands $chatCommands,
        ChatCommandDataProvider $chatCommandDataProvider,
        WindowHelpDetailsFactory $windowHelpDetailsFactory
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->chatCommands = $chatCommands;
        $this->chatCommandDataPovider = $chatCommandDataProvider;
        $this->windowHelpDetailsFactory = $windowHelpDetailsFactory;
    }


    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $collection = $this->dataCollectionFactory->create($this->getChatCommands($manialink));
        $collection->setPageSize(2);

        $helpButton = new Label();
        $helpButton->setText('')
            ->setSize(4, 4)
            ->setAreaColor("0000")
            ->setAreaFocusColor("0000");

        $desctiptionButton = new Label();
        $desctiptionButton->setText('')
            ->setSize(4, 4)
            ->setAreaColor("0000")
            ->setAreaFocusColor("0000");

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'command',
                "expansion_core.windows.chat_commands.column_command",
                25
            )
            ->addTextColumn(
                'description',
                'expansion_core.windows.chat_commands.column_description',
                70,
                false,
                true
            )
            ->addActionColumn('help', '', 5, array($this, 'callbackHelp'), $helpButton)
            ->addActionColumn(
                'description',
                '',
                5,
                array($this, 'callbackDescription'),
                $desctiptionButton
            );

        $manialink->setData('grid', $gridBuilder);
    }

    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getContentFrame();
        $contentFrame->removeAllChildren();

        $collection = $this->dataCollectionFactory->create($this->getChatCommands($manialink));
        $collection->setPageSize(20);

        /** @var GridBuilder $gridBuilder */
        $gridBuilder = $manialink->getData('grid');
        $contentFrame->addChild($gridBuilder->build($contentFrame->getWidth(), $contentFrame->getHeight()));
    }

    /**
     * Get chat commands to display the admin.
     *
     * @param ManialinkInterface $manialink
     *
     * @return array
     */
    protected function getChatCommands(ManialinkInterface $manialink)
    {
        $login = $manialink->getUserGroup()->getLogins()[0];

        return array_map(
            function($command) {
                /** @var AbstractChatCommand $command */
                return [
                    'command' => $command->getCommand(),
                    'description' => $command->getDescription(),
                    'help' => $command->getHelp(),
                    'aliases' => $command->getAliases(),
                ];
            },
            array_filter(
                $this->chatCommands->getChatCommands(),
                function($command) use ($login) {
                    if ($command instanceof AbstractAdminChatCommand) {
                        return $command->hasPermission($login);
                    }
                    return true;
                }

            )
        );
    }

    /**
     * Callback called when help button is pressed.
     *
     * @param ManialinkInterface $manialink
     * @param $login
     * @param $params
     * @param $arguments
     */
    public function callbackHelp(ManialinkInterface $manialink, $login, $params, $arguments)
    {
        $this->chatCommandDataPovider->onPlayerChat(0, $login, '/'.$arguments['command'].' -h', true);
    }

    /**
     * Callbacked called when description button is pressed.
     *
     * @param ManialinkInterface $manialink
     * @param $login
     * @param $params
     * @param $arguments
     */
    public function callbackDescription(ManialinkInterface $manialink, $login, $params, $arguments)
    {
        $chatCommands = $this->chatCommands->getChatCommands();
        $this->windowHelpDetailsFactory->setCurrentCommand($chatCommands[$arguments['command']]);

        $this->windowHelpDetailsFactory->create($login);
    }
}
