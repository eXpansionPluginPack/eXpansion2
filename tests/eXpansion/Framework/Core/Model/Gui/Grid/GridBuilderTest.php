<?php

namespace Tests\eXpansion\Framework\Core\Model\Gui\Grid;

use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionInterface;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use FML\Controls\Label;
use Tests\eXpansion\Framework\Core\SimpleTestCore;

class GridBuilderTest extends SimpleTestCore
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $dataCollection;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $manialink;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mlFactory;

    protected function setUp()
    {
        parent::setUp();

        $dispatcher = $this->getMockBuilder(DispatcherInterface::class)
            ->getMock();

        $this->dataCollection = $this->createMock(DataCollectionInterface::class);
        $this->manialink = $this->createMock(ManialinkInterface::class);
        $this->manialink->method('getUserGroup')->willReturn(
            new Group(null, $dispatcher)
        );
        $this->mlFactory = $this->createMock(ManialinkFactoryInterface::class);
    }


    public function testBuilder()
    {
        $this->dataCollection->expects($this->exactly(2))->method('getData')->with(1)->willReturn(
            [
                ['id' => 'Toto'],
                ['id' => 'Toto2'],
            ]
        );
        $this->dataCollection->expects($this->exactly(4))->method('getLineData')->willReturn('Test');

        $builder = $this->createBuilder();
        $builder->setDataCollection($this->dataCollection);
        $builder->setManialinkFactory($this->mlFactory);
        $builder->setManialink($this->manialink);

        $builder->addTextColumn('id', 'Test', 10);
        $builder->addActionColumn('id2', 'Test', 10, "test", Label::create());

        $builder->build(100, 100);
        $builder->build(100, 100);
    }

    public function testNextPage()
    {
        $this->dataCollection->method('getLastPageNumber')->willReturn(10);
        $this->dataCollection
            ->expects($this->exactly(1))
            ->method('getData')
            ->with(2)
            ->willReturn(
                [
                    ['id' => 'Toto'],
                    ['id' => 'Toto2'],
                ]
            );
        $this->dataCollection->expects($this->exactly(2))->method('getLineData')->willReturn('Test');

        $builder = $this->createBuilder();
        $builder->setDataCollection($this->dataCollection);
        $builder->setManialinkFactory($this->mlFactory);
        $builder->setManialink($this->manialink);

        $builder->addTextColumn('id', 'Test', 10);
        $builder->addActionColumn('id2', 'Test', 10, "test", Label::create());

        $builder->goToNextPage($this->manialink);
        $builder->build(100, 100);
    }

    public function testPreviousPage()
    {
        $this->dataCollection->method('getLastPageNumber')->willReturn(10);
        $this->dataCollection
            ->expects($this->exactly(2))
            ->method('getData')
            ->withConsecutive([2], [1])
            ->willReturn(
                [
                    ['id' => 'Toto'],
                    ['id' => 'Toto2'],
                ]
            );
        $this->dataCollection->expects($this->exactly(4))->method('getLineData')->willReturn('Test');

        $builder = $this->createBuilder();
        $builder->setDataCollection($this->dataCollection);
        $builder->setManialinkFactory($this->mlFactory);
        $builder->setManialink($this->manialink);

        $builder->addTextColumn('id', 'Test', 10);
        $builder->addActionColumn('id2', 'Test', 10, "test", Label::create());

        $builder->goToNextPage($this->manialink);
        $builder->build(100, 100);
        $builder->goToPreviousPage($this->manialink);
        $builder->build(100, 100);
    }

    public function testFirstPage()
    {
        $this->dataCollection->method('getLastPageNumber')->willReturn(10);
        $this->dataCollection
            ->expects($this->exactly(2))
            ->method('getData')
            ->withConsecutive([2], [1])
            ->willReturn(
                [
                    ['id' => 'Toto'],
                    ['id' => 'Toto2'],
                ]
            );
        $this->dataCollection->expects($this->exactly(4))->method('getLineData')->willReturn('Test');

        $builder = $this->createBuilder();
        $builder->setDataCollection($this->dataCollection);
        $builder->setManialinkFactory($this->mlFactory);
        $builder->setManialink($this->manialink);

        $builder->addTextColumn('id', 'Test', 10);
        $builder->addActionColumn('id2', 'Test', 10, "test", Label::create());

        $builder->goToNextPage($this->manialink);
        $builder->build(100, 100);
        $builder->goToFirstPage($this->manialink);
        $builder->build(100, 100);
    }


    public function testLastPage()
    {
        $this->dataCollection->method('getLastPageNumber')->willReturn(10);
        $this->dataCollection
            ->expects($this->exactly(1))
            ->method('getData')
            ->withConsecutive([10])
            ->willReturn(
                [
                    ['id' => 'Toto'],
                    ['id' => 'Toto2'],
                ]
            );
        $this->dataCollection->expects($this->exactly(2))->method('getLineData')->willReturn('Test');

        $builder = $this->createBuilder();
        $builder->setDataCollection($this->dataCollection);
        $builder->setManialinkFactory($this->mlFactory);
        $builder->setManialink($this->manialink);

        $builder->addTextColumn('id', 'Test', 10);
        $builder->addActionColumn('id2', 'Test', 10, "test", Label::create());

        $builder->goToLastPage($this->manialink);
        $builder->build(100, 100);

        $builder->resetColumns();
    }

    /**
     * @return GridBuilder
     */
    protected function createBuilder()
    {
        return $this->container->get(GridBuilderFactory::class)->create();
    }
}
