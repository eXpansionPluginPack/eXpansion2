<?php

namespace Tests\eXpansion\Framework\Core;

use eXpansion\Framework\Core\DataProviders\ChatDataProvider;
use eXpansion\Framework\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Xmlrpc\FaultException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestCore extends KernelTestCase
{
    /** @var ContainerInterface */
    protected $container;

    /** @var  Application */
    protected $consoleApplication;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        // Loading this to force composer autoload to load all possible eXceptions.
        new FaultException('test');

        //start the symfony kernel
        $kernel = static::createKernel();
        $kernel->boot();

        $this->container = $kernel->getContainer();

        $this->consoleApplication = new Application($kernel);

        $dedicatedConnectionMock = $this->getMockBuilder('Maniaplanet\DedicatedServer\Connection')
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('expansion.framework.core.services.dedicated_connection', $dedicatedConnectionMock);

        $dedicatedConnectionMock = $this->getMockBuilder(Console::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('expansion.framework.core.services.console', $dedicatedConnectionMock);
    }

    protected function getMockPlayerStorage($player)
    {
        $playerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $playerStorage->method('getPlayerInfo')
            ->willReturn($player);

        return $playerStorage;
    }
}
