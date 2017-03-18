<?php


namespace Tests\eXpansion\Core\Services;


use eXpansion\Core\Services\Application;
use eXpansion\Core\Services\Console;
use eXpansion\Core\Services\DataProviderManager;
use eXpansion\Core\Services\PluginManager;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Tests\eXpansion\Core\TestCore;


class ApplicationTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $pluginManagerMock = $this->createMock(PluginManager::class);
        $this->container->set('expansion.core.services.plugin_manager', $pluginManagerMock);

        $dataProviderMock = $this->createMock(DataProviderManager::class);
        $this->container->set('expansion.core.services.data_provider_manager', $dataProviderMock);

        $consoleMock = $this->createMock(Console::class);
        $this->container->set('expansion.core.services.console', $consoleMock);
    }

    public function testInit()
    {
        $outPutMock = $this->createMock(ConsoleOutputInterface::class);

        /** @var \PHPUnit_Framework_MockObject_MockObject $consoleMock */
        $consoleMock = $this->container->get('expansion.core.services.console');
        $consoleMock->expects($this->once())->method('init')->withConsecutive([$outPutMock]);
        $consoleMock->expects($this->atLeastOnce())->method('writeln');

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('expansion.core.services.data_provider_manager');
        $dataProviderMock->expects($this->once())->method('init');

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $pluginManagerMock = $this->container->get('expansion.core.services.plugin_manager');
        $pluginManagerMock->expects($this->once())->method('init');

        $this->container->get('expansion.core.services.application')->init($outPutMock);
    }

    public function testRun()
    {
        /** @var Application $application */
        $application = $this->container->get('expansion.core.services.application');
        // We need to stop the application so that it doesen't run indefinitively.
        $application->stopApplication();

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('expansion.core.services.data_provider_manager');
        $dataProviderMock->expects($this->exactly(4))
            ->method('dispatch')
            ->withConsecutive(
                [Application::EVENT_RUN, []],
                [Application::EVENT_PRE_LOOP, []],
                ['test', ['data']],
                [Application::EVENT_POST_LOOP, []]
            );

        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.core.services.dedicated_connection');
        $connectionMock->expects($this->exactly(1))
            ->method('executeCallbacks')
            ->willReturn([['test', ['data']]]);

        $application->run();
    }
}
