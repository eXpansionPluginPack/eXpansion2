<?php

namespace eXpansion\Framework\Core\Model\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Class Event
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Model
 */
class DedicatedEvent extends BaseEvent
{
    /** @var array */
    protected $parameters;

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}
