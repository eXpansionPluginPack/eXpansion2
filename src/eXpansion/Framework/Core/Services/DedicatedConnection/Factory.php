<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 15:57
 */

namespace eXpansion\Framework\Core\Services\DedicatedConnection;

use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\TransportException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Service factory to create connection to the dedicated server.
 *
 * @package eXpansion\Framework\Core\Services\DedicatedConnection
 */
class Factory
{
    const EVENT_CONNECTED = 'expansion.dedicated.connected';

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

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /**
     * Factory constructor.
     *
     * @param                          $host
     * @param                          $port
     * @param                          $timeout
     * @param                          $user
     * @param                          $password
     * @param LoggerInterface          $logger
     * @param Console                  $console
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        $host,
        $port,
        $timeout,
        $user,
        $password,
        LoggerInterface $logger,
        Console $console,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger;
        $this->console = $console;
        $this->eventDispatcher = $eventDispatcher;
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
            $lastExcelption = $this->attemptConnection($maxAttempts);

            if (!is_null($lastExcelption)) {
                $this->console->getSfStyleOutput()->error(
                    [
                        "Looks like your Dedicated server is either offline or has wrong config settings",
                        "Error message: " . $lastExcelption->getMessage()
                    ]
                );
                $this->logger->error("Unable to open connection for Dedicated server", ["exception" => $lastExcelption]);

                throw $lastExcelption;
            }

            // Dispatch connected event.
            $event = new GenericEvent($this->connection);
            $this->eventDispatcher->dispatch(self::EVENT_CONNECTED, $event);
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
        $lastExcelption = null;

        do {

            if (!is_null($lastExcelption)) {
                // Not first error.
                $lastExcelption = null;

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

            } catch (\Exception $e) {
                $lastExcelption = $e;
                $attempts++;
                $remainingAttemps = $maxAttempts - $attempts;

                $this->console->getSfStyleOutput()->error(
                    [
                        "Cound't connect to the dedicated server !",
                        "Attempt : $attempts, Remaining attemps : $remainingAttemps ",
                        $e->getMessage(),
                    ]
                );
            }
        } while($attempts < $maxAttempts && !is_null($lastExcelption));

        return $lastExcelption;
    }

    /**
     * Get connection to the dedicated.
     *
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
