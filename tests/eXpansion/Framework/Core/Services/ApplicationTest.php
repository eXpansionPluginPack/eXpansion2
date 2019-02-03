<?php


namespace Tests\eXpansion\Framework\Core\Services;


use eXpansion\Framework\Core\Helpers\Version;
use eXpansion\Framework\Core\Services\Application;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Tests\eXpansion\Framework\Core\TestCore;


class ApplicationTest extends TestCore
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockDispatcher;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockVersionHelper;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockDispatcher = $this->createMock(Application\Dispatcher::class);
        $this->mockVersionHelper = $this->createMock(Version::class);
    }

    /**
     * Test that initialization runs as expected, by initializing the proper elements.
     */
    public function testInit()
    {
        $outPutMock = $this->createMock(ConsoleOutputInterface::class);

        $this->mockConsole->expects($this->atLeastOnce())->method('init')->withConsecutive([$outPutMock]);
        $this->mockConsole->expects($this->atLeastOnce())->method('writeln');

        $this->mockDispatcher->expects($this->once())->method('init');

        $application = new Application(
            $this->mockDispatcher,
            $this->mockConnectionFactory,
            $this->mockConsole,
            new NullLogger(),
            $this->mockVersionHelper
        );

        $this->assertEquals($application, $application->init($outPutMock));
    }

    /**
     * Test that during runtime the application dispatches the proper events.
     */
    public function testRun()
    {
        /** @var Application $application */
        $application = new Application(
            $this->mockDispatcher,
            $this->mockConnectionFactory,
            $this->mockConsole,
            new NullLogger(),
            $this->mockVersionHelper
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
