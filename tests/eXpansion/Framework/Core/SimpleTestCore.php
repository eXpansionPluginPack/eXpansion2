<?php

namespace Tests\eXpansion\Framework\Core;

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

class SimpleTestCore extends KernelTestCase
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

    /**
     * @return ChatOutput|object
     */
    protected function getChatOutputHelper()
    {
        return $this->container->get(ChatOutput::class);
    }
}
