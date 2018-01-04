<?php

namespace eXpansion\Bundle\WidgetCurrentMap\Plugins;

use eXpansion\Bundle\WidgetCurrentMap\Plugins\Gui\CurrentMapWidgetFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use Maniaplanet\DedicatedServer\Connection;


class WidgetCurrentMap implements ListenerInterfaceExpApplication, ListenerInterfaceMpScriptMatch
{
    /** @var Connection */
    protected $connection;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var CurrentMapWidgetFactory
     */
    private $widget;
    /**
     * @var Group
     */
    private $players;

    /**
     * Debug constructor.
     *
     * @param Connection              $connection
     * @param PlayerStorage           $playerStorage
     * @param CurrentMapWidgetFactory $widget
     */
    public function __construct(
        Connection $connection,
        PlayerStorage $playerStorage,
        CurrentMapWidgetFactory $widget,
        Group $players
    ) {
        $this->connection = $connection;
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {


    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        $this->widget->create($this->players);
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {

    }

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchStart($count, $time)
    {
        $this->widget->update($this->players);
    }

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchStart($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnStart($count, $time)
    {

    }

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnStart($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundStart($count, $time)
    {
        // do nothing
    }

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundEnd($count, $time)
    {
        // do nothing
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundStart($count, $time)
    {
        // do nothing
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundEnd($count, $time)
    {
        // do nothing
    }
}
