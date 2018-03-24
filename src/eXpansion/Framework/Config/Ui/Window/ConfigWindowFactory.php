<?php

namespace eXpansion\Framework\Config\Ui\Window;

use eXpansion\Framework\Config\Exception\InvalidConfigException;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use eXpansion\Framework\Config\Services\ConfigUiManager;
use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory;
use eXpansion\Framework\Gui\Components\Button;
use eXpansion\Framework\Gui\Components\Label;
use eXpansion\Framework\Gui\Components\Tooltip;
use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Quad;

/**
 * Class ConfigWindowFactory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Ui\Window
 */
class ConfigWindowFactory extends WindowFactory
{
    /** @var string */
    protected $currentPath;

    /** @var ConfigManagerInterface */
    protected $configManager;

    /** @var ConfigUiManager */
    protected $configUiManager;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * ConfigWindowFactory constructor.
     *
     * @param                        $name
     * @param                        $sizeX
     * @param                        $sizeY
     * @param null                   $posX
     * @param null                   $posY
     * @param WindowFactoryContext   $context
     * @param ConfigManagerInterface $configManager
     * @param ConfigUiManager        $configUiManager
     * @param ChatNotification       $chatNotification
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        ConfigManagerInterface $configManager,
        ConfigUiManager $configUiManager,
        ChatNotification $chatNotification
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->configManager = $configManager;
        $this->configUiManager = $configUiManager;
        $this->chatNotification = $chatNotification;
    }


    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getContentFrame();
        $contentFrame->removeAllChildren();

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);

        $saveButton = $this->uiFactory->createConfirmButton('expansion_config.ui.save', Button::COLOR_SUCCESS);
        $saveButton->setAction(
            $this->actionFactory->createManialinkAction(
                $manialink,
                [$this, 'saveCallback'],
                ['path' => $this->currentPath]
            )
        )
            ->setPosition(
                $this->sizeX - $saveButton->getWidth() - 4,
                -$this->sizeY + $saveButton->getHeight() + 4
            )
            ->setTranslate(true);

        // see how grid is built to fix this.
        $configs = $this->configManager->getConfigDefinitionTree()->get($this->currentPath);

        $elements = [(new Quad())->setHeight(4)];
        foreach ($configs as $config) {
            if (!is_object($config) || !($config instanceof ConfigInterface)) {
                throw new PlayerException("{$this->currentPath} is not valid configuration path");
            }

            $elements[] = $this->buildConfig($config, $this->sizeX - 8, $tooltip);
        }

        $contentFrame->addChild(
            $this->uiFactory->createLayoutScrollable(
                $this->uiFactory->createLayoutRow(0, 0, $elements, 2),
                $this->sizeX,
                $this->sizeY - $saveButton->getHeight() - 4 - 4
            )->setAxis(false, true)
        );

        $contentFrame->addChild($saveButton);
    }

    /**
     * Build display for config.
     *
     * @param ConfigInterface $config
     * @param                 $sizeX
     * @param Tooltip         $tooltip
     *
     * @return \eXpansion\Framework\Gui\Layouts\LayoutRow
     */
    protected function buildConfig(ConfigInterface $config, $sizeX, Tooltip $tooltip)
    {
        $rowLayout = $this->uiFactory->createLayoutRow(2, 0, [], 1);

        $rowLayout->addChild(
            $name = $this->uiFactory
                ->createLabel($config->getName(), Label::TYPE_TITLE)
                ->setWidth($sizeX)
                ->setHorizontalAlign(Control::LEFT)
                ->setTranslate(true)
                ->setTextId($config->getName())
                ->setText(null));

        if ($config->getDescription()) {
            $rowLayout->addChild(
                $this->uiFactory
                    ->createLabel("", Label::TYPE_NORMAL)
                    ->setWidth($sizeX)
                    ->setHorizontalAlign(Control::LEFT)
                    ->setTranslate(true)
                    ->setTextId($config->getDescription())
                    ->setText(null)
                    ->setTextPrefix("  ")
                    ->setMaxLines(3)
                    ->setLineSpacing(1)
            );
        }

        $rowLayout->addChild(
            $this->configUiManager->getUiHandler($config)->build($config, $sizeX * 0.37));


        return $rowLayout;
    }


    /**
     * @param ManialinkInterface $manialink
     * @param                    $login
     * @param                    $entries
     * @param                    $args
     */
    public function saveCallback(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $configs = $this->configManager->getConfigDefinitionTree();

        $error = false;
        // First check all.
        foreach ($entries as $configPath => $configValue) {
            $config = $configs->get($configPath);

            if ($config instanceof ConfigInterface) {
                try {
                    $config->validate($configValue);
                } catch (InvalidConfigException $invalidConfigException) {
                    $this->chatNotification->sendMessage(
                        $invalidConfigException->getTranslatableMessage(),
                        $manialink->getUserGroup(),
                        $invalidConfigException->getTranslationParameters()
                    );

                    $error = true;
                }
            }
        }

        if ($error) {
            // Don't set values and don't refresh window.
            return;
        }

        // Then save all if all is ok.
        foreach ($entries as $configPath => $configValue) {
            $config = $configs->get($configPath);

            if ($config instanceof ConfigInterface) {
                $config->setRawValue($configValue);
            }
        }

        $this->setCurrentPath($args['path']);
        $this->update($manialink->getUserGroup());


        $this->chatNotification->sendMessage('eXpansion.config.action.saved', $manialink->getUserGroup());
    }

    /**
     * Set path to display in.
     *
     * @param string $currentPath
     */
    public function setCurrentPath(string $currentPath)
    {
        $this->currentPath = $currentPath;
    }
}
