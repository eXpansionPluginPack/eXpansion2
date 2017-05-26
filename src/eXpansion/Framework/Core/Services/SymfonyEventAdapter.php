<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Model\Event\DedicatedEvent;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use eXpansion\Framework\Core\Services\Application\EventProcessorInterface;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class StorageProvider
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Services
 */
class SymfonyEventAdapter implements EventProcessorInterface
{
    /** @var EventDispatcherInterface */
    protected $symfonyEventDispatcher;


    /**
     * StorageProvider constructor.
     *
     * @param $connection
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->symfonyEventDispatcher = $eventDispatcher;
    }


    /**
     * Dispatch event as it needs to be.
     *
     * @param string $eventName Name of the event.
     * @param array $params Parameters
     */
    public function dispatch($eventName, $params)
    {

        $event = new DedicatedEvent();
        $event->setParameters($params);
        $this->symfonyEventDispatcher->dispatch("maniaplanet.game." . $eventName, $event);
    }
}