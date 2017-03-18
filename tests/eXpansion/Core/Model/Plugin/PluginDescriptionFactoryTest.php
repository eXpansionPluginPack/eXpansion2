<?php


namespace Tests\eXpansion\Core\Model\Plugin;


use eXpansion\Core\Model\Plugin\PluginDescription;
use eXpansion\Core\Model\Plugin\PluginDescriptionFactory;
use Tests\eXpansion\Core\TestHelpers\ContainerDataTrait;


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
