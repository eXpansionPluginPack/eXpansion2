<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 15:57
 */

namespace eXpansion\Framework\Core\Services\DedicatedConnection;

use eXpansion\Framework\Core\Services\Application\DispatcherInterface as Dispatcher;
use eXpansion\Framework\Core\Services\Console;
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
    /** @var Connection */
    protected $connection;

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

    /** @var LoggerInterface */
    protected $logger;

    /** @var Console */
    protected $console;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Factory constructor.
     *
     * @param string          $host
     * @param int             $port
     * @param int             $timeout
     * @param string          $user
     * @param string          $password
     * @param LoggerInterface $logger
     * @param Console         $console
     * @param Dispatcher      $dispatcher
     */
    public function __construct(
        $host,
        $port,
        $timeout,
        $user,
        $password,
        LoggerInterface $logger,
        Console $console,
        Dispatcher $dispatcher
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger;
        $this->console = $console;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Attempt to connect to the dedicated server.
     *
     * @param int $maxAttempts
     *
     * @return Connection
     * @throws TransportException when connection fails.
     */
    public function createConnection($maxAttempts = 3)
    {

        if (is_null($this->connection)) {
            $lastException = $this->attemptConnection($maxAttempts);

            if (!is_null($lastException)) {
                $this->console->getSfStyleOutput()->error(
                    [
                        "Looks like your Dedicated server is either offline or has wrong config settings",
                        "Error message: ".$lastException->getMessage(),
                    ]
                );
                $this->logger->error("Unable to open connection for Dedicated server", ["exception" => $lastException]);

                throw $lastException;
            }
        }

        return $this->connection;
    }

    /**
     * @param $maxAttempts
     *
     * @return \Exception|TransportException|null
     */
    protected function attemptConnection($maxAttempts)
    {
        $attempts = 0;
        $lastException = null;

        do {

            if (!is_null($lastException)) {
                // Not first error.
                $lastException = null;

                $this->console->getSfStyleOutput()->block(
                    "Will attempt to re-connect to dedicated server in 30seconds"
                );
                sleep(30);
            }

            try {
                $this->console->writeln('Attempting to connect to the dedicated server!');

                $this->connection = Connection::factory(
                    $this->host,
                    $this->port,
                    $this->timeout,
                    $this->user,
                    $this->password
                );
                $this->console->writeln('Dedicated server at '.$this->host.':'.$this->port.' $0f0Connected!');
                $this->dispatcher->dispatch("expansion.connected", null);

            } catch (\Exception $e) {
                $lastException = $e;
                $attempts++;
                $remainingAttempts = $maxAttempts - $attempts;

                $this->console->getSfStyleOutput()->error(
                    [
                        "Could not connect to the dedicated server !",
                        "Attempt : $attempts, Remaining attempts : $remainingAttempts ",
                        $e->getMessage(),
                    ]
                );
            }
        } while ($attempts < $maxAttempts && !is_null($lastException));

        return $lastException;
    }

    /**
     * Get connection to the dedicated.
     *
     * @return null|Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
