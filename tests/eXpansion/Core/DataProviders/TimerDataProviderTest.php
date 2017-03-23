<?php


namespace Tests\eXpansion\Core\DataProviders;


use eXpansion\Core\DataProviders\Listener\TimerDataListenerInterface;
use eXpansion\Core\DataProviders\TimerDataProvider;
use Tests\eXpansion\Core\TestCore;


class TimerDataProviderTest extends TestCore
{

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $pluginMock;

    /** @var TimerDataProvider */
    protected $timerDataProvider;

    protected function setUp()
    {
        parent::setUp();

        $this->pluginMock = $this->createMock(TimerDataListenerInterface::class);
        $this->timerDataProvider = $this->container->get('expansion.core.data_providers.timer_data_provider');
    }

    public function testOnPreLoopOnce()
    {
        $this->timerDataProvider->registerPlugin('test', $this->pluginMock);

        $this->pluginMock->expects($this->once())->method("onEverySecond");
        $this->timerDataProvider->onPreLoop();
    }

    public function testOnPreLoopNonce()
    {
        $this->timerDataProvider->onPreLoop();

        $this->timerDataProvider->registerPlugin('test', $this->pluginMock);
        $this->pluginMock->expects($this->never())->method("onEverySecond");
        $this->pluginMock->expects($this->once())->method("onPreLoop");
        $this->timerDataProvider->onPreLoop();
    }

    public function testOnPostLoopNonce()
    {
        $this->timerDataProvider->registerPlugin('test', $this->pluginMock);

        $this->pluginMock->expects($this->once())->method("onPostLoop");
        $this->timerDataProvider->onPostLoop();
    }
}
