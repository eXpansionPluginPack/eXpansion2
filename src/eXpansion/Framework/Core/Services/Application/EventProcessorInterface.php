<?php

namespace eXpansion\Framework\Core\Services\Application;

/**
 * interface EventProcessorInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Core\Services\Application
 */
interface EventProcessorInterface
{
    /**
     * Dispatch event as it needs to be.
     *
     * @param string $eventName Name of the event.
     * @param array $params Parameters
     */
    public function dispatch($eventName, $params);
}