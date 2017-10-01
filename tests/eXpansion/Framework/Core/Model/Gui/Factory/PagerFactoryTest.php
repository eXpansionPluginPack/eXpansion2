<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\Action;
use eXpansion\Framework\Core\Model\Gui\Factory\LineFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\PagerFactory;
use FML\Controls\Label;
use FML\Types\Renderable;
use Tests\eXpansion\Framework\Core\TestCore;

class PagerFactoryTest extends TestCore
{

    public function testCreate()
    {
        $factory = $this->getPagerFactory();


        $this->assertInstanceOf(
            Renderable::class,
            $factory->create(
                100,
                1,
                1,
                '',
                '',
                '',
                ''
            )
        );
        $this->assertInstanceOf(
            Renderable::class,
            $factory->create(
                100,
                10,
                100,
                '',
                '',
                '',
                ''
            )
        );
    }

    /**
     * @return PagerFactory
     */
    protected function getPagerFactory()
    {
        return $this->container->get(PagerFactory::class);
    }

}
