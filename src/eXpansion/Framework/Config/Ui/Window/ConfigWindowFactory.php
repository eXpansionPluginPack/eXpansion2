<?php

namespace eXpansion\Framework\Config\Ui\Window;

use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Services\ConfigManager;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use eXpansion\Framework\Config\Services\ConfigUiManager;
use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory;
use FML\Controls\Frame;

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

        // TODO should use scrollbars here as we can't use grid.
        // see how grid is built to fix this.
        $configs = $this->configManager->getConfigDefinitionTree()->get($this->currentPath);

        $elements = [];
        foreach ($configs as $config) {
            if (!is_object($config) || !($config instanceof ConfigInterface)) {
                throw new PlayerException("{$this->currentPath} is not valid configuration path");
            }

            $elements[] = $this->configUiManager->getUiHandler($config)->build($config, $this->sizeX - 8);
        }
        $contentFrame->addChild($this->uiFactory->createLayoutRow(0, 0, $elements));

        $saveButton = $this->uiFactory->createButton('expansion_config.save');
        $saveButton->setAction(
            $this->actionFactory->createManialinkAction(
                $manialink,
                [$this, 'saveCallback'],
                ['path' => $this->currentPath]
            )
        );
        $saveButton->setPosition(
            $this->sizeX - $saveButton->getWidth() - 8,
            -$this->sizeY + $saveButton->getHeight() + 8
        );
        $contentFrame->addChild($saveButton);
    }

    /**
     * @param ManialinkInterface $manialink
     * @param $login
     * @param $entries
     * @param $args
     */
    public function saveCallback(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $configs = $this->configManager->getConfigDefinitionTree();

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
