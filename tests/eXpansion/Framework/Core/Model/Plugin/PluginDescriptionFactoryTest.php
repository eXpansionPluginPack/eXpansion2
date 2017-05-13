<?php


namespace Tests\eXpansion\Framework\Core\Model\Plugin;


use eXpansion\Framework\Core\Model\Plugin\PluginDescription;
use eXpansion\Framework\Core\Model\Plugin\PluginDescriptionFactory;
use Tests\eXpansion\Framework\Core\TestHelpers\ContainerDataTrait;


class PluginDescriptionFactoryTest extends \PHPUnit_Framework_TestCase
{
    use ContainerDataTrait;

    public function testFactory()
    {
        $pluginFactory = new PluginDescriptionFactory(PluginDescription::class);
        $plugin = $pluginFactory->create('test-1');

        $this->assertInstanceOf(PluginDescription::class, $plugin);
        $this->assertEquals('test-1', $plugin->getPluginId());
    }
}
