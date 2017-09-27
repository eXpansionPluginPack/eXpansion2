<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\Factory\BackGroundFactory;
use FML\Types\Renderable;
use Tests\eXpansion\Framework\Core\TestCore;

class BackGroundFactoryTest extends TestCore
{
    public function testCreate()
    {
        /** @var BackGroundFactory $factory */
        $factory = $this->container->get(BackGroundFactory::class);

        $this->assertInstanceOf(Renderable::class, $factory->create(10,  4, 0));
        $this->assertInstanceOf(Renderable::class, $factory->create(10,  4, 1));
    }
}
