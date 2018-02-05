<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 15:57
 */

namespace eXpansion\Framework\Core\Services\DedicatedConnection;

use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\TransportException;
use Psr\Log\LoggerInterface;

/**
 * Service factory to create connection to the dedicated server.
 *
 * @package eXpansion\Framework\Core\Services\DedicatedConnection
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Factory constructor.
     *
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @param string $user
     * @param string $password
     * @param LoggerInterface $logger
     */
    public function __construct($host, $port, $timeout, $user, $password, LoggerInterface $logger)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger;
    }

    /**
     * Connect to the dedicated server.
     *
     * @return Connection
     *
     * @throws TransportException When can't connect.
     */
    public function createConnection()
    {
        try {
            return Connection::factory(
                $this->host,
                $this->port,
                $this->timeout,
                $this->user,
                $this->password
            );
        } catch (TransportException $ex) {
            echo "Looks like your Dedicated server is either offline or has wrong config settings.\n";
            echo "Error message: " . $ex->getMessage();
            $this->logger->error("Unable to open connection for Dedicated server", ["exception" => $ex]);

            throw $ex;
        }
    }
}
