<?php
/**
 * File Test.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\Gui\Ui;

use eXpansion\Framework\Core\Exceptions\UnknownMethodException;
use eXpansion\Framework\Gui\Components\Button;
use eXpansion\Framework\Gui\Components\Label;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Types\Renderable;
use Tests\eXpansion\Framework\Core\TestCore;

class FactoryTest extends TestCore
{
    /** @var  Factory */
    protected $factory;

    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->container->get('expansion.gui.component.factory');
    }


    public function testComponents()
    {
        $tests = [
            ['m' => 'createButton', 'p' => ['text']],
            ['m' => 'createButton', 'p' => ['text', Button::TYPE_DECORATED]],
            ['m' => 'createCheckbox', 'p' => ['text', 'YOYO']],
            ['m' => 'createCheckbox', 'p' => ['text', 'YOYO', true]],
            ['m' => 'createDropdown', 'p' => ['text', ['YOYO']]],
            ['m' => 'createDropdown', 'p' => ['text', ['YOYO'], 0]],
            ['m' => 'createDropdown', 'p' => ['text', ['YOYO'], 0, true]],
            ['m' => 'createInput', 'p' => ['text']],
            ['m' => 'createInput', 'p' => ['text', 'yoyo']],
            ['m' => 'createLabel', 'p' => ['text']],
            ['m' => 'createLabel', 'p' => ['text', Label::TYPE_HEADER]],
            ['m' => 'createLabel', 'p' => ['text', Label::TYPE_NORMAL]],
            ['m' => 'createLabel', 'p' => ['text', Label::TYPE_TITLE]],
            ['m' => 'createLine', 'p' => [10, 10]],
            ['m' => 'createTextbox', 'p' => ['text']],
            ['m' => 'createTextbox', 'p' => ['text', 'yoyo']],
            ['m' => 'createTextbox', 'p' => ['text', 'yoyo', 50]],
            ['m' => 'createTextbox', 'p' => ['text', 'yoyo', 50, 10]],
        ];

        foreach ($tests as $test) {
            $this->methodTest($test['m'], $test['p']);
        }
    }

    public function testWrongMethod()
    {
        $this->expectException(UnknownMethodException::class);
        $this->factory->createToto();
    }

    public function testWrongMethod2()
    {
        $this->expectException(UnknownMethodException::class);
        $this->factory->toto();
    }

    protected function methodTest($method, $arguments)
    {
        /** @var Renderable $element */
        $element = $this->factory->$method(...$arguments);
        $doc = new \DOMDocument();

        $this->assertInstanceOf(Renderable::class, $element);
        $this->assertInstanceOf(\DOMElement::class, $element->render($doc));

        if ($element instanceof ScriptFeature) {
            $element->prepare(new Script());
        }
    }
}
