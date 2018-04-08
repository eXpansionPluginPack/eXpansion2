<?php


namespace eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\Helpers\CompatibleFetcher;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use oliverde8\AssociativeArraySimplified\AssociativeArray;


/**
 * Class WidgetPositionProvider
 *
 * @package eXpansion\Framework\Core\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WidgetPositionDataProvider extends AbstractDataProvider implements StatusAwarePluginInterface
{
    /** @var CompatibleFetcher */
    protected $compatibleFetcher;

    /** @var GameDataStorage */
    protected $gameStorage;

    /** @var array  */
    protected $widgetPositions;

    /**
     * WidgetPositionDataProvider constructor.
     *
     * @param CompatibleFetcher $compatibleFetcher
     * @param GameDataStorage   $gameStorage
     * @param array             $widgetPositions
     */
    public function __construct(CompatibleFetcher $compatibleFetcher, GameDataStorage $gameStorage, array $widgetPositions)
    {
        $this->compatibleFetcher = $compatibleFetcher;
        $this->gameStorage = $gameStorage;
        $this->widgetPositions = $widgetPositions;
    }


    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        $title = $this->gameStorage->getTitle();
        $mode = $this->gameStorage->getGameModeCode();
        $script = strtolower($this->gameStorage->getGameInfos()->scriptName);

        foreach ($this->plugins as $pluginId => $plugin) {
            if (isset($this->widgetPositions[$pluginId])) {
                $options = $this->compatibleFetcher->getCompatibleData($this->widgetPositions[$pluginId], $title, $mode, $script);

                if (!is_null($options)) {
                    /** @var WidgetFactory $plugin */
                    $plugin->updateOptions($options['posX'], $options['posY'], AssociativeArray::getFromKey($options, 'options', []));
                }
            }
        }
    }
}
