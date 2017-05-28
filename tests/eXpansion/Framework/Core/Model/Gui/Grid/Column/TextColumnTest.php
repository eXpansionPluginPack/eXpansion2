<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Grid\Column;

use eXpansion\Framework\Core\Model\Gui\Grid\Column\ActionColumn;
use eXpansion\Framework\Core\Model\Gui\Grid\Column\TextColumn;
use FML\Controls\Label;

class TextColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testActionColumn()
    {
        $column = new TextColumn('id', 'test', 5,false, true);
        $this->assertEquals(false, $column->getSortable());
        $this->assertEquals(true, $column->getTranslatable());


        $column->setSortable(true);
        $column->setTranslatable(false);

        $this->assertEquals(true, $column->getSortable());
        $this->assertEquals(false, $column->getTranslatable());
    }
}
