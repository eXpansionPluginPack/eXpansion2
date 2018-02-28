<?php


namespace Tests\eXpansion\Framework\Core\Services;


use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Services\Console;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Tests\eXpansion\Framework\Core\TestCore;


class ApplicationTest extends TestCore
{
    protected $mockDispatcher;

    protected function setUp()
    {
        parent::setUp();

        $this->mockDispatcher = $this->createMock(Application\Dispatcher::class);
    }

    public function testInit()
    {
        $outPutMock = $this->createMock(ConsoleOutputInterface::class);

        $this->mockConsole->expects($this->once())->method('init')->withConsecutive([$outPutMock]);
        $this->mockConsole->expects($this->atLeastOnce())->method('writeln');

        $this->mockDispatcher->expects($this->once())->method('init');

        $application = new Application(
            $this->mockDispatcher,
            $this->mockConnectionFactory,
            $this->mockConsole,
            new NullLogger()
        );

        $this->assertEquals($application, $application->init($outPutMock));
    }

    public function testRun()
    {
        /** @var Application $application */
        $application = new Application(
            $this->mockDispatcher,
            $this->mockConnectionFactory,
            $this->mockConsole,
            new NullLogger()
        );

        // We need to stop the application so that it doesen't run indefinitively.
        $application->stopApplication();

        $this->mockDispatcher->expects($this->exactly(4))
            ->method('dispatch')
            ->withConsecutive(
                [Application::EVENT_READY, []],
                [Application::EVENT_PRE_LOOP, []],
                ['test', ['data']],
                [Application::EVENT_POST_LOOP, []]
            );

        $this->mockConnection->expects($this->exactly(1))
            ->method('executeCallbacks')
            ->willReturn([['test', ['data']]]);

        $application->run();
    }
}
