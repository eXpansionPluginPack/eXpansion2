<?php

namespace eXpansion\Framework\Core\DataProviders\Listener;

/**
 * Interface ListenerInterfaceExpApplication
 *
 * @package eXpansion\Framework\Core\DataProviders\Listener;
 * @author  reaby
 */
interface ListenerInterfaceExpApplication
{
    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit();

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady();

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop();


}
