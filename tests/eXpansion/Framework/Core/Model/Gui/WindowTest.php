<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 13:22
 */

namespace Tests\eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Action;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Frame;
use FML\Controls\Quad;
use Tests\eXpansion\Framework\Core\TestCore;

class WindowTest extends TestCore
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerGroup;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockTranslationHelper;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockWindowsFrameFactory;

    /** @var Window */
    protected $window;

    protected function setUp()
    {
        $this->mockPlayerGroup = $this->getMockBuilder(Group::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockTranslationHelper = $this->getMockBuilder(Translations::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockWindowsFrameFactory = $this->getMockBuilder(WindowFrameFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockWindowsFrameFactory->method('build')->willReturn(Quad::create());

        $factoryMock = $this->getMockBuilder(ManialinkFactoryInterface::class)->getMock();

        $this->window = new Window(
            $factoryMock,
            $this->mockPlayerGroup,
            $this->mockTranslationHelper,
            $this->mockWindowsFrameFactory,
            'test',
            10,
            20
        );
    }

    /**
     * Test that window is created properly with valid xml.
     */
    public function testWindow()
    {
        /** @var ManiaScriptFactory $factory */
        $action = new Action([$this, 'totoCallback'], []);

        $this->window->setCloseAction($action->getId());

        $this->assertInstanceOf(\SimpleXMLElement::class, simplexml_load_string($this->window->getXml()));
    }

    /**
     * Test different methods of the window.
     */
    public function testMethods()
    {
        $mockRenderable = $this->createMock(Frame::class);
        $mockFrame = Frame::create()->setAlign("center", "center2")
            ->setZ(1000)->setSize(10, 20);

        $this->window->addChild($mockRenderable);
        $this->assertEquals([$mockFrame, $mockRenderable], $this->window->getChildren());
        $this->window->addChild($mockRenderable);
        $this->assertEquals([$mockFrame, $mockRenderable], $this->window->getChildren());

        $this->window->removeAllChildren();
        $this->assertEmpty($this->window->getChildren());

        $this->window->addChildren([$mockRenderable]);
        $this->assertEquals([$mockRenderable], $this->window->getChildren());

        $this->window->removeChildren();
        $this->assertEmpty($this->window->getChildren());

        $format = $this->window->getFormat();
        $this->window->setFormat($format);
    }
}
