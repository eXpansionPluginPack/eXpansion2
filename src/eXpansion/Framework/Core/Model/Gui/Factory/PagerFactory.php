<?php
namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Gui\Components\uiButton;
use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Quads\Quad_Icons64x64_1;

/**
 * Class PagerFactory
 *
 * @package eXpansion\Framework\Core\Model\Gui\Factory;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class PagerFactory
{
    /** @var LabelFactory */
    protected $labelFactory;

    /**
     * PagerFactory constructor.
     *
     * @param LabelFactory $labelFactory
     */
    public function __construct(LabelFactory $labelFactory)
    {
        $this->labelFactory = $labelFactory;
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
        $actionLastPage
    ) {
        $frame = new Frame();
        $frame->setSize($width, 7);

        $buttonSize = 7;

        // Show previous buttons
        if ($currentPageNumber > 2) {
            $button = new uiButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setPosition(1, 0)
                ->setAction($actionFirstPage);
            $frame->addChild($button);
        }
        if ($currentPageNumber > 1) {
            $button = new uiButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setPosition(2 + $buttonSize, 0)
                ->setAction($actionPreviousPage);
            $frame->addChild($button);
        }

        // Show current page.
        $label = $this->labelFactory->create("$currentPageNumber / $lastPageNumber");
        $label->setSize(10, 7);
        $label->setHorizontalAlign(Control::CENTER);
        $label->setPosition($frame->getWidth() / 2, 0);
        $frame->addChild($label);

        if ($currentPageNumber < $lastPageNumber) {
            $button = new uiButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setPosition($frame->getWidth() - 1 - (2 * $buttonSize), 0)
                ->setAction($actionNextPage);
            $frame->addChild($button);
        }
        if ($currentPageNumber < $lastPageNumber - 1) {
            $button = new uiButton("", uiButton::TYPE_DECORATED);
            $button
                ->setSize($buttonSize, $buttonSize)
                ->setPosition($frame->getWidth() - 1 - $buttonSize, 0)
                ->setAction($actionLastPage);
            $frame->addChild($button);
        }

        return $frame;
    }
}
