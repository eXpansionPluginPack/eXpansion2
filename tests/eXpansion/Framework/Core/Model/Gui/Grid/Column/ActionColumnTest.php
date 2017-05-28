<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Grid\Column;

use eXpansion\Framework\Core\Model\Gui\Grid\Column\ActionColumn;
use FML\Controls\Label;

class ActionColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testActionColumn()
    {
        $label = Label::create();
        $label2 = Label::create();

        $column = new ActionColumn('id', 'test', 5, [$this, 'test'], $label);
        $this->assertEquals('id', $column->getKey());
        $this->assertEquals('test', $column->getName());
        $this->assertEquals(5, $column->getWidthCoeficiency());
        $this->assertEquals([$this, 'test'], $column->getCallable());
        $this->assertEquals($label, $column->getRenderer());

        $column->setKey('id2');
        $column->setName('test2');
        $column->setWidthCoeficiency(6);
        $column->setCallable([$this, 'test2']);
        $column->setRenderer($label2);

        $this->assertEquals('id2', $column->getKey());
        $this->assertEquals('test2', $column->getName());
        $this->assertEquals(6, $column->getWidthCoeficiency());
        $this->assertEquals([$this, 'test2'], $column->getCallable());
        $this->assertEquals($label2, $column->getRenderer());

    }

}
