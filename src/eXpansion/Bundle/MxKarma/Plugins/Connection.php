<?php

/*
 * Copyright (C) 2014 Reaby
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace eXpansion\Bundle\MxKarma\Plugins;

use eXpansion\Bundle\MxKarma\Entity\MxRating;
use eXpansion\Bundle\MxKarma\Entity\MxVote;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\MapStorage;
use Maniaplanet\DedicatedServer\Structures\GameInfos;
use Maniaplanet\DedicatedServer\Structures\Map;
use oliverde8\AsynchronousJobs\Job\CallbackCurl;

/**
 * Description of Connection
 *
 * @author Reaby
 */
class Connection
{

    const EVENT_CONNECT = "expansion.mxkarma.connect";
    const EVENT_VOTESAVE = "expansion.mxkarma.votesave";
    const EVENT_VOTELOAD = 'expansion.mxkarma.voteload';
    const EVENT_DISCONNECT = 'expansion.mxkarma.disconnect';

    private $address = "http://karma.mania-exchange.com/api2/";
    private $options = [CURLOPT_HTTPHEADER => ["Content-Type:application/json"]];

    private $connected = false;

    private $sessionKey = null;

    private $sessionSeed = null;

    private $apiKey = "";

    /** @var MxRating */
    private $ratings = null;
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var Console
     */
    private $console;

    /** @var Http */
    private $http;

    /**
     * Connection constructor.
     *
     * @param Dispatcher $dispatcher
     * @param Http $http
     * @param GameDataStorage $gameDataStorage
     * @param MapStorage $mapStorage
     * @param Console $console
     */
    public function __construct(
        Dispatcher $dispatcher,
        Http $http,
        GameDataStorage $gameDataStorage,
        MapStorage $mapStorage,
        Console $console
    ) {
        $this->dispatcher = $dispatcher;
        $this->http = $http;
        $this->gameDataStorage = $gameDataStorage;
        $this->mapStorage = $mapStorage;
        $this->console = $console;
    }


    /**
     * connect to MX karma
     *
     * @param string $serverLogin
     * @param string $apiKey
     */
    public function connect($serverLogin, $apiKey)
    {
        $this->apiKey = $apiKey;

        $params = array(
            "serverLogin" => $serverLogin,
            "applicationIdentifier" => "eXpansion v ".AbstractApplication::EXPANSION_VERSION,
            "testMode" => "false",
        );
        $this->console->writeln('> MxKarma attempting to connect...');
        $this->http->get(
            $this->buildUrl(
                "startSession", $params),
            [$this, "xConnect"]
        );
    }

    /**
     * @param HttpResult $result
     */
    public function xConnect(HttpResult $result)
    {

        if ($result->hasError()) {
            $this->console->writeln('> MxKarma connection $f00 failure: '.$result->getError());
            $this->disconnect();

            return;
        }

        $data = $this->getObject($result->getResponse());

        if ($data === null) {
            return;
        }

        $this->sessionKey = $data->sessionKey;
        $this->sessionSeed = $data->sessionSeed;

        $outHash = hash("sha512", ($this->apiKey.$this->sessionSeed));

        $params = array("sessionKey" => $this->sessionKey, "activationHash" => $outHash);
        $this->console->writeln('> MxKarma attempting to activate session...');
        $this->http->get(
            $this->buildUrl("activateSession", $params),
            [$this, "xActivate"],
            [],
            $this->options
        );
    }

    public function xActivate(HttpResult $result)
    {

        if ($result->hasError()) {
            $this->console->writeln('> MxKarma connection $f00 failure: '.$result->getError());
            return;
        }

        $data = $this->getObject($result->getResponse());

        if ($data === null) {
            return;
        }

        if ($data->activated) {
            $this->connected = true;
            $this->console->writeln('> MxKarma connection $0f0success!');
            $this->dispatcher->dispatch(self::EVENT_CONNECT, []);
        }
    }

    /**
     * loads votes from server
     * @param array $players
     * @param bool $getVotesOnly
     */
    public function loadVotes($players = array(), $getVotesOnly = false)
    {
        if (!$this->connected) {
            $this->console->writeln('> MxKarma trying to load votes when not connected!');

            return;
        }

        $this->console->writeln('> MxKarma attempting to load votes...');
        $params = array("sessionKey" => $this->sessionKey);
        $postData = [
            "gamemode" => $this->getGameMode(),
            "titleid" => $this->gameDataStorage->getTitle(),
            "mapuid" => $this->mapStorage->getCurrentMap()->uId,
            "getvotesonly" => $getVotesOnly,
            "playerlogins" => $players,
        ];
        $this->http->post(
            $this->buildUrl("getMapRating", $params),
            json_encode($postData),
            array($this, "xGetRatings"),
            [],
            $this->options
        );
    }

    /**
     * @param Map $map
     * @param int $time time in seconds from BeginMap to EndMap
     * @param MxVote[] $votes
     */
    public function saveVotes(Map $map, $time, $votes)
    {
        if (!$this->connected) {
            $this->console->writeln('> MxKarma not connected.');

            return;
        }

        $params = array("sessionKey" => $this->sessionKey);
        $postData = array(
            "gamemode" => $this->getGameMode(),
            "titleid" => $this->gameDataStorage->getTitle(),
            "mapuid" => $map->uId,
            "mapname" => $map->name,
            "mapauthor" => $map->author,
            "isimport" => false,
            "maptime" => $time,
            "votes" => $votes,
        );


        $this->console->writeln('> MxKarma attempting to save votes...');
        $this->http->post(
            $this->buildUrl("saveVotes", $params),
            json_encode($postData),
            [$this, "xSaveVotes"],
            [],
            $this->options
        );
    }

    /**
     * @param HttpResult $result
     */
    public function xSaveVotes(HttpResult $result)
    {

        if ($result->hasError()) {
            $this->console->writeln('> MxKarma save votes $f00 failure: '.$result->getError());

            return;
        }

        $data = $this->getObject($result->getResponse());

        if ($data === null) {
            return;
        }
        $this->console->writeln('> MxKarma save votes $0f0success!');
        $this->dispatcher->dispatch(self::EVENT_VOTESAVE, ["updated" => $data->updated]);
    }

    /**
     * @param HttpResult $result
     * @return MxRating|null
     */
    public function xGetRatings(HttpResult $result)
    {

        if ($result->hasError()) {
            $this->console->writeln('> MxKarma load votes $f00 failure: '.$result->getError());

            return null;
        }

        $data = $this->getObject($result->getResponse());

        if ($data === null) {
            return null;
        }

        $this->ratings = new MXRating();
        $this->ratings->append($data);

        $this->console->writeln('> MxKarma load $0f0success!');
        $this->dispatcher->dispatch(self::EVENT_VOTELOAD, ["ratings" => $this->ratings]);

        return $this->ratings;
    }

    /**
     * @return string
     */
    public function getGameMode()
    {
        $gamemode = "";
        switch ($this->gameDataStorage->getGameInfos()->gameMode) {
            case GameInfos::GAMEMODE_SCRIPT:
                $gamemode = strtolower($this->gameDataStorage->getGameInfos()->scriptName);
                break;
            case GameInfos::GAMEMODE_ROUNDS:
                $gamemode = "Rounds";
                break;
            case GameInfos::GAMEMODE_CUP:
                $gamemode = "Cup";
                break;
            case GameInfos::GAMEMODE_TEAM:
                $gamemode = "Team";
                break;
            case GameInfos::GAMEMODE_LAPS:
                $gamemode = "Laps";
                break;
            case GameInfos::GAMEMODE_TIMEATTACK:
                $gamemode = "TimeAttack";
                break;
        }

        return $gamemode;
    }

    /**
     * @param string $data json data
     *
     * @return object|null
     */
    public function getObject($data)
    {
        $obj = (object)json_decode($data);
        if ($obj->success === false) {
            $this->handleErrors($obj);

            return null;
        }

        return $obj->data;
    }

    /**
     * @param object $obj
     */
    public function handleErrors($obj)
    {
        switch ($obj->data->code) {
            case 1:
                $this->console->writeln('> MxKarma $fffinternal server error');
                break;
            case 2:
                $this->console->writeln('> MxKarma $fffSession key is invalid (not activated, experied or got disabled).');
                break;
            case 4:
                $this->console->writeln('> MxKarma $fffSome parameters are not provided.');
                break;
            case 5:
                $this->console->writeln('> MxKarma $fffAPI key not found or suspended.');
                $this->disconnect();
                break;
            case 6:
                $this->console->writeln('> MxKarma $fffServer not found or suspended.');
                $this->disconnect();
                break;
            case 7:
                $this->console->writeln('> MxKarma $fffCross-server call rejected.');
                break;
            case 8:
                $this->console->writeln('> MxKarma $fffInvalid activation hash provided, session closed.');
                $this->disconnect();
                break;
            case 9:
                $this->console->writeln('> MxKarma $fffSession already active.');
                $this->disconnect();
                break;
            case 10:
                $this->console->writeln('> MxKarma $fffUnsupported Content-Type.');
                break;
            case 11:
                $this->console->writeln('> MxKarma $fffToo many logins requested.');
                break;
            case 12:
                $this->console->writeln('> MxKarma $fffInvalid JSON or invalid structure.');
                break;
            case 13:
                $this->console->writeln('> MxKarma $fffMalformed vote request.');
                break;
            case 14:
                $this->console->writeln('> MxKarma $fffno votes cast - please do not make requests if there are no votes!');
                break;
            case 15:
                $this->console->writeln('> MxKarma $ffftoo many import votes - request a limit raise if needed');
                break;
            case 16:
                $this->console->writeln('> MxKarma $fffImport rejected.');
                break;
            default:
                $this->console->writeln('> MxKarma $fffUnknown api error');
                break;
        }

        //   Dispatcher::dispatch(new MXKarmaEvent(MXKarmaEvent::ON_ERROR, $origin, $obj->data->code, $obj->data->message));
    }

    /**
     * @param string $method
     * @param array $params
     *
     * @return string
     */
    private function buildUrl($method, $params)
    {
        $url = $this->address.$method;

        return $url."?".http_build_query($params);
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->connected;
    }


    /**
     *
     */
    public function disconnect()
    {
        $this->connected = false;
        $this->console->writeln('> MxKarma $f00disconnected!');
        $this->dispatcher->dispatch(self::EVENT_DISCONNECT, []);
    }


}
