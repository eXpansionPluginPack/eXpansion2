<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 10:35
 */

namespace eXpansion\Core\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('eXpansion:run')
            ->setDescription("Run eXpansion on your server.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('expansion.core.services.application')
            ->init($output)
            ->run($output);
    }


}