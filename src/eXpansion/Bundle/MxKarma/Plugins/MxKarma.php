<?php

namespace eXpansion\Bundle\MxKarma\Plugins;


use eXpansion\Bundle\MxKarma\DataProviders\Listeners\ListenerInterfaceMxKarma;
use eXpansion\Bundle\MxKarma\Entity\MxRating;
use eXpansion\Bundle\MxKarma\Entity\MxVote;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use Maniaplanet\DedicatedServer\Structures\Map;
use Symfony\Component\Yaml\Yaml;
use eXpansion\Bundle\MxKarma\Plugins\Connection as MxConnection;

class MxKarma implements StatusAwarePluginInterface,
    ListenerInterfaceMpScriptMap,
    ListenerInterfaceMpLegacyChat,
    ListenerInterfaceExpApplication,
    ListenerInterfaceMxKarma
{


    /** @var MxVote[] */
    protected $changedVotes = [];

    /** @var  int */
    private $startTime;
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

    /** @var  MxRating */
    private $mxRating;

    /** @var  MxVote[] */
    private $votes;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * MxKarma constructor.
     * @param Connection $mxKarma
     * @param Console $console
     * @param ChatNotification $chatNotification
     * @param Dispatcher $dispatcher
     * @param PlayerStorage $playerStorage
     */
    public function __construct(
        MxConnection $mxKarma,
        Console $console,
        ChatNotification $chatNotification,
        Dispatcher $dispatcher,
        PlayerStorage $playerStorage
    ) {

        $this->config = (object)Yaml::parse(file_get_contents('./app/config/plugins/mxkarma.yml'))['parameters'];
        $this->console = $console;
        $this->dispatcher = $dispatcher;
        $this->chatNotification = $chatNotification;
        $this->mxKarma = $mxKarma;
        $this->playerStorage = $playerStorage;
    }

    /**
     * sets vote value
     * @param $login
     * @param $vote
     */
    public function setVote($login, $vote)
    {
        $player = $this->playerStorage->getPlayerInfo($login);

        $obj = [
            "login" => $login,
            "nickname" => $player->getNickName(),
            "vote" => $vote,
        ];

        $this->changedVotes[$player->getLogin()] = new MxVote((object)$obj);
        $this->chatNotification->sendMessage('expansion_mxkarma.chat.votechanged', $login);
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
     * Called when a player chats.
     *
     * @param Player $player
     * @param $text
     *
     * @return void
     */
    public function onPlayerChat(Player $player, $text)
    {
        if ($player->getPlayerId() === 0) {
            return;
        }
        if (substr($text, 0, 1) == "/") {
            return;
        }

        if ($text == "++") {
            $this->setVote($player->getLogin(), 100);
        }

        if ($text == "--") {
            $this->setVote($player->getLogin(), 0);
        }

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
        $this->startTime = time();
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
     *
     */
    public function onMxKarmaConnect()
    {
        $this->mxKarma->loadVotes(array_keys($this->playerStorage->getOnline()), false);
    }

    /**
     * @param MxRating $mxRating
     * @return mixed
     */
    public function onMxKarmaVoteLoad(MxRating $mxRating)
    {

        $this->mxRating = $mxRating;
        $this->changedVotes = [];
        $this->votes = $mxRating->getVotes();

    }

    /**
     * @param MxVote[] $updatedVotes
     * @return mixed
     */
    public function onMxKarmaVoteSave($updatedVotes)
    {
        // TODO: Implement onMxKarmaVoteSave() method.
    }

    public function onMxKarmaDisconnect()
    {
        // TODO: Implement onMxKarmaDisconnect() method.
    }

    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return mixed
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onStartMapStart() method.
    }

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return mixed
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {
        $this->startTime = time();
        $this->mxKarma->loadVotes(array_keys($this->playerStorage->getOnline()), false);
    }

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return mixed
     */
    public function onEndMapStart($count, $time, $restarted, Map $map)
    {

    }

    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return mixed
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map)
    {

        if (!empty($this->changedVotes)) {
            $votes = [];
            foreach ($this->changedVotes as $vote) {
                $votes[] = $vote;
            }

            $this->mxKarma->saveVotes($map, (time() - $this->startTime), $votes);
        }
    }

}
