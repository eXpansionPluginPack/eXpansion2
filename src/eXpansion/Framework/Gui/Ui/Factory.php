<?php

namespace eXpansion\Framework\Gui\Ui;

use eXpansion\Framework\Core\Exceptions\UnknownMethodException;
use eXpansion\Framework\Core\Model\Gui\Factory\BackGroundFactory;
use eXpansion\Framework\Gui\Components\Animation;
use eXpansion\Framework\Gui\Components\Button;
use eXpansion\Framework\Gui\Components\Checkbox;
use eXpansion\Framework\Gui\Components\ConfirmButton;
use eXpansion\Framework\Gui\Components\Dropdown;
use eXpansion\Framework\Gui\Components\Input;
use eXpansion\Framework\Gui\Components\InputMasked;
use eXpansion\Framework\Gui\Components\Label;
use eXpansion\Framework\Gui\Components\line;
use eXpansion\Framework\Gui\Components\Textbox;
use eXpansion\Framework\Gui\Components\Tooltip;
use eXpansion\Framework\Gui\Layouts\LayoutLine;
use eXpansion\Framework\Gui\Layouts\LayoutRow;
use eXpansion\Framework\Gui\Layouts\LayoutScrollable;


/**
 * Class Factory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Gui\Ui
 *
 * @method Button createButton($text, $type = Button::TYPE_DEFAULT)
 * @method ConfirmButton createConfirmButton($text, $type = Button::TYPE_DEFAULT)
 * @method Checkbox createCheckbox($text, $name, $checked = false, $disabled = false)
 * @method Dropdown createDropdown($name, $options, $selectedIndex = -1, $isOpened = false)
 * @method Input createInput($name, $default = "", $width = 30)
 * @method InputMasked createInputMasked($name, $default = "", $width = 30)
 * @method Label createLabel($text = "", $type = Label::TYPE_NORMAL, $controlId = null)
 * @method Line createLine($x, $y)
 * @method Textbox createTextbox($name, $default = "", $lines = 1, $width = 30)
 * @method Tooltip createTooltip()
 * @method Animation createAnimation()
 * @method LayoutLine createLayoutLine($startX, $startY, $elements = [], $margin = 0.);
 * @method LayoutRow createLayoutRow($startX, $startY, $elements = [], $margin = 0.);
 * @method LayoutScrollable createLayoutScrollable($frame, $sizeX, $sizeY);
 *
 */
class Factory
{
    /**
     * @var string[]
     */
    protected $classes;

    /**
     * @var BackGroundFactory[]
     */
    protected $factories;

    /**
     * Factory constructor.
     *
     * @param $classes
     * @param $factories
     */
    public function __construct($classes, $factories = null)
    {
        $this->classes = $classes;
        $this->factories = $factories;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws UnknownMethodException
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'create') === 0) {
            $name = str_replace('create', '', $name);

            if (isset($this->classes[$name])) {
                $class = $this->classes[$name];

                return new $class(...$arguments);
            }

            if (isset($this->factories[$name])) {
                return $this->factories[$name]->create(...$arguments);
            }
        }

        throw new UnknownMethodException("$name is an unknown method");
    }
}
