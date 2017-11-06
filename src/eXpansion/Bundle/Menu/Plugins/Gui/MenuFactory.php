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
        $manialink->removeAllChildren();
        $manialink->getFmlManialink()->getScript()->addScriptFunction("",
            <<<EOL
    
    Void onButtonOver(CMlControl Element) {
        Audio.PlaySoundEvent( CAudioManager::ELibSound::ScoreIncrease, 0, 0.);

        if (Element is CMlLabel) {
                declare El = (Element as CMlLabel);
        }
    
        if (Element.HasClass("noAnim") == False) {
            AnimMgr.Add(Element, "<elem scale=\"1.3\" />", 300, CAnimManager::EAnimManagerEasing::ElasticOut);
        }
    }   

    Void onButtonOut(CMlControl Element) {
        if (Element.HasClass("noAnim") == False) {
            AnimMgr.Add(Element, "<elem scale=\"1.0\" />", 300, CAnimManager::EAnimManagerEasing::ElasticOut);
		}
    }


***FML_OnInit***
***
	declare CMlFrame Exp_Window <=> (Page.GetFirstChild("Window") as CMlFrame);
    Exp_Window.ZIndex = 10000.;
***

***FML_Loop***
***


        // handle pending events
        foreach (Event in PendingEvents) {

            // mouse hover states
            if (Event.Type == CMlScriptEvent::Type::MouseOver && Event.Control.HasClass("menuItem")) {
                onButtonOver(Event.Control);
            }

            if (Event.Type == CMlScriptEvent::Type::MouseOut && Event.Control.HasClass("menuItem")) {
                onButtonOut(Event.Control);
            }
        }
***

EOL
        );


        $button = $this->uiFactory->createButton("Admin", uiButton::TYPE_DECORATED);
        $button->setPosition(-150, 70);
        $button->setScale(1);

        $button->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'createMenu'],
            [$manialink]));
        $manialink->addChild($button);
        $manialink->addChild($this->currentMenuView);
    }

    public function createMenu($login, $input, $args)
    {
        $manialink = $args[0];
        $anim = $this->uiFactory->createAnimation();


        $frame = Frame::create();

        $menuButtons = $this->uiFactory->createLayoutRow(0, 20);
        $menuButtons->setAlign("center", "top");


        $basebtn = $this->uiFactory->createLabel("", uiLabel::TYPE_TITLE);
        $basebtn->addClass('menuItem')
            ->setOpacity(0)
            ->setSize(60, 8)
            ->setScriptEvents(true)
            ->setAlign("center", "center");

        $btn = clone $basebtn;
        $btn->setText("");
        $btn->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "doHide"], [$manialink]));
        $menuButtons->addChild($btn);

        $btn = clone $basebtn;
        $btn->setText("Close menu");
        $btn->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "doHide"], [$manialink]));
        $menuButtons->addChild($btn);

        $delay = 0;
        foreach ($menuButtons->getChildren() as $btn) {
            $anim->addAnimation($btn, "opacity='1'", 500, $delay, "Linear");
            $delay += 50;
        }

        $frame->addChild($menuButtons);
        $frame->addChild($this->createHeader("Server Menu"));
        $frame->addChild($anim);

        $this->currentMenuView = $frame;
        $group = $this->groupFactory->createForPlayer($login);
        $this->update($group);

    }

    protected function createHeader($title = "Server Menu")
    {
        $anim = $this->uiFactory->createAnimation();

        $headerFrame = Frame::create("background");

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
            ->setBackgroundColor("fff")
            ->setOpacity(0);
        $headerFrame->addChild($quad);
        $anim->addAnimation($quad, "opacity='1'", 300, 0, "Linear");

        $quad = new Quad();
        $quad->addClass("bg")
            ->setId("mainBg")
            ->setPosition(0, 0)
            ->setSize(322, 182);
        $quad->setAlign("center", "center")
            ->setStyles("Bgs1", "BgDialogBlur")
            ->setOpacity(0);

        $anim->addAnimation($quad, "opacity='1'", 300, 0, "Linear");
        $headerFrame->addChild($quad);

        return $headerFrame;
    }

    public function doHide($login, $input, $args)
    {
        $manialink = $args[0];

        $this->currentMenuView = Frame::create();

        $group = $this->groupFactory->createForPlayer($login);
        $this->update($group);
    }


}
