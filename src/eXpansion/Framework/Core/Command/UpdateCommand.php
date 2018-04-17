<?php

namespace eXpansion\Framework\Core\Command;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Notifications\Services\Notifications;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Class UpdateCommand
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\Core\Command
 */
class UpdateCommand extends ContainerAwareCommand
{
    /** @var Factory */
    protected $factory;

    /** @var Notifications */
    protected $notification;

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var Console */
    protected $console;

    /**
     * RunCommand constructor.
     */
    public function __construct(Factory $factory)
    {
        parent::__construct();

        $this->factory = $factory;
    }


    protected function configure()
    {
        $this->setName('eXpansion:update')
            ->setDescription("Update eXpansion");

        $this->addArgument("login", InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Initialize console output.
        $this->console->init(new NullOutput(), null);

        $sfs = new SymfonyStyle($input, $output);
        $playerLogin = $this->getPlayerLogin();

        $sfs->title("Updating Composer!");
        $this->notifyPlayer("", "Updating Composer!", $playerLogin);
        $this->runCommand("composer self-update", $playerLogin);

        $sfs->title("Updating eXpansion!");
        $this->notifyPlayer("", "Updating eXpansion!", $playerLogin);
        $this->runCommand("composer update --prefer-dist --prefer-stable --no-suggest --no-dev -o", $playerLogin);
    }

    protected function runCommand($command, $playerLogin)
    {
        $process = new Process($command);
        $process->setWorkingDirectory($this->getContainer()->getParameter('kernel.root_dir'));

        $process->run(function ($type, $buffer) use ($playerLogin) {
            $this->notifyPlayer($type, $buffer, $playerLogin);
        });
    }

    protected function notifyPlayer($type, $message, $playerLogin)
    {
        if (!$playerLogin) {
            return;
        }

        try {
            // TODO on long term replace this with a nice window.
            foreach (explode("/n", $message) as $messageLine) {
                if ($type == Process::ERR) {
                    $this->chatNotification->sendMessage($messageLine, $playerLogin);
                } else {
                    $this->chatNotification->sendMessage($messageLine, $playerLogin);
                }
            }
        } catch (\Exception $e) {
            // Ignore this is not main concern of the command. Probably connection was lost.
        }
    }

    protected function getPlayerLogin(InputInterface $input, SymfonyStyle $sfs)
    {
        if (!$input->hasArgument('login')) {
            return false;
        }

        try {
            $this->factory->createConnection(1);
            return $input->getArgument('login');
        } catch (\Exception $e) {
            $sfs->warning("Can't connect to dedicated server - Update status won't be shown ingame.");
        }

        return false;
    }
}
