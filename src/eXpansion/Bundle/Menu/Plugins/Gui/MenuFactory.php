<?php

namespace eXpansion\Bundle\Menu\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\uiAnimation;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;


/**
 * Class MenuFactory
 *
 * @package eXpansion\Bundle\Menu\Plugins\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class MenuFactory extends WidgetFactory
{
    /** @var  ManiaScriptFactory */
    protected $menuScriptFactory;

    /** @var  AdminGroups */
    protected $adminGroupsHelper;

    public $currentMenuView;


    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        ManiaScriptFactory $menuScriptFactory,
        AdminGroups $adminGroupsHelper
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->menuScriptFactory = $menuScriptFactory;
        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->currentMenuView = Frame::create();

    }


    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {

    }

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        // Do stuff Here.
        echo "update\n";

        $manialink->removeAllChildren();

        $button = $this->uiFactory->createButton("Menu", uiButton::TYPE_DECORATED);
        $button->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'createMenu'],
            [$manialink]));
        $manialink->addChild($button);
        $manialink->addChild($this->currentMenuView);
    }

    public function createMenu($login, $input, $args)
    {
        $frame = Frame::create();
        $manialink = $args[0];

        $anim = $this->uiFactory->createAnimation();
        $menuButtons = $this->uiFactory->createLayoutRow(0, 30);

        $btn = $this->uiFactory->createLabel("Test", uiLabel::TYPE_NORMAL);
        $btn->addClass('menuItem')
            ->setAlign("center", "top");
        $btn->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "hide"], [$manialink]));
        $menuButtons->addChild($btn);

        $delay = 0;
        foreach ($menuButtons->getChildren() as $btn) {
            $anim->addAnimation($btn, "", 300, $delay, uiAnimation::BackOut);
            $delay += 50;
        }

        $frame->addChild($menuButtons);
        $frame->addChild($this->createHeader("Server Menu"));

        $this->currentMenuView = $frame;

        $group = $this->groupFactory->createForPlayer($login);
        $this->update($group);

    }

    protected function createHeader($title = "Server Menu")
    {
        $headerFrame = Frame::create("background");
        $headerFrame->setZ(100);

        $baseLabel = new Label();
        $baseLabel->setAreaColor("0000")
            ->setAreaFocusColor("0000")
            ->setTextColor("FFF");
        $baseLabel->setAlign("center", "center2")
            ->addClass("bg");

        $label = clone $baseLabel;
        $label->setText("")
            ->setTextSize(16)
            ->setPosition(0, 50)
            ->setSize(32, 32);
        $headerFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setText($title)
            ->setTextSize(8)
            ->setPosition(0, 35)
            ->setTextFont('Oswald');
        $headerFrame->addChild($label);


        $quad = new Quad();
        $quad->addClass("bg")
            ->setPosition(0, 28)
            ->setSize(100, 0.5)
            ->setAlign("center", "center")
            ->setBackgroundColor("fff");
        $headerFrame->addChild($quad);

        $quad = new Quad();
        $quad->addClass("bg")
            ->setId("mainBg")
            ->setPosition(0, 0)
            ->setSize(322, 182);
        $quad->setAlign("center", "center")
            ->setStyles("Bgs1", "BgDialogBlur");
        $headerFrame->addChild($quad);

        return $headerFrame;
    }

    public function hide($manialink)
    {
        $this->hide($manialink);
    }


}
