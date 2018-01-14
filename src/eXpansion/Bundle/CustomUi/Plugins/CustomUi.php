<?php

namespace eXpansion\Bundle\CustomUi\Plugins;

use eXpansion\Bundle\CustomUi\Plugins\Gui\CustomCheckpointWidget;
use eXpansion\Bundle\CustomUi\Plugins\Gui\CustomScoreboardWidget;
use eXpansion\Bundle\CustomUi\Plugins\Gui\CustomSpeedWidget;
use eXpansion\Bundle\CustomUi\Plugins\Gui\MarkersWidget;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use Maniaplanet\DedicatedServer\Connection;


class CustomUi implements ListenerInterfaceExpApplication, StatusAwarePluginInterface, ListenerInterfaceMpLegacyPlayer
{
    /** @var Connection */
    protected $connection;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var Group
     */
    private $allPlayers;
    /**
     * @var CustomScoreboardWidget
     */
    private $customSpeedWidget;
    /**
     * @var CustomCheckpointWidget
     */
    private $customCheckpointWidget;
    /**
     * @var CustomScoreboardWidget
     */
    private $customScoreboardWidget;

    /**
     * @var Group
     */
    private $players;

    /**
     * CustomUi constructor.
     *
     * @param Connection             $connection
     * @param PlayerStorage          $playerStorage
     * @param Group                  $allPlayers
     * @param Group                  $players
     * @param CustomSpeedWidget      $customSpeedWidget
     * @param CustomCheckpointWidget $customCheckpointWidget
     * @param CustomScoreboardWidget $customScoreboardWidget
     * @param MarkersWidget          $markersWidget
     */
    public function __construct(
        Connection $connection,
        PlayerStorage $playerStorage,
        Group $allPlayers,
        Group $players,
        CustomSpeedWidget $customSpeedWidget,
        CustomCheckpointWidget $customCheckpointWidget,
        CustomScoreboardWidget $customScoreboardWidget,
        MarkersWidget $markersWidget
    ) {
        $this->connection = $connection;
        $this->playerStorage = $playerStorage;
        $this->allPlayers = $allPlayers;
        $this->customSpeedWidget = $customSpeedWidget;
        $this->customCheckpointWidget = $customCheckpointWidget;
        $this->customScoreboardWidget = $customScoreboardWidget;
        $this->players = $players;
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        // do nothing
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // do nothing
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     * @throws \Maniaplanet\DedicatedServer\InvalidArgumentException
     */
    public function onApplicationReady()
    {

        $properties = /** @lang XML */
            <<<EOL
    <ui_properties>
 		<!-- The map name and author displayed in the top right of the screen when viewing the scores table -->
 		<map_info visible="false" pos="-160. 80. 150." />
 
 		<!-- Information about live envent displayed in the top right of the screen -->
 		<live_info visible="false" pos="-159. 84. 5." />
 
 		<!-- Information about the spectated player displayed in the bottom of the screen -->
 		<spectator_info visible="true" pos="-135. 80. 5." />
 
 		<!-- Only visible in solo modes, it hides the medal/ghost selection UI -->
 		<opponents_info visible="true" />
 		<!--
 			The server chat displayed on the bottom left of the screen
 			The offset values range from 0. to -3.2 for x and from 0. to 1.8 for y
 			The linecount property must be between 0 and 40
 			use offset 0 1.55 for upper corner 
 		-->
 		<chat visible="true" offset="0. 0." linecount="7" />
 		
 		<!-- Time of the players at the current checkpoint displayed at the bottom of the screen -->
 		<checkpoint_list visible="true" pos="48. -52. 5." />
 		
 		<!-- Small scores table displayed at the end of race of the round based modes (Rounds, Cup, ...) on the right of the screen -->
 		<round_scores visible="true" pos="-158.5 40. 5." />
 		
 		<!-- Race time left displayed at the bottom right of the screen -->
 		<countdown visible="false" pos="81. -63. 5." />
 		
 		<!-- 3, 2, 1, Go! message displayed on the middle of the screen when spawning --> 		
 		<go visible="true" />
 		
 		<!-- Current race chrono displayed at the bottom center of the screen -->
 		<chrono visible="true" pos="0. -80. -5." />
 		
 		<!-- Speed and distance raced displayed in the bottom right of the screen -->
 		<speed_and_distance visible="false" pos="137. -69. 5." />
 		
 		<!-- Previous and best times displayed at the bottom right of the screen -->
 		<personal_best_and_rank visible="false" pos="157. -24. 5." />
 		
 		<!-- Current position in the map ranking displayed at the bottom right of the screen -->
 		<position visible="false" pos="150.5 -28. 5." />
 		
 		<!-- Checkpoint time information displayed in the middle of the screen when crossing a checkpoint -->
 		<checkpoint_time visible="false" pos="0. 3. -10." />
 		
 		<!-- The avatar of the last player speaking in the chat displayed above the chat -->
 		<chat_avatar visible="false" />
 		
 		<!-- Warm-up progression displayed on the right of the screen during warm-up -->
  		<warmup visible="true" pos="153. 13. 0." /> 		
 		
 		<!-- Ladder progression box displayed on the top of the screen at the end of the map --> 		
 		<endmap_ladder_recap visible="false" /> 		
 		
 		<!-- Laps count displayed on the right of the screen on multilaps map --> 		
 		<multilap_info visible="true" pos="140. 84. 5." />
 		
 		<!-- Player's ranking at the latest checkpoint --> 		
 		<checkpoint_ranking visible="false" pos="0. 84. 5." />
 		
 		<!-- Scores table displayed in the middle of the screen --> 		
 		<scorestable alt_visible="false" />
 		
 		<!-- Number of players spectating us displayed at the bottom right of the screen --> 		
 		<viewers_count visible="true" pos="157. -40. 5." /> 	
 	</ui_properties>
EOL;

        $this->connection->triggerModeScriptEvent('Trackmania.UI.SetProperties', [$properties]);
        $this->connection->triggerModeScriptEvent('Shootmania.UI.SetProperties', [$properties]);

        $this->customScoreboardWidget->create($this->allPlayers);
        $this->customSpeedWidget->create($this->allPlayers);
        $this->customCheckpointWidget->create($this->allPlayers);
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // do nothing
    }

    public function onPlayerConnect(Player $player)
    {
        // do nothing
    }

    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // do nothing
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // do nothing
    }

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // do nothing
    }
}
