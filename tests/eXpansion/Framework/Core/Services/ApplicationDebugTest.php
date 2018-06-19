<?php


namespace Tests\eXpansion\Framework\Core\Services;


use eXpansion\Framework\Core\Helpers\Version;
use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Services\ApplicationDebug;
use eXpansion\Framework\Core\Services\Console;
use Psr\Log\NullLogger;
use Tests\eXpansion\Framework\Core\TestCore;


class ApplicationDebugTest extends TestCore
{
    protected $mockDataProvider;
    protected $mockVersionHelper;

    protected function setUp()
    {
        parent::setUp();

        $this->mockVersionHelper = $this->createMock(Version::class);
        $this->mockDataProvider = $this->createMock(Application\Dispatcher::class);
    }


    public function testRun()
    {
        /** @var Application $application */
        $application = new ApplicationDebug(
            $this->mockDataProvider,
            $this->mockConnectionFactory,
            $this->mockConsole,
            new NullLogger(),
            $this->mockVersionHelper
        );
        // We need to stop the application so that it doesen't run indefinitively.
        $application->stopApplication();

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $this->mockDataProvider->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [Application::EVENT_READY, []],
                ['test', ['data']]
            );

        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $this->mockConnection->expects($this->exactly(1))
            ->method('executeCallbacks')
            ->willReturn([['test', ['data']]]);

        $application->run();
    }
}
