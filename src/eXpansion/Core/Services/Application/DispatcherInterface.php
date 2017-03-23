<?php


namespace eXpansion\Core\Services\Application;


interface DispatcherInterface
{
    /**
     * Initialize the dispatcher and it's dependencies.
     *
     * @return void
     */
    public function init();

    /**
     * Dispatch the event.
     *
     * @param $event
     * @param $params
     *
     * @return mixed
     */
    public function dispatch($event, $params);
}