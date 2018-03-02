<?php

namespace eXpansion\Bundle\Players\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Gui\Components\Button;
use Maniaplanet\DedicatedServer\Connection;

abstract class AbstractListWindow extends GridWindowFactory
{
    /**
     * @var PlayerStorage
     */
    protected $playerStorage;
    /**
     * @var ChatCommandDataProvider
     */
    protected $chatCommandDataProvider;
    /**
     * @var Factory
     */
    protected $factory;
    /**
     * @var AdminGroups
     */
    protected $adminGroups;

    /**
     * @var ChatNotification
     */
    protected $chatNotification;

    /**
     * PlayersWindow constructor.
     * @param                         $name
     * @param                         $sizeX
     * @param                         $sizeY
     * @param null                    $posX
     * @param null                    $posY
     * @param WindowFactoryContext    $context
     * @param PlayerStorage           $playerStorage
     * @param DataCollectionFactory   $dataCollectionFactory
     * @param GridBuilderFactory      $gridBuilderFactory
     * @param ChatCommandDataProvider $chatCommandDataProvider
     * @param Factory                 $factory
     * @param AdminGroups             $adminGroups
     * @param ChatNotification        $chatNotification
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        PlayerStorage $playerStorage,
        DataCollectionFactory $dataCollectionFactory,
        GridBuilderFactory $gridBuilderFactory,
        ChatCommandDataProvider $chatCommandDataProvider,
        Factory $factory,
        AdminGroups $adminGroups,
        ChatNotification $chatNotification

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->playerStorage = $playerStorage;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->chatCommandDataProvider = $chatCommandDataProvider;
        $this->factory = $factory;
        $this->adminGroups = $adminGroups;
        $this->chatNotification = $chatNotification;
    }

    protected function createContent(
        ManialinkInterface $manialink
    ) {
        parent::createContent($manialink);
        $this->updateData($manialink);
    }

    protected function updateContent(
        ManialinkInterface $manialink
    ) {
        parent::updateContent($manialink);
    }

    /**
     * @param ManialinkInterface $manialink
     */
    protected function createGrid(
        ManialinkInterface $manialink
    ) {

        $selectButton = $this->uiFactory->createButton('expansion_players.gui.list.window.column.remove',
            Button::TYPE_DEFAULT)->setSize(10, 5)->setTranslate(true);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($manialink->getData('dataCollection'))
            ->setManialinkFactory($this)
            ->addTextColumn(
                'login',
                'expansion_players.gui.players.window.column.login',
                4,
                true,
                false

            )
            ->addActionColumn('login', 'expansion_players.gui.list.window.column.remove',
                3, [$this, "callbackRemovePlayer"], $selectButton);


        $manialink->setData('grid', $gridBuilder);

        $frame = $manialink->getContentFrame();
        $this->setGridSize($frame->getWidth(), $frame->getHeight() - 10);

        $this->setGridSize(60, 90);
        $this->setGridPosition(0, 0);

    }

    /**
     * Get list of players to display.
     *
     * @return array
     */
    abstract function getDataSet() : array;

    /**
     * Execute action for player.
     *
     * @param $login
     *
     * @return mixed
     */
    abstract function executeForPlayer($login);


    /**
     * @inheritdoc
     */
    public function updateData(
        $manialink
    ) {
        $dataset = $this->getDataSet();

        $listData = [];
        foreach ($dataset as $player) {
            $listData[] = ["login" => $player->login];
        }

        $this->setData($manialink, $listData);
    }

    /**
     * @param ManialinkInterface $manialink
     * @param $login
     * @param $entries
     * @param $args
     */
    public function callbackRemovePlayer(
        $manialink,
        $login,
        $entries,
        $args
    ) {
        try {
            $this->executeForPlayer($args['login']);
            $this->updateData($manialink);
        } catch (\Exception $e) {
            $this->chatNotification->sendMessage('expansion_players.chat.exception',
                $login, ["%message%" => $e->getMessage()]);
        }
        $this->update($manialink->getUserGroup());
    }

    /**
     * @param $login
     * @param $command
     */
    public function callChatCommand($login, $command)
    {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, $command, true);
    }

}

