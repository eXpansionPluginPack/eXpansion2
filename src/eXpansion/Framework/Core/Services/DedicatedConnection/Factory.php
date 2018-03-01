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
     * Factory constructor.
     *
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @param string $user
     * @param string $password
     * @param LoggerInterface $logger
     */
    public function __construct(
        $host,
        $port,
        $timeout,
        $user,
        $password,
        LoggerInterface $logger,
        Console $console
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger;
        $this->console = $console;
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
