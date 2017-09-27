<?php

namespace eXpansion\Framework\Core\Command;

use eXpansion\Framework\Core\Services\Application;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class eXpansion....
 *
 * @package eXpansion\Framework\Core\Command
 */
class RunCommand extends ContainerAwareCommand
{
    /** @var Application */
    protected $expansionApplication;

    /**
     * RunCommand constructor.
     *
     * @param Application $expansionApplication
     */
    public function __construct(Application $expansionApplication)
    {
        parent::__construct();

        $this->expansionApplication = $expansionApplication;
    }


    protected function configure()
    {
        $this->setName('eXpansion:run')
            ->setDescription("Run eXpansion on your server.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->expansionApplication
            ->init($output)
            ->run();
    }
}
