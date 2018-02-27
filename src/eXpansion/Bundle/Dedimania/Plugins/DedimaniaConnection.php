<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 16.19
 */

namespace eXpansion\Bundle\Dedimania\Plugins;


use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Xmlrpc\Request;

class DedimaniaConnection implements ListenerInterfaceExpTimer
{

    const dedimaniaUrl = "http://dedimania.net:8081/Dedimania";

    private $read = [];
    private $write = [];
    private $except = [];

    /** @var \Webaccess */
    protected $webaccess;
    /**
     * @var Console
     */
    private $console;
    /** @var int */
    private $tryTimer = -1;

    /** @var int */
    private $lastUpdate;
    /** @var string|null */
    private $sessionId = null;


    /**
     * DedimaniaConnection constructor.
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        require_once(dirname(__DIR__).DIRECTORY_SEPARATOR."Classes".DIRECTORY_SEPARATOR."Webaccess.php");
        $this->webaccess = new \Webaccess($console);
        $this->console = $console;
    }

    /** @api */
    public function onPreLoop()
    {
        // do nothing
    }

    /** @api */
    public function onPostLoop()
    {
        try {
            if ($this->tryTimer == -1) {
                $this->webaccess->select($this->read, $this->write, $this->except, 0, 0);
            } else {
                if (time() >= $this->tryTimer) {
                    $this->console->writeln("Webaccess: Main Loop active again!");
                    $this->tryTimer = -1;
                }
            }
        } catch (\Exception $e) {
            $this->console->writeln(
                'Webaccess: OnTick Update $f00failed$555, trying to retry in 2 seconds...');
            $this->tryTimer = time() + 2;
        }
    }

    /** @api */
    public function onEverySecond()
    {
        try {
            if ($this->sessionId !== null && (time() - $this->lastUpdate) > 240) {
                $this->console->writeln("Dedimania: sent connection keep-alive!");
                //    $this->updateServerPlayers($this->storage->currentMap);
                $this->console->writeln("$0f0Dedimania: Should now update players for current map");

                $this->lastUpdate = time();
            }
        } catch (\Exception $e) {
            $this->console->writeln("Dedimania: periodic keep-alive failed: ".$e->getMessage());
        }
    }


    /**
     * Send a request to Dedimania
     *
     * @param string   $request
     * @param callable $callback
     */
    final public function sendRequest($request, $callback)
    {
        $this->webaccess->request(
            self::dedimaniaUrl,
            [[$this, "process"], $callback],
            $request,
            true,
            600,
            3,
            5,
            'eXpansion server controller',
            'application/x-www-form-urlencoded; charset=UTF-8'
        );
    }

    final public function process($response, $callback)
    {

        try {

            if (is_array($response) && array_key_exists('Message', $response)) {

                $message = Request::decode($response['Message']);
                $errors = end($message[1]);

                if (count($errors) > 0 && array_key_exists('methods', $errors[0])) {
                    foreach ($errors[0]['methods'] as $error) {
                        if (!empty($error['errors'])) {
                            $this->console->writeln('Dedimania error on method: $fff'.$error['methodName']);
                            $this->console->writeln('$f00'.$error['errors']);
                        }
                    }
                }

                $array = $message[1];
                unset($array[count($array) - 1]); // remove trailing errors and info

                if (array_key_exists("faultString", $array[0])) {
                    $this->console->writeln('Dedimania fault:$f00 '.$array[0]['faultString']);

                    return;
                }

                if (!empty($array[0][0]['Error'])) {
                    $this->console->writeln('Dedimania error:$f00 '.$array[0][0]['Error']);

                    return;
                }

                call_user_func_array($callback, [$array[0][0]]);

                return;
            } else {
                $this->console->writeln('Dedimania Error: $f00Can\'t find Message from Dedimania reply');
            }
        } catch (\Exception $e) {
            $this->console->writeln('Dedimania Error: $f00Connection to dedimania server failed.'.$e->getMessage());
        }
    }

    /**
     * @return null|string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param null|string $sessionId
     */
    public function setSessionId(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }

}