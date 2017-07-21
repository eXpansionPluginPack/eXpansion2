<?php

namespace eXpansion\Bundle\MxKarma\Plugins;


use eXpansion\Framework\Core\DataProviders\Listener\ApplicationDataListenerInterface;
use eXpansion\Framework\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\MatchDataListenerInterface;
use Maniaplanet\DedicatedServer\Structures\Map;
use Symfony\Component\Yaml\Yaml;
use eXpansion\Bundle\MxKarma\Plugins\Connection as MxConnection;

class MxKarma implements MatchDataListenerInterface, StatusAwarePluginInterface, ChatDataListenerInterface, ApplicationDataListenerInterface
{
    /**
     * @var object
     */
    protected $config;

    /**
     * @var Console
     */
    protected $console;
    /**
     * @var Dispatcher
     */
    protected $dispatcher;
    /**
     * @var ChatNotification
     */
    protected $chatNotification;

    /**
     * @var MxConnection
     */
    private $mxKarma;

    public function __construct(
        MxConnection $mxKarma,
        Console $console,
        ChatNotification $chatNotification,
        Dispatcher $dispatcher
    ) {

        $this->config = (object)Yaml::parse(file_get_contents('./app/config/plugins/mxkarma.yml'))['parameters'];
        $this->console = $console;
        $this->dispatcher = $dispatcher;
        $this->chatNotification = $chatNotification;
        $this->mxKarma = $mxKarma;
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
        // TODO: Implement setStatus() method.
    }

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartMatchStart($count, $time)
    {
        // TODO: Implement onStartMatchStart() method.
    }

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartMatchEnd($count, $time)
    {
        // TODO: Implement onStartMatchEnd() method.
    }

    /**
     * Called when a player chats.
     *
     * @param Player $player
     * @param $text
     *
     * @return void
     */
    public function onPlayerChat(Player $player, $text)
    {
        // TODO: Implement onPlayerChat() method.
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
        $this->mxKarma->connect($this->config->serverlogin, $this->config->apikey);

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

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndMatchStart($count, $time)
    {
        // TODO: Implement onEndMatchStart() method.
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndMatchEnd($count, $time)
    {
        // TODO: Implement onEndMatchEnd() method.
    }

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartTurnStart($count, $time)
    {
        // TODO: Implement onStartTurnStart() method.
    }

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartTurnEnd($count, $time)
    {
        // TODO: Implement onStartTurnEnd() method.
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndTurnStart($count, $time)
    {
        // TODO: Implement onEndTurnStart() method.
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndTurnEnd($count, $time)
    {
        // TODO: Implement onEndTurnEnd() method.
    }

    /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartRoundStart($count, $time)
    {
        // TODO: Implement onStartRoundStart() method.
    }

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartRoundEnd($count, $time)
    {
        // TODO: Implement onStartRoundEnd() method.
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndRoundStart($count, $time)
    {
        // TODO: Implement onEndRoundStart() method.
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndRoundEnd($count, $time)
    {
        // TODO: Implement onEndRoundEnd() method.
    }
}
