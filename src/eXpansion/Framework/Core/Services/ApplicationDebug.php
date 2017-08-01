<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Services\Application\AbstractApplication;

/**
 * eXpansion Application main routine.
 *
 * @package eXpansion\Framework\Core\Services
 */
class ApplicationDebug extends AbstractApplication
{

    protected function executeRun()
    {

        $calls = $this->connection->executeCallbacks();
        if (!empty($calls)) {
            foreach ($calls as $call) {
                $method = preg_replace('/^[[:alpha:]]+\./', '', $call[0]); // remove trailing "Whatever."
                $params = (array) $call[1];

                $this->dispatcher->dispatch($method, $params);
            }
        }
        $this->connection->executeMulticall();
    }
}
