<?php

namespace eXpansion\Framework\Core\Command;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
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

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var Console */
    protected $console;

    /**
     * RunCommand constructor.
     */
    public function __construct(Factory $factory, ChatNotification $chatNotification, Console $console)
    {
        parent::__construct();

        $this->factory = $factory;
        $this->chatNotification = $chatNotification;
        $this->console = $console;
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
        $playerLogin = $this->getPlayerLogin($input, $sfs);

        $sfs->title("Updating Composer!");
        $this->notifyPlayer("", "Updating Composer!", $playerLogin, $sfs);
        $process = $this->runCommand("composer self-update", $playerLogin, $sfs);

        if ($process->isSuccessful()) {
            $this->notifyPlayer("", "Composer has been updated", $playerLogin);
            $sfs->success('Composer has been updated');
        } else {
            $this->notifyPlayer("", "Composer update has failed", $playerLogin);
            $sfs->success('Composer update has failed');

            return 1;
        }

        $sfs->title("Updating eXpansion!");
        $this->notifyPlayer("", "Updating eXpansion!", $playerLogin, $sfs);
        $this->runCommand("composer update --prefer-dist --prefer-stable --no-suggest -o", $playerLogin, $sfs);

        if ($process->isSuccessful()) {
            $this->notifyPlayer("", "eXpansion has been updated. Please restart eXpansion", $playerLogin);
            $sfs->success('eXpansion has been updated');
        } else {
            $this->notifyPlayer("", "eXpansion update has failed", $playerLogin);
            $sfs->success('eXpansion update has failed');

            return 1;
        }
    }

    protected function runCommand($command, $playerLogin, SymfonyStyle $sfs)
    {
        $process = new Process($command);
        $process->setWorkingDirectory($this->getContainer()->getParameter('kernel.root_dir') . '/..');

        $process->run(function ($type, $buffer) use ($playerLogin, $sfs) {
            $this->notifyPlayer($type, $buffer, $playerLogin);
            $sfs->writeln($buffer);
        });

        return $process;
    }

    protected function notifyPlayer($type, $message, $playerLogin)
    {
        if (!$playerLogin) {
            return;
        }

        try {
            // TODO on long term replace this with a nice window.
            foreach (explode("/n", $message) as $messageLine) {
                $this->chatNotification->sendMessage($messageLine, $playerLogin);
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
