<?php

namespace eXpansion\Framework\Core\Command;

use eXpansion\Framework\Core\Services\ApplicationDebug;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class eXpansion....
 *
 * @package eXpansion\Framework\Core\Command
 */
class DediEventsTestCommand extends ContainerAwareCommand
{
    /** @var  ApplicationDebug */
    protected $expansionApplicationDebug;

    /**
     * DediEventsTestCommand constructor.
     *
     * @param ApplicationDebug $expansionApplicationDebug
     */
    public function __construct(ApplicationDebug $expansionApplicationDebug)
    {
        parent::__construct();

        $this->expansionApplicationDebug = $expansionApplicationDebug;
    }


    protected function configure()
    {
        $this->setName('eXpansion:test:dedicated')
            ->setDescription("Test Dedicated events.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->expansionApplicationDebug
            ->init($output)
            ->run();
    }
}
