<?php


namespace eXpansion\Framework\Core\Services\Application;

use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use Maniaplanet\DedicatedServer\Connection;
use Propel\Runtime\Connection\Exception\ConnectionException;
use Propel\Runtime\Propel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractApplication implements RunInterface
{
    /** Base eXpansion callbacks. */
    const EVENT_BEFORE_INIT = "expansion.before_init";
    const EVENT_AFTER_INIT = "expansion.after_init";
    const EVENT_READY = "expansion.ready";
    const EVENT_STOP = "expansion.stop";

    const EXPANSION_VERSION = "dev";

    /** @var Factory */
    protected $factory;

    /** @var DispatcherInterface */
    protected $dispatcher;

    /** @var Console */
    protected $console;

    /** @var bool */
    protected $isRunning = true;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AbstractApplication constructor.
     *
     * @param DispatcherInterface $dispatcher
     * @param Factory $factory
     * @param Console $output
     * @param LoggerInterface $logger
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        Factory $factory,
        Console $output,
        LoggerInterface $logger
    ) {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
        $this->console = $output;
        $this->logger = $logger;
    }

    /**
     * Initialize eXpansion.
     *
     * @param OutputInterface $console
     *
     * @return $this|mixed
     * @throws \Maniaplanet\DedicatedServer\Xmlrpc\TransportException
     */
    public function init(OutputInterface $console)
    {
        $this->checkPhpExtensions($console);

        $this->console->init($console, $this->dispatcher);
        $this->dispatcher->dispatch(self::EVENT_BEFORE_INIT, []);

        $this->factory->createConnection();
        $this->dispatcher->init($this->factory->getConnection());

        $this->dispatcher->dispatch(self::EVENT_AFTER_INIT, []);
        return $this;
    }

    protected function checkPhpExtensions(OutputInterface $console)
    {
        $extensions = array(
            'openssl' => 'extension=php_openssl.dll',
            'curl' => 'extension=curl.dll',
        );
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $extensions['com_dotnet'] = 'extension=php_com_dotnet.dll';
        }

        $status = true;
        $showIni = false;
        foreach ($extensions as $extension => $description) {
            if (!extension_loaded($extension)) {
                $console->writeln(
                    "<error>eXpansion needs PHP extension $extension to run. Enable it to run eXpansion => " . $description . "</error>"
                );
                $status = false;
                $showIni = true;
            }
        }

        $recommend = array(
            'xmlrpc' => "It will have better performances !",
        );
        foreach ($recommend as $extension => $reason) {
            if (!extension_loaded($extension)) {
                $console->writeln(
                    "<error>eXpansion works better with PHP extension</error> <info>$extension</info>: " . $reason . ""
                );
                $showIni = true;
            }
        }

        if ($showIni) {
            $console->writeln('<info>[PHP] PHP is using fallowing ini file :</info> "'. php_ini_loaded_file() .'"');
            sleep(5);
        }
        return $status;
    }

    /**
     * Run eXpansion
     *
     * @inheritdoc
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
        $this->factory->getConnection()->enableCallbacks(true);

        // need to send this for scripts to start callback handling
        try {
            $this->factory->getConnection()->triggerModeScriptEvent("XmlRpc.EnableCallbacks", ["True"]);
        } catch (\Exception $exception) {
            $this->factory->getConnection()->saveMatchSettings('MatchSettings/eXpansion-mode-fail-'.date(DATE_ISO8601).'.txt');
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
            if ($delay > $cycleTime / 2 && $lastGcTime < (time() - $gcCycleTime)) {
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

        $this->factory->getConnection()->sendHideManialinkPage(null);
        $this->factory->getConnection()->triggerModeScriptEvent("Shootmania.UI.ResetProperties", []);
        $this->factory->getConnection()->triggerModeScriptEvent("Trackmania.UI.ResetProperties", []);
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
