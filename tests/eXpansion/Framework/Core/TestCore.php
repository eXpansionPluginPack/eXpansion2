<?php

namespace Tests\eXpansion\Framework\Core;

use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatDataProvider;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\Core\Helpers\ChatOutput;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Xmlrpc\FaultException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestCore extends KernelTestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConnection;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConnectionFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConsole;

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

        $this->mockConnection = $this->getMockBuilder('Maniaplanet\DedicatedServer\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockConnectionFactory = $this->getMockBuilder(Factory::class)->disableOriginalConstructor()->getMock();
        $this->mockConnectionFactory->method('getConnection')->willReturn($this->mockConnection);

        $this->mockConsole = $this->getMockBuilder(Console::class)
            ->disableOriginalConstructor()
            ->getMock();

        $outputMock = $this->getMockBuilder(OutputInterface::class)->getMock();
        $this->mockConsole->method('getConsoleOutput')->willReturn($outputMock);

        $this->consoleApplication = new Application($kernel);
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
