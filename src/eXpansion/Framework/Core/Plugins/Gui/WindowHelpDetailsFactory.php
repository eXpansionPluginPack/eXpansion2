<?php


namespace eXpansion\Framework\Core\Plugins\Gui;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Model\Gui\Factory\LineFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;

/**
 * Class WindowHelpDetailsFactory
 *
 * @package eXpansion\Framework\Core\Plugins\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WindowHelpDetailsFactory extends WindowFactory
{
    /** @var AbstractChatCommand */
    protected $currentCommand = null;

    /** @var  Translations */
    protected $translationsHelper;

    /** @var LineFactory */
    protected $lineFactory;

    /** @var LineFactory */
    protected $titleLineFactory;

    /**
     * @param LineFactory $lineFactory
     */
    public function setLineFactory($lineFactory)
    {
        $this->lineFactory = $lineFactory;
    }

    /**
     * @param LineFactory $titleLineFactory
     */
    public function setTitleLineFactory($titleLineFactory)
    {
        $this->titleLineFactory = $titleLineFactory;
    }

    /**
     * @param Translations $translationsHelper
     */
    public function setTranslationsHelper($translationsHelper)
    {
        $this->translationsHelper = $translationsHelper;
    }

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $manialink->setData('command', $this->currentCommand);
    }

    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        $manialink->getContentFrame()->removeAllChildren();

        /** @var AbstractChatCommand $command */
        $command = $manialink->getData('command');

        $column1Width = $manialink->getContentFrame()->getWidth() * (2/3);
        $column2Width = $manialink->getContentFrame()->getWidth() * (1/3) - 1;

        /*
         * COLUMN 1 Description of the chat command
         */
        $descriptionTitle = $this->titleLineFactory->create(
            20,
            [
                [
                    'text' => 'expansion_core.windows.chat_commands_description.description',
                    'width' => 1,
                    'translatable' => true
                ]
            ],
            0
        );
        $manialink->addChild($descriptionTitle);

        $descriptionLabel = $this->lineFactory->create(
            $column1Width - 21,
            [
                [
                    'text' => $command->getDescription(),
                    'width' => 1,
                    'translatable' => true

                ]
            ],
            0,
            16,
            true,
            4
        );
        $descriptionLabel->setPosition(21, 0);
        $manialink->addChild($descriptionLabel);

        $helpTitle = $this->titleLineFactory->create(
            20,
            [
                [
                    'text' => 'expansion_core.windows.chat_commands_description.help',
                    'width' => 1,
                    'translatable' => true
                ]
            ],
            0
        );
        $helpTitle->setPosition(0, -1 * ($descriptionLabel->getHeight() + 1));
        $manialink->addChild($helpTitle);

        $helpLabel = $this->lineFactory->create(
            $column1Width - 21,
            [
                [
                    'text' => "Use '/" . $this->currentCommand->getCommand() . " -h' to get help on the usage of the command.",
                    'width' => 1,
                    'translatable' => false

                ]
            ],
            0,
            8,
            true,
            2
        );
        $helpLabel->setPosition(21, $helpTitle->getY());
        $manialink->addChild($helpLabel);

        /**
         * COLUMN2 Aliases of the chat command.
         */
        $aliasesTitle = $this->titleLineFactory->create(
            20,
            [
                [
                    'text' => 'expansion_core.windows.chat_commands_description.aliases',
                    'width' => 1,
                    'translatable' => true
                ]
            ],
            0
        );
        $aliasesTitle->setPosition($column1Width + 1, 0);
        $manialink->addChild($aliasesTitle);

        $posY = 0;
        $idx = 0;
        foreach ($command->getAliases() as $i => $alias) {
            $aliasesLabel = $this->lineFactory->create(
                $column2Width - 21,
                [
                    [
                        'text' => "/$alias",
                        'width' => 1,
                        'translatable' => false

                    ]
                ],
                $idx++
            );
            $aliasesLabel->setPosition($column1Width + 22, $posY);
            $manialink->addChild($aliasesLabel);

            $posY -= ($aliasesLabel->getHeight() + 1);
        }
    }

    /**
     * @param null $currentCommand
     */
    public function setCurrentCommand($currentCommand)
    {
        $this->currentCommand = $currentCommand;
    }
}
