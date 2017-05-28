<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\Factory\LabelFactory;
use FML\Types\Renderable;
use Tests\eXpansion\Framework\Core\TestCore;

class LabelFactoryTest extends TestCore
{

    public function testCreate()
    {
        $factory = $this->getLabelFactory();

        $this->assertInstanceOf(Renderable::class, $factory->create('Yep'));
        $this->assertInstanceOf(Renderable::class, $factory->create('Yep', true));
        $this->assertInstanceOf(Renderable::class, $factory->create('Yep', true, LabelFactory::TYPE_TITLE));
    }

    /**
     * @return LabelFactory
     */
    protected function getLabelFactory()
    {
        return $this->container->get('expansion.framework.core.gui.element.factory.label');
    }

}
