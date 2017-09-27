<?php

namespace Tests\eXpansion\Framework\Core\Command;

use eXpansion\Framework\Core\Command\DediEventsTestCommand;
use eXpansion\Framework\Core\Command\RunCommand;
use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Services\Application\RunInterface;
use eXpansion\Framework\Core\Services\ApplicationDebug;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\eXpansion\Framework\Core\TestCore;

class RunCommandTest extends TestCore
{
    public function testRun()
    {
        $applicationMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $applicationMock
            ->expects($this->once())->method('init')
            ->with($this->isInstanceOf(OutputInterface::class))
            ->willReturn($applicationMock);
        $applicationMock->expects($this->once())->method('run');

        $command = new RunCommand($applicationMock);
        $command->setContainer($this->container);
        $this->consoleApplication->add($command);

        $tester = new CommandTester($command);
        $tester->execute(
            array('command' => $command->getName())
        );
    }

    public function testDebugRun()
    {
        $applicationMock = $this->getMockBuilder(ApplicationDebug::class)
            ->disableOriginalConstructor()
            ->getMock();

        $applicationMock
            ->expects($this->once())->method('init')
            ->with($this->isInstanceOf(OutputInterface::class))
            ->willReturn($applicationMock);
        $applicationMock->expects($this->once())->method('run');

        $command = new DediEventsTestCommand($applicationMock);
        $command->setContainer($this->container);
        $this->consoleApplication->add($command);

        $tester = new CommandTester($command);
        $tester->execute(
            array('command' => $command->getName())
        );
    }
}
