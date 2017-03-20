<?php

namespace eXpansion\Core\Services;

use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * eXpansion Application main routine.
 *
 * @package eXpansion\Core\Services
 */
class Application
{
    /** @var  Connection */
    protected $connection;

    /** @var  PluginManager */
    protected $pluginManager;

    /** @var  DataProviderManager */
    protected $dataProviderManager;

    /** @var Console */
    protected $console;

    protected $isRunning = true;

    /** Base eXpansion callbacks. */
    const EVENT_RUN = "expansion.run";
    const EVENT_PRE_LOOP = "expansion.pre_loop";
    const EVENT_POST_LOOP = "expansion.post_loop";

    /**
     * Application constructor.
     *
     * @param PluginManager $pluginManager
     * @param DataProviderManager $dataProviderManager
     * @param Connection $connection
     * @param Console $output
     */
    public function __construct(
        PluginManager $pluginManager,
        DataProviderManager $dataProviderManager,
        Connection $connection,
        Console $output
    ) {
        $this->pluginManager = $pluginManager;
        $this->connection = $connection;
        $this->dataProviderManager = $dataProviderManager;
        $this->console = $output;
    }

    /**
     * Initialize eXpansion.
     *
     * @param OutputInterface $output
     * @return $this
     */
    public function init(ConsoleOutputInterface $console)
    {
        $this->console->init($console);

        $this->console->writeln('$fff            8b        d8$fff              $0d0   ad888888b, ');
        $this->console->writeln('$fff             Y8,    ,8P $fff              $0d0  d8"     "88 ');
        $this->console->writeln('$fff              `8b  d8\' $fff               $0d0          a8  ');
        $this->console->writeln('$fff ,adPPYba,      Y88P    $fff  8b,dPPYba,  $0d0       ,d8P"  ');
        $this->console->writeln('$fffa8P_____88      d88b    $fff  88P\'    "8a $0d0     a8P"     ');
        $this->console->writeln('$fff8PP"""""""    ,8P  Y8,  $fff  88       d8 $0d0   a8P\'      ');
        $this->console->writeln('$fff"8b,   ,aa   d8\'    `8b$fff   88b,   ,a8" $0d0  d8"         ');
        $this->console->writeln('$fff `"Ybbd8"\'  8P        Y8$fff  88`YbbdP"\'  $0d0  88888888888');
        $this->console->writeln('$fff                        $fff  88          $0d0                ');
        $this->console->writeln('$777  eXpansion v.2.0.0.0   $fff  88          $0d0               ');

        $this->pluginManager->init();
        $this->dataProviderManager->init();

        return $this;
    }

    /**
     * Run eXpansion
     *
     */
    public function run()
    {
        $this->connection->enableCallbacks(true);

        $startTime = microtime(true);
        $nextCycleStart = $startTime;
        $cycleTime = 1 / 60;

        $this->console->writeln("Running preflight checks...");

        // need to send this for scripts to start callback handling
        $this->connection->triggerModeScriptEventArray("XmlRpc.EnableCallbacks", ["True"]);

        $this->dataProviderManager->dispatch(self::EVENT_RUN, []);

        $this->console->writeln("And takeoff");

        do {
            $this->dataProviderManager->dispatch(self::EVENT_PRE_LOOP, []);

            $calls = $this->connection->executeCallbacks();
            if (!empty($calls)) {
                foreach ($calls as $call) {
                    $method = preg_replace('/^[[:alpha:]]+\./', '', $call[0]); // remove trailing "Whatever."
                    $params = (array) $call[1];
                    $this->console->writeln('$fffCallback: $070'.$method);
                    $this->console->writeln('$ff0Params: $777'.print_r($params, true));

                    $this->dataProviderManager->dispatch($method, $params);
                }
            }
            $this->connection->executeMulticall();
            $this->dataProviderManager->dispatch(self::EVENT_POST_LOOP, []);

            $endCycleTime = microtime(true) + $cycleTime / 10;
            do {
                $nextCycleStart += $cycleTime;
            } while ($nextCycleStart < $endCycleTime);

            @time_sleep_until($nextCycleStart);
        } while ($this->isRunning);
    }

    /**
     * Stop eXpansion.
     */
    public function stopApplication()
    {
        $this->isRunning = false;
    }
}
