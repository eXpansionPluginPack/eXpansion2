<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 12:36
 */

namespace eXpansion\Core\Model;


class ProviderListner
{
    protected $eventName;

    protected $provider;

    protected $method;

    /**
     * Model constructor.
     * @param $eventName
     * @param $provider
     * @param $method
     */
    public function __construct($eventName, $provider, $method)
    {
        $this->eventName = $eventName;
        $this->provider = $provider;
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param mixed $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param mixed $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

}