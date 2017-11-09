<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Gui\Components\uiCheckbox;
use eXpansion\Framework\Gui\Components\uiInput;
use eXpansion\Framework\Gui\Components\uiTooltip;
use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Label;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class LineBuilder
 *
 * @package eXpansion\Framework\Core\Model\Gui\Builders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class LineFactory
{
    /** @var BackGroundFactory */
    protected $backGroundFactory;

    /** @var LabelFactory */
    protected $labelFactory;

    /** @var string */
    protected $type;

    /**
     * TitleLineFactory constructor.
     *
     * @param BackGroundFactory $backGroundFactory
     * @param LabelFactory $labelFactory
     * @param string $type
     */
    public function __construct(
        BackGroundFactory $backGroundFactory,
        LabelFactory $labelFactory,
        $type = LabelFactory::TYPE_NORMAL
    ) {
        $this->backGroundFactory = $backGroundFactory;
        $this->labelFactory = $labelFactory;
        $this->type = $type;
    }

    /**
     * Create a multi column line.
     *
     * @param float $totalWidth
     * @param array $columns
     * @param int $index
     * @param float $height
     * @param bool $autoNewLine
     * @param int $maxLines
     *
     * @return Frame
     *
     * @throws \Exception
     */
    public function create($totalWidth, $columns, $index = 0, $height = 5.0, $autoNewLine = false, $maxLines = 1)
    {
        $totalCoef
            = ($totalWidth - 1) / array_reduce($columns, function ($carry, $item) {
                return $carry + $item['width'];
            });

        $frame = new Frame();
        //$frame->setHeight($height);
        //$frame->setWidth($totalWidth);

        $postX = 1;
        foreach ($columns as $columnData) {
            if (isset($columnData['text'])) {
                $element = $this->createTextColumn($totalCoef, $columnData, $postX, $height, $autoNewLine, $maxLines);
            } elseif (isset($columnData['input'])) {
                $element = $this->createInputColumn($totalCoef, $columnData, $postX);
            } elseif (isset($columnData['renderer'])) {
                $element = $this->createRendererColumn($columnData, $postX);
            }

            if (!isset($element)) {
                throw new \Exception('Element not found.');
            }

            if (isset($columnData['action'])) {
                $element->setAction($columnData['action']);
            }

            $frame->addChild($element);
            $postX += $columnData["width"] * $totalCoef;
        }

        $frame->addChild($this->backGroundFactory->create($totalWidth, $height, $index));

        return $frame;
    }

    /**
     * @param float $totalCoef
     * @param array $columnData
     * @param float $postX
     * @param float $height
     * @param bool $autoNewLine
     * @param int $maxLines
     *
     * @return Label
     */
    protected function createTextColumn($totalCoef, $columnData, $postX, $height, $autoNewLine = false, $maxLines = 1)
    {
        $label = $this->labelFactory->create(
            $columnData['text'],
            AssociativeArray::getFromKey($columnData, 'translatable', false),
            $this->type
        );
        $label->setHeight($height - 1);
        $label->setWidth(($columnData["width"] * $totalCoef) - 0.5);
        $label->setPosition($postX, -0.5);
        $label->setAutoNewLine($autoNewLine);
        $label->setMaxLines($maxLines);

        return $label;
    }


    protected function createInputColumn($totalCoef, $columnData, $postX)
    {
        /** @var uiTooltip $tooltip */
        $tooltip = $columnData['tooltip'];
        $value = $columnData['input'];
        $i = $columnData['index'];
        $type = gettype($value);

        if ($type == "boolean") {
            $element = new uiCheckbox("", "entry_".$i."_boolean", true);
            if ($value === false) {
                $element = new uiCheckbox("", "entry_".$i."_boolean", false);
            }
            $element->setY(3);
        } else {
            $element = new uiInput("entry_".$i."_".$type);
            $element->setDefault($value);
        }
        $element->setPosition($postX, -0.5);
        $element->setWidth(($columnData["width"] * $totalCoef) - 0.5);
        $element->setHeight(4);

        $tooltip->addTooltip($element, $type);

        return $element;
    }


    /**
     * @param $columnData
     * @param $postX
     *
     * @return Control
     */
    protected function createRendererColumn($columnData, $postX)
    {
        /** @var Control $element */
        $element = $columnData['renderer'];
        $element->setPosition($postX, -0.5);
        $element->setHeight(4);

        return $element;
    }
}
