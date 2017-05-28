<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;
use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Types\Renderable;
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
     * @param LabelFactory      $labelFactory
     * @param string            $type
     */
    public function __construct(
        BackGroundFactory $backGroundFactory,
        LabelFactory $labelFactory,
        $type = LabelFactory::TYPE_NORMAL
    )
    {
        $this->backGroundFactory = $backGroundFactory;
        $this->labelFactory = $labelFactory;
        $this->type = $type;
    }

    /**
     * Create a multi column line.
     *
     * @param $totalWidth
     * @param $columns
     *
     * @return Frame
     */
    public function create($totalWidth, $columns, $index = 0)
    {
        $totalCoef
            = ($totalWidth - 1) / array_reduce($columns, function($carry, $item){return $carry + $item['width'];});

        $frame = new Frame();
        $frame->setHeight(5);
        $frame->setWidth($totalWidth);

        $postX = 1;
        foreach ($columns as $columnData)
        {
            if (isset($columnData['text'])) {
                $element = $this->createTextColumn($totalCoef, $columnData, $postX);
            } elseif (isset($columnData['renderer'])) {
                $element = $this->createRendererColumn($columnData, $postX);
            }

            if (isset($columnData['action'])) {
                $element->setAction($columnData['action']);
            }

            $frame->addChild($element);
            $postX += $columnData["width"] * $totalCoef;
        }

        $frame->addChild($this->backGroundFactory->create($totalWidth, 5, $index));
        return $frame;
    }

    /**
     * @param float $totalCoef
     * @param array $columnData
     * @param float $postX
     *
     * @return Label
     */
    protected function createTextColumn($totalCoef, $columnData, $postX)
    {
        $label = $this->labelFactory->create(
            $columnData['text'],
            AssociativeArray::getFromKey($columnData, 'translatable', false),
            $this->type
        );
        $label->setWidth(($columnData["width"] * $totalCoef) - 0.5);
        $label->setPosition($postX, -0.5);

        return $label;
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