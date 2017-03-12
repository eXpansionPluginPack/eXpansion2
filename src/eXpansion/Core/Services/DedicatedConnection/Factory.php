<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 15:57
 */

namespace eXpansion\Core\Services\DedicatedConnection;

use Maniaplanet\DedicatedServer\Connection;

/**
 * Service factory to create connection to the dedicated server.
 *
 * @package eXpansion\Core\Services\DedicatedConnection
 */
class Factory
{
    /** @var string The name/ip of the host */
    protected $host;

    /** @var int */
    protected $port;

    /** @var int */
    protected $timeout;

    /** @var string */
    protected $user;

    /** @var string */
    protected $password;

    /**
     * Factory constructor.
     *
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @param string $user
     * @param string $password
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