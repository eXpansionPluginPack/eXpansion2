<?php


namespace eXpansion\Framework\Core\Services\Application;

use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractApplication implements RunInterface
{
    /** Base eXpansion callbacks. */
    const EVENT_BEFORE_INIT = "expansion.before_init";
    const EVENT_AFTER_INIT = "expansion.after_init";
    const EVENT_READY = "expansion.ready";
    const EVENT_STOP = "expansion.stop";

    const EXPANSION_VERSION = "2.0.0.0";
    const SCRIPT_API_VERSION = "2.4.0";

    /** @var Connection */
    protected $connection;

    /** @var DispatcherInterface */
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
     * @param OutputInterface $console
     *
     * @return $this
     */
    public function init(OutputInterface $console)
    {
        $this->console->init($console, $this->dispatcher);
        $this->dispatcher->dispatch(self::EVENT_BEFORE_INIT, []);
        $this->dispatcher->init($this->connection);
        $this->dispatcher->dispatch(self::EVENT_AFTER_INIT, []);

        return $this;
    }

    /**
     * Run eXpansion
     */
    public function run()
    {
        // Time each cycle needs to take in microseconds. Wrunning 60 cycles per seconds to have optimal response time.
        $cycleTime = (1 / 60) * 1000000;

        // Running GC collect every 5 minutes should be sufficient.;
        $gcCycleTime = 60 * 5;

        // Time when we will force gc cycles.
        $maxGcCycleTime = 60 * 20;

        // Last time garbage collector ran. Assume that at start it ran.
        $lastGcTime = time();

        $this->console->writeln("Running preflight checks...");
        $this->connection->enableCallbacks(true);

        // need to send this for scripts to start callback handling
        try {
            $this->connection->triggerModeScriptEvent("XmlRpc.EnableCallbacks", ["True"]);
            $this->connection->triggerModeScriptEvent("XmlRpc.SetApiVersion", [self::SCRIPT_API_VERSION]);
        } catch (\Exception $exception) {
            $this->connection->saveMatchSettings('MatchSettings/eXpansion-mode-fail-'.date(DATE_ISO8601).'.txt');
            throw $exception;
        }

        $this->console->writeln("preflight checks OK.");
        $this->dispatcher->dispatch(self::EVENT_READY, []);
        $this->console->writeln("And takeoff");

        do {
            $startTime = microtime(true);

            // Run the actuall application
            $this->executeRun();

            $endTime = microtime(true);
            $delay = $cycleTime - (($endTime - $startTime) * 1000000);

            // If we got lot's of time and it's been a while since last GC collect let's run a garbage collector
            // cycle this iteration.
            if ($delay > $cycleTime/2 && $lastGcTime < (time() - $gcCycleTime)) {
                // PHP does this automatically as well but in some mysterious ways it can sometimes keep in memory
                // hundred of mb's before running it.
                gc_collect_cycles();
                $lastGcTime = time();

                // Renew delay so that this iteration isn't much slower then the others
                $endTime = microtime(true);
                $delay = $cycleTime - (($endTime - $startTime) * 1000000);
            }

            if ($lastGcTime < (time() - $maxGcCycleTime)) {
                //It's been a while since last Garbage collection forcing it to go even through the application is
                // running slow.
                gc_collect_cycles();
                $lastGcTime = time();

            } elseif ($delay > 0) {
                usleep($delay);
            }
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
