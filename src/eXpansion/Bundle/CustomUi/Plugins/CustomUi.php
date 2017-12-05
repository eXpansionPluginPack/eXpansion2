<?php

namespace eXpansion\Bundle\CustomUi\Plugins;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
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
     * CustomUi constructor.
     *
     * @param Connection $connection
     * @param PlayerStorage $playerStorage
     */
    public function __construct(Connection $connection, PlayerStorage $playerStorage)
    {
        $this->connection = $connection;
        $this->playerStorage = $playerStorage;
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

    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // TODO: Implement onApplicationInit() method.
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        $properties = /** @lang XML */
            <<<EOL
    <ui_properties>
 		<!-- The map name and author displayed in the top right of the screen when viewing the scores table -->
 		<map_info visible="true" pos="-160. 80. 150." />
 
 		<!-- Information about live envent displayed in the top right of the screen -->
 		<live_info visible="false" pos="-159. 84. 5." />
 
 		<!-- Information about the spectated player displayed in the bottom of the screen -->
 		<spectator_info visible="true" pos="0. -68. 5." />
 
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
 		<countdown visible="true" pos="153. -40. 5." />
 		
 		<!-- 3, 2, 1, Go! message displayed on the middle of the screen when spawning --> 		
 		<go visible="true" />
 		
 		<!-- Current race chrono displayed at the bottom center of the screen -->
 		<chrono visible="true" pos="0. -80. -5." />
 		
 		<!-- Speed and distance raced displayed in the bottom right of the screen -->
 		<speed_and_distance visible="true" pos="137. -69. 5." />
 		
 		<!-- Previous and best times displayed at the bottom right of the screen -->
 		<personal_best_and_rank visible="false" pos="157. -24. 5." />
 		
 		<!-- Current position in the map ranking displayed at the bottom right of the screen -->
 		<position visible="true" pos="150.5 -28. 5." />
 		
 		<!-- Checkpoint time information displayed in the middle of the screen when crossing a checkpoint -->
 		<checkpoint_time visible="true" pos="0. 3. -10." />
 		
 		<!-- The avatar of the last player speaking in the chat displayed above the chat -->
 		<chat_avatar visible="true" />
 		
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
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // TODO: Implement onApplicationStop() method.
    }

    public function onPlayerConnect(Player $player)
    {

    }

    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // TODO: Implement onPlayerDisconnect() method.
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerInfoChanged() method.
    }

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerAlliesChanged() method.
    }
}
