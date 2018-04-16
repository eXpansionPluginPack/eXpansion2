<?php

namespace eXpansion\Bundle\MxKarma\Plugins;


use eXpansion\Bundle\MxKarma\DataProviders\Listeners\ListenerInterfaceMxKarma;
use eXpansion\Bundle\MxKarma\Entity\MxRating;
use eXpansion\Bundle\MxKarma\Entity\MxVote;
use eXpansion\Bundle\MxKarma\Services\MxKarmaService;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use Maniaplanet\DedicatedServer\Structures\Map;


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
     * @var MxKarmaService
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
     * @var ConfigInterface
     */
    private $apikey;
    /**
     * @var ConfigInterface
     */
    private $serverLogin;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    /**
     * @var ConfigInterface
     */
    private $enabled;

    /**
     * MxKarma constructor.
     * @param ConfigInterface  $enabled
     * @param ConfigInterface  $apikey
     * @param ConfigInterface  $serverLogin
     * @param MxKarmaService   $mxKarma
     * @param Console          $console
     * @param ChatNotification $chatNotification
     * @param Dispatcher       $dispatcher
     * @param PlayerStorage    $playerStorage
     * @param GameDataStorage  $gameDataStorage
     */
    public function __construct(
        ConfigInterface $enabled,
        ConfigInterface $apikey,
        ConfigInterface $serverLogin,
        MxKarmaService $mxKarma,
        Console $console,
        ChatNotification $chatNotification,
        Dispatcher $dispatcher,
        PlayerStorage $playerStorage,
        GameDataStorage $gameDataStorage
    ) {

        $this->console = $console;
        $this->dispatcher = $dispatcher;
        $this->chatNotification = $chatNotification;
        $this->mxKarma = $mxKarma;
        $this->playerStorage = $playerStorage;
        $this->apikey = $apikey;
        $this->serverLogin = $serverLogin;
        $this->gameDataStorage = $gameDataStorage;
        $this->enabled = $enabled;
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

    }

    /**
     * Called when a player chats.
     *
     * @param Player $player
     * @param        $text
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
        // do nothing
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        $this->startTime = time();
        $this->connect();

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

    /**
     *
     */
    public function onMxKarmaConnect()
    {
        $this->mxKarma->loadVotes(array_keys($this->playerStorage->getOnline()), false);
    }

    /**
     * @param MxRating $mxRating
     * @return void
     */
    public function onMxKarmaVoteLoad(MxRating $mxRating)
    {

        $this->mxRating = $mxRating;
        $this->changedVotes = [];
        $this->votes = $mxRating->getVotes();

        $total = $mxRating->getVoteAverage();

        $yes = 0;
        $no = 0;
        if ($mxRating->getVoteAverage() > 0) {
            $yes = round(($mxRating->getVoteAverage() / 100) * $mxRating->getVoteCount());

            $no = round(((100 - $mxRating->getVoteAverage()) / 100) * $mxRating->getVoteCount());
        }

        $this->chatNotification->sendMessage('expansion_mxkarma.chat.votesloaded', null,
            ["%total%" => round($total, 2), "%positive%" => $yes, "%negative%" => $no]);

    }

    /**
     * @param MxVote[] $updatedVotes
     * @return void
     */
    public function onMxKarmaVoteSave($updatedVotes)
    {
        // do nothing
    }

    public function onMxKarmaDisconnect()
    {
        // do nothing
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
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        if ($this->enabled->get()) {
            if ($this->mxKarma->isConnected() == false) {
                $this->console->writeln("> MxKarma is at disconnected state, trying to establish connection.");
                $this->connect();
            }
        }
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
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {
        $this->startTime = time();
        $this->mxKarma->loadVotes(array_keys($this->playerStorage->getOnline()), false);
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

    protected function connect()
    {
        if ($this->enabled->get()) {
            if (empty($this->apikey->get())) {
                $this->console->writeln('> MxKarma: api key not set, $f00can\'t connect.');

                return;
            }

            if (empty($this->serverLogin->get())) {
                $this->console->writeln('> MxKarma: server login not set, $f00can\'t connect.');

                return;
            }

            if ($this->gameDataStorage->getSystemInfo()->serverLogin !== $this->serverLogin->get()) {
                $this->console->writeln("> MxKarma: server login doesn't match configured server login.");

                return;
            }

            $this->mxKarma->connect($this->serverLogin->get(), $this->apikey->get());
        }
    }


}
