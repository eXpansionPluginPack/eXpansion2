<?php

namespace eXpansion\Bundle\Chat\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;

class ChatWidgetFactory extends WidgetFactory
{

    /***
     * MenuFactory constructor.
     *
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param WidgetFactoryContext $context
     * @param VoteService $voteService
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
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);

        $row = $this->uiFactory->createLayoutRow(0, 0, [], -1);
        $manialink->addChild($row);

        $btn = $this->uiFactory->createLabel("  ");
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setScriptEvents(true)
            ->setAreaColor("000a")
            ->setAreaFocusColor("f90a")
            ->setId("ButtonPublic");

        $tooltip->addTooltip($btn, "Public Chat");
        $row->addChild($btn);


        $btn = $this->uiFactory->createLabel("  ");
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setScriptEvents(true)
            ->setAreaColor("000a")
            ->setAreaFocusColor("f90a")
            ->setId("ButtonPrivate");

        $tooltip->addTooltip($btn, "Private Messages");
        $row->addChild($btn);

        $btn = $this->uiFactory->createLabel("  ");
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setScriptEvents(true)
            ->setAreaColor("000a")
            ->setAreaFocusColor("f90a")
            ->setId("ButtonServer");

        $tooltip->addTooltip($btn, "Server Console");
        $row->addChild($btn);


        $manialink->addChild($btn);


    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
    }


}
