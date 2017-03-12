<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 12:17
 */

namespace eXpansion\Core\Services;


use Maniaplanet\DedicatedServer\Connection;

class Application
{
    /**
     * @TODO use DI to inject this.
     *
     * @var  Connection
     */
    protected $connection;

    /** @var  PluginManager */
    protected $pluginManager;

    /** @var  DataProviderManager */
    protected $dataProviderManager;

    /**
     * Application constructor.
     * @param $pluginManager
     */
    public function __construct(PluginManager $pluginManager, DataProviderManager $dataProviderManager)
    {
        $this->pluginManager = $pluginManager;
        $this->dataProviderManager = $dataProviderManager;
    }

    public function init()
    {
        $this->connection = Connection::factory(
            "localhost",
            5000,
            3600
        );

        $this->pluginManager->init();
        $this->dataProviderManager->init();

        return $this;
    }

    public function run()
    {
        $this->connection->enableCallbacks(true);

        $startTime = microtime(true);
        $nextCycleStart = $startTime;
        $cycleTime = 1 / 60;

        while(true)
        {
            $calls = $this->connection->executeCallbacks();
            if(!empty($calls))
            {
                foreach($calls as $call)
                {
                    $method = preg_replace('/^[[:alpha:]]+\./', '', $call[0]); // remove trailing "Whatever."
                    $params = (array) $call[1];

                    $this->dataProviderManager->dispatch($method, $params);
                }
            }
            $this->connection->executeMulticall();


            $endCycleTime = microtime(true) + $cycleTime / 10;
            do
            {
                $nextCycleStart += $cycleTime;
            }
            while($nextCycleStart < $endCycleTime);
            @time_sleep_until($nextCycleStart);
        }
    }

}