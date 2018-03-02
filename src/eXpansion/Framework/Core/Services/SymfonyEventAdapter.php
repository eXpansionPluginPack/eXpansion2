<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Model\Event\DedicatedEvent;
use eXpansion\Framework\Core\Services\Application\EventProcessorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class StorageProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Core\Services
 */
class SymfonyEventAdapter implements EventProcessorInterface
{
    /** @var EventDispatcherInterface */
    protected $symfonyEventDispatcher;

    /**
     * SymfonyEventAdapter constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
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
     * @param array  $params Parameters
     */
    public function dispatch($eventName, $params)
    {
        $event = new DedicatedEvent();
        $event->setParameters($params);
        $this->symfonyEventDispatcher->dispatch($eventName, $event);
    }
}