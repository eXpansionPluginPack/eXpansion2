<?php

namespace eXpansion\Framework\Gui\Ui;

use eXpansion\Framework\Core\Model\Gui\Factory\BackGroundFactory;
use eXpansion\Framework\Gui\Components\uiAnimation;
use eXpansion\Framework\Gui\Components\UiButton;
use eXpansion\Framework\Gui\Components\uiCheckbox;
use eXpansion\Framework\Gui\Components\uiDropdown;
use eXpansion\Framework\Gui\Components\uiInput;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Components\uiLine;
use eXpansion\Framework\Gui\Components\uiTextbox;
use eXpansion\Framework\Gui\Components\uiTooltip;
use eXpansion\Framework\Gui\Layouts\layoutLine;
use eXpansion\Framework\Gui\Layouts\layoutRow;

use eXpansion\Framework\Core\Exceptions\UnknownMethodException;
use FML\Controls\Frame;

/**
 * Class Factory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Gui\Ui
 *
 * @method UiButton createButton($text, $type = UiButton::TYPE_DEFAULT)
 * @method uiCheckbox createCheckbox($text, $name, $checked = false, $disabled = false)
 * @method uiDropdown createDropdown($name, $options, $selectedIndex = -1, $isOpened = false)
 * @method uiInput createInput($name, $default = "", $width = 30)
 * @method uiLabel createLabel($text = "", $type = uiLabel::TYPE_NORMAL, $controlId = null)
 * @method uiLine createLine($x, $y)
 * @method uiTextbox createTextbox($name, $default = "", $lines = 1, $width = 30)
 * @method uiTooltip createTooltip()
 * @method uiAnimation createAnimation()
 *
 * @method layoutLine createLayoutLine($startX, $startY, $elements = [], $margin = 0.);
 * @method layoutRow createLayoutRow($startX, $startY, $elements = [], $margin = 0.);
 *
 * @method Frame createBackground($width, $height, $index = 0)
 * @method Frame createTitleBackground($width, $height, $index = 0)
 * @method Frame createPager($width, $currentPageNumber, $lastPageNumber, $actionFirstPage, $actionPreviousPage, $actionNextPage, $actionLastPage)
 * @method Frame createGridLine($totalWidth, $columns, $index = 0, $height = 5.0, $autoNewLine = false, $maxLines = 1)
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
    public function __construct($classes, $factories)
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
