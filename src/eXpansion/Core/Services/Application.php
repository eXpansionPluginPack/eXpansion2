<?php

namespace eXpansion\Core\Services;

use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * eXpansion Application main routine.
 *
 * @package eXpansion\Core\Services
 */
class Application
{
    /**
     * @var  Connection
     */
    protected $connection;

    /** @var  PluginManager */
    protected $pluginManager;

    /** @var  DataProviderManager */
    protected $dataProviderManager;

    /** Base eXpansion callbacks. */
    const EVENT_RUN = "expansion.run";
    const EVENT_PRE_LOOP = "expansion.pre_loop";
    const EVENT_POST_LOOP = "expansion.post_loop";

    /**
     * Application constructor.
     * @param PluginManager $pluginManager
     * @param DataProviderManager $dataProviderManager
     * @param Connection $connection
     */
    public function __construct(
        PluginManager $pluginManager,
        DataProviderManager $dataProviderManager,
        Connection $connection
    ) {
        $this->pluginManager = $pluginManager;
        $this->connection = $connection;
        $this->dataProviderManager = $dataProviderManager;
    }

    /**
     * Initialize eXpansion.
     *
     * @param OutputInterface $output
     * @return $this
     */
    public function init(OutputInterface $output)
    {
        $output->writeln("eXpansion is starting...");
        $this->pluginManager->init();
        $this->dataProviderManager->init();

        return $this;
    }

    /**
     * Run eXpansion
     *
     * @param OutputInterface $output
     */
    public function run(OutputInterface $output)
    {
        $this->connection->enableCallbacks(true);

        $startTime = microtime(true);
        $nextCycleStart = $startTime;
        $cycleTime = 1 / 60;

        $output->writeln("Running preflight checks...");

        $this->dataProviderManager->dispatch(self::EVENT_RUN, []);

        $output->writeln("And takeoff");

        while (true) {
            $this->dataProviderManager->dispatch(self::EVENT_PRE_LOOP, []);

            $calls = $this->connection->executeCallbacks();
            if (!empty($calls)) {
                foreach ($calls as $call) {
                    $method = preg_replace('/^[[:alpha:]]+\./', '', $call[0]); // remove trailing "Whatever."
                    $params = (array) $call[1];

                    $this->dataProviderManager->dispatch($method, $params);
                }
            }
            $this->connection->executeMulticall();
            $this->dataProviderManager->dispatch(self::EVENT_POST_LOOP, []);


            $endCycleTime = microtime(true) + $cycleTime / 10;
            do {
                $nextCycleStart += $cycleTime;
            }
            while ($nextCycleStart < $endCycleTime);
            @time_sleep_until($nextCycleStart);
        }
    }
}
