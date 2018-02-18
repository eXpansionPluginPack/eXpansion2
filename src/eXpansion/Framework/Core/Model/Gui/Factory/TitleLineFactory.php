<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Gui\Components\Label;
use eXpansion\Framework\Gui\Components\Tooltip;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class LineBuilder
 *
 * @package eXpansion\Framework\Core\Model\Gui\Builders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class TitleLineFactory
{
    /** @var Factory */
    protected $uiFactory;

    /**
     * TitleLineFactory constructor.
     *
     * @param Factory $uiFactory
     */
    public function __construct(
        Factory $uiFactory
    ) {
        $this->uiFactory = $uiFactory;
    }

    /**
     * Create a multi column line.
     *
     * @param float $totalWidth
     * @param array $columns
     * @param int   $index
     * @param float $height
     * @param bool  $autoNewLine
     * @param int   $maxLines
     *
     * @return Frame
     *
     * @throws \Exception
     */
    public function create($totalWidth, $columns, $index = 0, $height = 5.0)
    {

        $tooltip = $this->uiFactory->createTooltip();

        $totalCoef
            = ($totalWidth - 1) / array_reduce($columns, function ($carry, $item) {
                return $carry + $item['width'];
            });

        $frame = Frame::create();
        $postX = 1;
        foreach ($columns as $columnData) {
            $action = null;
            if (isset($columnData['action'])) {
                $action = $columnData['action'];
            }

            if (isset($columnData['title'])) {
                $element = $this->createTitleColumn($totalCoef, $columnData, $postX, $height, $action, $tooltip);
            }

            if (!isset($element)) {
                throw new \Exception('Element not found.');
            }

            $frame->addChild($element);
            $postX += $columnData["width"] * $totalCoef;
        }

        $line = $this->uiFactory->createLine(0, -$height);
        $line->setLength($totalWidth)->setStroke(0.33)->setColor("fff");

        $frame->addChild($line);

        return $frame;
    }

    /**
     * @param float   $totalCoef
     * @param array   $columnData
     * @param float   $postX
     * @param float   $height
     * @param string  $action
     * @param Tooltip $tooltip
     * @return \eXpansion\Framework\Gui\Layouts\LayoutLine
     */
    protected function createTitleColumn($totalCoef, $columnData, $postX, $height, $action, Tooltip $tooltip)
    {
        $sort = AssociativeArray::getFromKey($columnData, 'sort', "");
        switch ($sort) {
            case "ASC":
                $sortType = " ";
                break;
            case "DESC":
                $sortType = " ";
                break;
            default:
                $sortType = "";
                break;
        }

        $sortLabel = $this->uiFactory->createLabel($sortType, Label::TYPE_HEADER);
        $sortLabel->setSize(3, $height - 1);

        $label = $this->uiFactory->createLabel($columnData['title'], Label::TYPE_HEADER);

        $translate = AssociativeArray::getFromKey($columnData, 'translatable', false);
        if ($translate) {
            $label->setTextId($columnData['title']);
            $label->setTranslate(true);
        }

        $label->setHeight($height - 1)
            ->setWidth(($columnData["width"] * $totalCoef) - 3.5)
            ->setPosition($postX, -0.5)
            ->setAutoNewLine(false)
            ->setMaxLines(1);

        if (!empty($action)) {
            $label->setAction($action);
            $tooltip->addTooltip($label, "click to sort");
        }

        return $this->uiFactory->createLayoutLine($postX, -0.5, [$label, $sortLabel]);
    }
}
