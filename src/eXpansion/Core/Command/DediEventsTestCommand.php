<?php

namespace eXpansion\Core\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class eXpansion....
 *
 * @package eXpansion\Core\Command
 */
class DediEventsTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('eXpansion:test:dedicated')
            ->setDescription("Test Dedicated events.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('expansion.core.services.application_debug')
            ->init($output)
            ->run();
    }
}
