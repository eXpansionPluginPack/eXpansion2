<?php

namespace eXpansion\Bundle\CustomUi\Plugins;

use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class CustomUi
 *
 * @package eXpansion\Bundle\CustomUi\Plugins
 */
class CustomUi implements StatusAwarePluginInterface, ListenerInterfaceMpScriptMap, ListenerInterfaceMpLegacyPlayer
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
     * @param Connection      $connection
     * @param Group           $allPlayers
     * @param string[]        $uiProperties
     * @param string          $setPropertiesScriptEvent
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
            $this->setCustomUiProperties();

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
     * @throws \Maniaplanet\DedicatedServer\InvalidArgumentException
     */
    protected function setCustomUiProperties()
    {
        $xml = new \SimpleXMLElement('<ui_properties/>');
        foreach ($this->uiProperties as $property => $propertyDetails) {
            $this->configureUiProperty($xml->addChild($property), $propertyDetails);
        }
        $this->connection->triggerModeScriptEvent($this->setPropertiesScriptEvent, [$xml->asXML()]);
        $this->connection->triggerModeScriptEvent("Trackmania.UI.SetProperty", ["scorestable", "visibility", "false"]);
    }

    /**
     * @param \SimpleXMLElement $element
     * @param array             $elementProperties
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

    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     * @throws \Maniaplanet\DedicatedServer\InvalidArgumentException
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        //do nothing
    }

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     * @throws \Maniaplanet\DedicatedServer\InvalidArgumentException
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {
        $this->setCustomUiProperties();
    }

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     */
    public function onEndMapStart($count, $time, $restarted, Map $map)
    {
        // do nothing
    }

    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     * @throws \Maniaplanet\DedicatedServer\InvalidArgumentException
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map)
    {
        // do nothing
    }

    /**
     * @param Player $player
     * @return void
     */
    public function onPlayerConnect(Player $player)
    {
        $this->setCustomUiProperties();
    }

    /**
     * @param Player $player
     * @param string $disconnectionReason
     * @return void
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // TODO: Implement onPlayerDisconnect() method.
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerInfoChanged() method.
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerAlliesChanged() method.
    }
}
