<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\Action;
use eXpansion\Framework\Core\Model\Gui\Factory\LineFactory;
use FML\Controls\Label;
use FML\Types\Renderable;
use Tests\eXpansion\Framework\Core\TestCore;

class LineFactoryTest extends TestCore
{

    public function testCreate()
    {
        $factory = $this->getLineFactory();

        $columns = [
            [
                'renderer' => Label::create(),
                'width' => 20,
            ],[
                'text' => 'Toto',
                'width' => 30,
                'action' => 'string',
            ],
        ];
        $this->assertInstanceOf(Renderable::class, $factory->create(100, $columns, 0));
    }

    /**
     * @return LineFactory
     */
    protected function getLineFactory()
    {
        return $this->container->get(LineFactory::class);
    }

}
