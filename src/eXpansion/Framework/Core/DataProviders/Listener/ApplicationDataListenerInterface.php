<?php

namespace eXpansion\Framework\Core\DataProviders\Listener;

/**
 * Interface ApplicationDataListenerInterface
 *
 * @package eXpansion\Framework\Core\DataProviders\Listener;
 * @author  reaby
 */
interface ApplicationDataListenerInterface
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
