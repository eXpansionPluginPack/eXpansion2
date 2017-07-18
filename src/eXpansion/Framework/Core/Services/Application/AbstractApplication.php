<?php


namespace eXpansion\Framework\Core\Services\Application;

use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractApplication implements RunInterface
{
    /** Base eXpansion callbacks. */
    const EVENT_BEFORE_INIT = "expansion.before_init";
    const EVENT_AFTER_INIT = "expansion.after_init";
    const EVENT_READY = "expansion.ready";
    const EVENT_STOP = "expansion.stop";

    /** @var Connection */
    protected $connection;

    /** @var Dispatcher */
    protected $dispatcher;

    /** @var Console */
    protected $console;

    /** @var bool */
    protected $isRunning = true;

    /**
     * Application constructor.
     *
     * @param DispatcherInterface $dispatcher
     * @param Connection $connection
     * @param Console $output
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        Connection $connection,
        Console $output
    ) {
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
        $this->console = $output;
    }

    /**
     * Initialize eXpansion.
     *
     * @param ConsoleOutputInterface $console
     *
     * @return $this
     */
    public function init(OutputInterface $console)
    {
        $this->console->init($console);

        $this->dispatcher->dispatch(self::EVENT_BEFORE_INIT, []);
        $this->dispatcher->init();
        $this->dispatcher->dispatch(self::EVENT_AFTER_INIT, []);

        return $this;
    }

    /**
     * Run eXpansion
     *
     */
    public function run()
    {

        $startTime = microtime(true);
        $nextCycleStart = $startTime;
        $cycleTime = 1 / 60;

        $this->console->writeln("Running preflight checks...");
        $this->connection->enableCallbacks(true);

        // need to send this for scripts to start callback handling
        $this->connection->triggerModeScriptEvent("XmlRpc.EnableCallbacks", ["True"]);

        $this->dispatcher->dispatch(self::EVENT_READY, []);

        $this->console->writeln("And takeoff");

        do {
            $this->executeRun();

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
        $this->dispatcher->dispatch(self::EVENT_STOP, []);
        $this->isRunning = false;
    }

    abstract protected function executeRun();
}
