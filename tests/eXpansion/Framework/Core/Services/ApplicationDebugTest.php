<?php


namespace Tests\eXpansion\Framework\Core\Services;


use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Services\ApplicationDebug;
use eXpansion\Framework\Core\Services\Console;
use Tests\eXpansion\Framework\Core\TestCore;


class ApplicationDebugTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $dataProviderMock = $this->createMock(Application\Dispatcher::class);
        $this->container->set(Application\DispatchLogger::class, $dataProviderMock);

        $consoleMock = $this->createMock(Console::class);
        $this->container->set(Console::class, $consoleMock);
    }


    public function testRun()
    {
        /** @var Application $application */
        $application = new ApplicationDebug(
            $this->container->get(Application\DispatchLogger::class),
            $this->container->get('expansion.service.dedicated_connection'),
            $this->container->get(Console::class)
        );
        // We need to stop the application so that it doesen't run indefinitively.
        $application->stopApplication();

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get(Application\DispatchLogger::class);
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
