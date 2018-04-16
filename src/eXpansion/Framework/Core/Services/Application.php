<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Services\Application\AbstractApplication;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * eXpansion Application main routine.
 *
 * @package eXpansion\Framework\Core\Services
 */
class Application extends AbstractApplication {

    /** Base eXpansion callbacks. */
    const EVENT_PRE_LOOP = "expansion.pre_loop";
    const EVENT_POST_LOOP = "expansion.post_loop";

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
        $this->console->init($console, $this->dispatcher);

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

        return parent::init($console);
    }


    protected function executeRun()
    {
        $this->dispatcher->dispatch(self::EVENT_PRE_LOOP, []);

        $calls = $this->factory->getConnection()->executeCallbacks();
        if (!empty($calls)) {
            foreach ($calls as $call) {
                $method = preg_replace('/^[[:alpha:]]+\./', '', $call[0]); // remove trailing "Whatever."
                $params = (array) $call[1];

                $this->dispatcher->dispatch($method, $params);
            }
        }

        $this->factory->getConnection()->executeMulticall();
        $this->dispatcher->dispatch(self::EVENT_POST_LOOP, []);
    }
}
