<?php

namespace eXpansion\Framework\Config\Ui\Window;

use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Services\ConfigManager;
use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
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

    /** @var ConfigManager */
    protected $configManager;

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getContentFrame();

        $manialink->setData('current_path', $this->currentPath);

        // TODO should be using the grid here. But the grid is not flexible enought! :(
        // see how grid is built to fix this.
        $configs = $this->configManager->getConfigTree()->get($this->currentPath);
        $elements = [];
        foreach ($configs as $config) {
            if (!is_object($config) || !($config instanceof ConfigInterface)) {
                throw new PlayerException("{$this->currentPath} is not valid configuration path");
            }

            $elements[] = $this->configManager->getUiHandler($config)->build($config, $this->sizeX - 8);
        }
        $contentFrame->addChild($this->uiFactory->createLayoutLine(0, 0, $elements));

        $saveButton = $this->uiFactory->createButton('expansion_config.save');
        $saveButton->setAction(
            $this->actionFactory->createManialinkAction($manialink, [$this, 'saveCallback'], [], true)
        );
        $saveButton->setPosition(
            $this->sizeX - $saveButton->getWidth() - 5,
            $this->sizeY - $saveButton->getHeight() - 4
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
        $configs = $this->configManager->getConfigTree();

        foreach ($entries as $configPath => $configValue) {
            $config = $configs->get($configPath);

            if ($config instanceof ConfigInterface) {
                $config->setRawValue($configValue);
            }
        }

        $this->update($manialink->getUserGroup());
    }
}
