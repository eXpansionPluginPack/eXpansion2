<?php


namespace Tests\eXpansion\Core\Command;


use eXpansion\Core\Command\DediEventsTestCommand;
use eXpansion\Core\Command\RunCommand;
use eXpansion\Core\Services\Application\RunInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\eXpansion\Core\TestCore;


class RunCommandTest extends TestCore
{
    public function testRun()
    {
        $applicationMock = $this->createMock(RunInterface::class);
        $this->container->set('expansion.core.services.application', $applicationMock);

        $applicationMock
            ->expects($this->once())->method('init')
            ->with($this->isInstanceOf(OutputInterface::class))
            ->willReturn($applicationMock);
        $applicationMock->expects($this->once())->method('run');

        $command = new RunCommand();
        $command->setContainer($this->container);
        $this->consoleApplication->add($command);

        $tester = new CommandTester($command);
        $tester->execute(
            array('command' => $command->getName())
        );
    }

    public function testDebugRun()
    {
        $applicationMock = $this->createMock(RunInterface::class);
        $this->container->set('expansion.core.services.application_debug', $applicationMock);

        $applicationMock
            ->expects($this->once())->method('init')
            ->with($this->isInstanceOf(OutputInterface::class))
            ->willReturn($applicationMock);
        $applicationMock->expects($this->once())->method('run');

        $command = new DediEventsTestCommand();
        $command->setContainer($this->container);
        $this->consoleApplication->add($command);

        $tester = new CommandTester($command);
        $tester->execute(
            array('command' => $command->getName())
        );
    }
}
