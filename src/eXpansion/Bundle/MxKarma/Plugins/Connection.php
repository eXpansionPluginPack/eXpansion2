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
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use Maniaplanet\DedicatedServer\Structures\GameInfos;
use oliverde8\AsynchronousJobs\Job\CallbackCurl;

/**
 * Description of Connection
 *
 * @author Reaby
 */
class Connection
{

   // private $address = "http://karma.mania-exchange.com/api2/";
    private $address = "http://localhost/index.html?";

    private $connected = false;

    private $sessionKey = null;

    private $sessionSeed = null;

    private $apiKey = "";

    /** @var MXRating */
    private $ratings = null;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Connection constructor.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher, Http $http)
    {
        $this->dispatcher = $dispatcher;
        $this->http = $http;
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
            "applicationIdentifier" => "eXpansion 2.0.0.0",
            "testMode" => "true",
        );

        $this->http->get(
            $this->buildUrl("startSession", $params),
            array($this, "xConnect")
        );
    }

    public function xConnect( $answer)
    {

        $data = $this->getObject($answer);

        if ($data === null) {
            return;
        }

        $this->sessionKey = $data->sessionKey;
        $this->sessionSeed = $data->sessionSeed;

        $outHash = hash("sha512", ($this->apiKey.$this->sessionSeed));

        $params = array("sessionKey" => $this->sessionKey, "activationHash" => $outHash);
        $this->http->call(
            $this->buildUrl("activateSession", $params),
            array($this, "xActivate"),
            array(),
            "ManiaLive - eXpansionPluginPack",
            "application/json"
        );
    }

    public function xActivate($answer, $httpCode)
    {

        if ($httpCode != 200) {
            return;
        }

        $data = $this->getObject($answer, "onActivate");

        if ($data === null) {
            return;
        }

        if ($data->activated) {
            $this->connected = true;
            //  Dispatcher::dispatch(new MXKarmaEvent(MXKarmaEvent::ON_CONNECTED));
        }
    }

    public function getRatings($players = array(), $getVotesOnly = false)
    {
        if (!$this->connected) {
            return;
        }

        $params = array("sessionKey" => $this->sessionKey);
        $postData = array(
            "gamemode" => $this->getGameMode(),
            "titleid" => $this->expStorage->titleId,
            "mapuid" => $this->storage->currentMap->uId,
            "getvotesonly" => $getVotesOnly,
            "playerlogins" => $players,
        );
        $this->dataAccess->httpPost(
            $this->buildUrl("getMapRating", $params),
            json_encode($postData),
            array($this, "xGetRatings"),
            array(),
            "ManiaLive - eXpansionPluginPack",
            "application/json"
        );
    }

    public function saveVotes(\Maniaplanet\DedicatedServer\Structures\Map $map, $time, $votes)
    {
        if (!$this->connected) {
            return;
        }

        $params = array("sessionKey" => $this->sessionKey);
        $postData = array(
            "gamemode" => $this->getGameMode(),
            "titleid" => $this->expStorage->titleId,
            "mapuid" => $map->uId,
            "mapname" => $map->name,
            "mapauthor" => $map->author,
            "isimport" => false,
            "maptime" => $time,
            "votes" => $votes,
        );
        $this->dataAccess->httpPost(
            $this->buildUrl("saveVotes", $params),
            json_encode($postData),
            array($this, "xSaveVotes"),
            array(),
            "ManiaLive - eXpansionPluginPack",
            "application/json"
        );
    }

    public function xSaveVotes($answer, $httpCode)
    {

        if ($httpCode != 200) {
            return;
        }

        $data = $this->getObject($answer);

        if ($data === null) {
            return;
        }

        //  Dispatcher::dispatch(new MXKarmaEvent(MXKarmaEvent::ON_VOTE_SAVE, $data->updated));
    }

    /**
     * @param $answer
     * @param $httpCode
     *
     * @return MxRating|null
     */
    public function xGetRatings($answer, $httpCode)
    {

        if ($httpCode != 200) {
            return null;
        }

        $data = $this->getObject($answer);

        if ($data === null) {
            return null;
        }

        $this->ratings = new MXRating();
        $this->ratings->append($data);

        return $this->ratings;
    }

    public function getGameMode()
    {
        switch ($this->storage->gameInfos->gameMode) {
            case GameInfos::GAMEMODE_SCRIPT:
                $gamemode = strtolower($this->storage->gameInfos->scriptName);
                break;
        }

        return $gamemode;
    }

    /**
     * @param string $data json data
     *
     * @return null
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
     * @param object $object
     */
    public function handleErrors($obj)
    {
        switch ($obj->data->code) {
            case 1:
                echo "internal server error";
                break;
            case 2:
                echo "Session key is invalid (not activated, experied or got disabled).";
                break;
            case 4:
                echo "Some parameters are not provided.";
                break;
            case 5:
                echo "API key not found or suspended.";
                break;
            case 6:
                echo "Server not found or suspended.";
                break;
            case 7:
                echo "Cross-server call rejected.";
                break;
            case 8:
                echo "Invalid activation hash provided, session closed.";
                $this->connected = false;
                break;
            case 9:
                echo "Session already active.";
                break;
            case 10:
                echo "Unsupported Content-Type.";
                break;
            case 11:
                echo "Too many logins requested.";
                break;
            case 12:
                echo "Invalid JSON or invalid structure.";
                break;
            case 13:
                echo "Malformed vote request.";
                break;
            case 14:
                echo "no votes cast - please do not make requests if there are no votes!";
                break;
            case 15:
                echo "too many import votes - request a limit raise if needed";
                break;
            case 16:
                echo "Import rejected.";
                break;
            default:
                echo "unknown error";
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
     * @param $url
     * @param callable $callback
     */
    public function httpGet($url, callable $callback)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, "application/json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch)["http_code"];
        curl_close($ch);
        call_user_func($callback, $output, $httpCode);
    }

}
