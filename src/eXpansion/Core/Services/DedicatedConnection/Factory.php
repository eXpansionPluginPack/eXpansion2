<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 15:57
 */

namespace eXpansion\Core\Services\DedicatedConnection;


use Maniaplanet\DedicatedServer\Connection;

class Factory
{
    protected $host;
    protected $port;
    protected $timeout;
    protected $user;
    protected $password;

    /**
     * Factory constructor.
     * @param $host
     * @param $port
     * @param $timeout
     * @param $user
     * @param $password
     */
    public function __construct($host, $port, $timeout, $user, $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Connect to the dedicated server.
     *
     * @return Connection
     */
    public function createConnection()
    {
        return Connection::factory(
            $this->host,
            $this->port,
            $this->timeout,
            $this->user,
            $this->password
        );
    }
}