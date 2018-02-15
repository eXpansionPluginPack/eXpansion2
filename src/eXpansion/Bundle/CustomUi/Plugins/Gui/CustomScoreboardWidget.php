<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetLabel;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;

class CustomScoreboardWidget extends WidgetFactory
{

    /**
     * ChatHelperWidget constructor.
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param                      $posX
     * @param                      $posY
     * @param WidgetFactoryContext $context
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
    }


    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {


        parent::createContent($manialink);

        $manialink->getFmlManialink()->setLayer("normal");


        $frame = Frame::create()->setPosition(-100, 50);
        $frame->addChildren([
            $this->uiFactory->createLabel("Live Rankings", uiLabel::TYPE_TITLE),
            $this->uiFactory->createLine(0, -3)->setLength(40)->setStroke(0.33)->setColor("fff"),
        ]);
        $manialink->addChild($frame);

        $align = ["left", "center", "right"];

        $column = $this->uiFactory->createLayoutRow(0, 0, [], 3);

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                for ($k = 0; $k < 3; $k++) {
                    $line = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);

                    $test1 = new WidgetLabel($align[$i]);
                    $test1->setAlign($align[$i], "center2")->setSize(mt_rand(6, 12), 3);

                    $test2 = new WidgetLabel($align[$j]);
                    $test2->setAlign($align[$j], "center2")->setSize(mt_rand(6, 12), 3);

                    $test3 = new WidgetLabel($align[$k]);
                    $test3->setAlign($align[$k], "center2")->setSize(mt_rand(6, 12), 3);

                    $line->addChildren([
                        $test1,
                        $test2,
                        $test3,
                    ]);

                    $column->addChild($line);
                }
            }
        }
        $manialink->addChild($column);

    }


}
