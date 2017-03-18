<?php

namespace Tests\eXpansion\Core;

use eXpansion\Core\DataProviders\ChatDataProvider;
use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Core\Storage\Data\Player;
use eXpansion\Core\Storage\PlayerStorage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestCore extends KernelTestCase
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        //start the symfony kernel
        $kernel = static::createKernel();
        $kernel->boot();

        $this->container = $kernel->getContainer();

        $dedicatedConnectionMock = $this->getMockBuilder('Maniaplanet\DedicatedServer\Connection')
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('expansion.core.services.dedicated_connection', $dedicatedConnectionMock);
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
