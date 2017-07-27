<?php


namespace Tests\eXpansion\Framework\Core\Services;


use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Services\Console;
use Tests\eXpansion\Framework\Core\TestCore;


class ApplicationDebugTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $dataProviderMock = $this->createMock(Application\Dispatcher::class);
        $this->container->set('expansion.service.application.dispatch_logger', $dataProviderMock);

        $consoleMock = $this->createMock(Console::class);
        $this->container->set('expansion.service.console', $consoleMock);
    }


    public function testRun()
    {
        /** @var Application $application */
        $application = $this->container->get('expansion.service.application_debug');
        // We need to stop the application so that it doesen't run indefinitively.
        $application->stopApplication();

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('expansion.service.application.dispatch_logger');
        $dataProviderMock->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [Application::EVENT_READY, []],
                ['test', ['data']]
            );

        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.service.dedicated_connection');
        $connectionMock->expects($this->exactly(1))
            ->method('executeCallbacks')
            ->willReturn([['test', ['data']]]);

        $application->run();
    }
}
