<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Ui\Factory as uiFactory;
use FML\Controls\Frame;
use FML\Controls\Quad;

/**
 * Class PagerFactory
 *
 * @package eXpansion\Framework\Core\Model\Gui\Factory;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class PagerFactory
{
    /** @var uiFactory */
    protected $uiFactory;

    /**
     * PagerFactory constructor.
     * @param uiFactory $uiFactory
     */
    public function __construct(uiFactory $uiFactory)
    {
        $this->uiFactory = $uiFactory;
    }

    /**
     * @param $width
     * @param $currentPageNumber
     * @param $lastPageNumber
     * @param $actionFirstPage
     * @param $actionPreviousPage
     * @param $actionNextPage
     * @param $actionLastPage
     *
     * @return Frame
     */
    public function create(
        $width,
        $currentPageNumber,
        $lastPageNumber,
        $actionFirstPage,
        $actionPreviousPage,
        $actionNextPage,
        $actionLastPage,
        $actionGotoPage
    ) {

        $frame = Frame::Create();
        $frame->setSize($width, 16);

        $pagerLine = $this->uiFactory->createLayoutLine(0, -1);
        $buttonSize = 6;

        $empty = Quad::create();
        $empty->setOpacity(0)->setSize($buttonSize, $buttonSize);

        // Show previous buttons
        if ($currentPageNumber > 2) {
            $button = $this->uiFactory->createButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setAction($actionFirstPage);
        } else {
            $button = clone $empty;
        }


        $pagerLine->addChild($button);

        if ($currentPageNumber > 1) {
            $button = $this->uiFactory->createButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setAction($actionPreviousPage);

        } else {
            $button = clone $empty;
        }
        $pagerLine->addChild($button);


        // Show current page.

        $input = $this->uiFactory->createInput('pager_gotopage', $currentPageNumber, 7);
        $input->setAction($actionGotoPage)->setPosition(0, 1)->setHorizontalAlign("left");


        $label = $this->uiFactory->createLabel(" / $lastPageNumber");
        $label->setSize(10, 7)->setHorizontalAlign("left");

        $line = $this->uiFactory->createLayoutLine(0, -2);
        $line->setHorizontalAlign("left");
        $line->addChildren([$input, $label]);

        if ($lastPageNumber > 1) {
            $pagerLine->addChild($line);
        } else {
            $label->setText("1 / 1");
            $pagerLine->addChild($label);
        }

        if ($currentPageNumber < $lastPageNumber) {
            $button = $this->uiFactory->createButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setAction($actionNextPage);

        } else {
            $button = clone $empty;
        }
        $pagerLine->addChild($button);

        if ($currentPageNumber < $lastPageNumber - 1) {
            $button = $this->uiFactory->createButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setAction($actionLastPage);

        } else {
            $button = clone $empty;
        }
        $pagerLine->addChild($button);

        $pagerLine->setAlign("center", "top");
        $pagerLine->setX(($width - $pagerLine->getWidth()) / 2);


        $borderTop = $this->uiFactory->createLine(0, 0);
        $borderTop->setLength($width)->setColor("fff");

        $frame->addChild($borderTop);
        $frame->addChild($pagerLine);

        return $frame;
    }
}
