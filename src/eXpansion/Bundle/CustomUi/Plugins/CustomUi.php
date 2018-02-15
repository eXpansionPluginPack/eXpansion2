<?php

namespace eXpansion\Bundle\CustomUi\Plugins;

use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class CustomUi
 *
 * @package eXpansion\Bundle\CustomUi\Plugins
 */
class CustomUi implements StatusAwarePluginInterface
{
    /** @var Connection */
    protected $connection;

    /**@var Group */
    protected $allPlayers;

    /** @var string[] */
    protected $uiProperties;

    /** @var string */
    protected $setPropertiesScriptEvent;

    /**@var WidgetFactory[] */
    protected $customWidgets;

    /**
     * CustomUi constructor.
     *
     * @param Connection $connection
     * @param Group $allPlayers
     * @param string[] $uiProperties
     * @param string $setPropertiesScriptEvent
     * @param WidgetFactory[] $customWidgets
     */
    public function __construct(
        Connection $connection,
        Group $allPlayers,
        array $uiProperties,
        string $setPropertiesScriptEvent,
        array $customWidgets
    ) {
        $this->connection = $connection;
        $this->allPlayers = $allPlayers;
        $this->uiProperties = $uiProperties;
        $this->setPropertiesScriptEvent = $setPropertiesScriptEvent;
        $this->customWidgets = $customWidgets;
    }


    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        if ($status) {
            $xml = new \SimpleXMLElement('<ui_properties/>');
            foreach ($this->uiProperties as $property => $propertyDetails) {
                $this->configureUiProperty($xml->addChild($property), $propertyDetails);
            }
            $this->connection->triggerModeScriptEvent($this->setPropertiesScriptEvent, [$xml->asXML()]);

            if (empty($this->customWidgets)) {
                return;
            }
            foreach ($this->customWidgets as $widget) {
                $widget->create($this->allPlayers);
            }
        } else {
            if (empty($this->customWidgets)) {
                return;
            }

            foreach ($this->customWidgets as $widget) {
                $widget->destroy($this->allPlayers);
            }
        }
    }

    /**
     * @param \SimpleXMLElement $element
     * @param string $elementProperties
     */
    protected function configureUiProperty(\SimpleXMLElement $element, $elementProperties)
    {
        foreach ($elementProperties as $property => $value) {
            if (in_array($property, ['visible', 'alt_visible'])) {
                $value = $value ? 'true' : 'false';
            }

            $element->addAttribute($property, $value);
        }
    }
}
