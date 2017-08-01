<?php


namespace Tests\eXpansion\Framework\Core\Services;


use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Services\Console;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Tests\eXpansion\Framework\Core\TestCore;


class ApplicationTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $dataProviderMock = $this->createMock(Application\Dispatcher::class);
        $this->container->set('expansion.service.dispatcher', $dataProviderMock);

        $consoleMock = $this->createMock(Console::class);
        $this->container->set('expansion.service.console', $consoleMock);
    }

    public function testInit()
    {
        $outPutMock = $this->createMock(ConsoleOutputInterface::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject $consoleMock */
        $consoleMock = $this->container->get('expansion.service.console');
        $consoleMock->expects($this->once())->method('init')->withConsecutive([$outPutMock]);
        $consoleMock->expects($this->atLeastOnce())->method('writeln');

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('expansion.service.dispatcher');
        $dataProviderMock->expects($this->once())->method('init');

        $application = $this->container->get('expansion.service.application');

        $this->assertEquals($application, $application->init($outPutMock));
    }

    public function testRun()
    {
        /** @var Application $application */
        $application = $this->container->get('expansion.service.application');
        // We need to stop the application so that it doesen't run indefinitively.
        $application->stopApplication();

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('expansion.service.dispatcher');
        $dataProviderMock->expects($this->exactly(4))
            ->method('dispatch')
            ->withConsecutive(
                [Application::EVENT_READY, []],
                [Application::EVENT_PRE_LOOP, []],
                ['test', ['data']],
                [Application::EVENT_POST_LOOP, []]
            );

        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.service.dedicated_connection');
        $connectionMock->expects($this->exactly(1))
            ->method('executeCallbacks')
            ->willReturn([['test', ['data']]]);

        $application->run();
    }
}
