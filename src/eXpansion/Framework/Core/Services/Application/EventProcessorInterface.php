<?php

namespace eXpansion\Framework\Core\Services\Application;

/**
 * interface EventProcessorInterface
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
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