<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\Factory\BackGroundFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\TitleBackGroundFactory;
use FML\Types\Renderable;
use Tests\eXpansion\Framework\Core\TestCore;

class TitleBackGroundFactoryTest extends TestCore
{
    public function testCreate()
    {
        /** @var TitleBackGroundFactory $factory */
        $factory = $this->container->get('expansion.framework.core.gui.element.factory.title_back_ground');

        $this->assertInstanceOf(Renderable::class, $factory->create(10,  4, 0));
    }
}
