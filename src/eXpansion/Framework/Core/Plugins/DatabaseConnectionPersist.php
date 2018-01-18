<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use Propel\Runtime\Propel;
use Psr\Log\LoggerInterface;

/**
 * Class DatabaseConnectionPersist
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Framework\Core\Plugins
 */
class DatabaseConnectionPersist implements ListenerInterfaceExpTimer
{
    /** @var int */
    protected $lastPing = 0;

    /** @var int */
    protected $pingInterval;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * DatabaseConnectionPersist constructor.
     *
     * @param int $pingInterval
     * @param LoggerInterface $logger
     */
    public function __construct(int $pingInterval, LoggerInterface $logger)
    {
        $this->pingInterval = $pingInterval;
        $this->logger = $logger;
    }


    /**
     * @inheritdoc
     */
    public function onPreLoop()
    {
        // Nothing.
    }

    /**
     * @inheritdoc
     */
    public function onPostLoop()
    {
        // Nothing.
    }

    /**
     * @inheritdoc
     */
    public function onEverySecond()
    {
        if ((time() - $this->lastPing) > $this->pingInterval) {
            Propel::getConnection()->inTransaction();

            $this->logger->debug('Pinged database to persist connection!');
            $this->lastPing = time();
        }
    }
}