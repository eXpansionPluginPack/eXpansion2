<?php


namespace Tests\eXpansion\Framework\Core\Model\Plugin;


use eXpansion\Framework\Core\Model\Plugin\PluginDescription;
use Tests\eXpansion\Framework\Core\TestHelpers\ContainerDataTrait;


class PluginDescriptionTest extends \PHPUnit_Framework_TestCase
{
    use ContainerDataTrait;

    public function testObject()
    {
        $pluginDescription = new PluginDescription('test-1');
        $this->CheckSimpleSettersGetters($pluginDescription, ['pluginId', 'childrens', 'parents']);

        $parents = ['p1', 'p2'];
        $c1 = new PluginDescription('c1');
        $c2 = new PluginDescription('c2');
        $childrens = [$c1, $c2];

        $pluginDescription->setParents($parents);
        $pluginDescription->addChildren($c1);
        $pluginDescription->addChildren($c2);

        $this->assertEquals('test-1', $pluginDescription->getPluginId());
        $this->assertEquals($parents, $pluginDescription->getParents());
        $this->assertEquals($childrens, $pluginDescription->getChildrens());
    }
}
