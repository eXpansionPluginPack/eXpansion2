<?php

namespace eXpansion\Bundle\Menu\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
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

    /**
     * @param ManiaScriptFactory $menuScriptFactory
     */
    public function setMenuScriptFactory($menuScriptFactory)
    {
        $this->menuScriptFactory = $menuScriptFactory;
    }

    /**
     * @param AdminGroups $adminGroupsHelper
     */
    public function setAdminGroupsHelper($adminGroupsHelper)
    {
        $this->adminGroupsHelper = $adminGroupsHelper;
    }

    /**
     * @param Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        /* Button frame first */

        $label = Label::create("open");
        $label->setText("Open")
            ->setAreaFocusColor("5ff")
            ->setAreaColor("3af")
            ->setPosition(100, 60)
            ->setScriptEvents(true);
       // $manialink->addChild($label);

        $btnFrame = Frame::create("buttons");
        $btnFrame->setZ(101)->setPosition(0, 30);

        $y = 0;
        $baseLabel = Label::create();
        $baseLabel->setAreaColor("0000")
            ->setAreaFocusColor("0000")
            ->setTextColor("FFF")
            ->setAlign("center", "center2")
            ->addClass("button")
            ->setScriptEvents(true);

        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 8)
            ->setText("Help")
            ->setTextPrefix(" ")
            ->setDataAttributes(["action" => "!help"]);
        $btnFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 10)
            ->setText("Show Profile")
            ->setTextPrefix(" ")
            ->setDataAttributes(["action" => "!profile"]);
        $btnFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 8)
            ->setText("Spectate")
            ->setTextPrefix("")
            ->setDataAttributes(["action" => "!spec"]);
        $btnFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 8)
            ->setText("Return to game")
            ->setTextPrefix(" ")
            ->setDataAttributes(["action" => "!close"]);
        $btnFrame->addChild($label);


        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 12)
            ->setText("Return to main menu")
            ->setTextPrefix("🏁 ")
            ->setDataAttributes(["action" => "!quit"]);
        $btnFrame->addChild($label);



        $bgFrame = Frame::create("background");
        $bgFrame->setZ(100);

        $baseLabel = Label::create();
        $baseLabel->setAreaColor("0000")
            ->setAreaFocusColor("0000")
            ->setTextColor("FFF")
            ->setAlign("center", "center2")
            ->addClass("bg");


        $label = clone $baseLabel;
        $label->setText("")
            ->setTextSize(16)
            ->setPosition(0, 50)
            ->setSize(32, 32);
        $bgFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setText("Server Menu")
            ->setTextSize(8)
            ->setPosition(0, 35)
            ->setTextFont('Oswald');
        $bgFrame->addChild($label);


        $quad = Quad::create();
        $quad->addClass("bg")
            ->setPosition(0, 28)
            ->setSize(100, 0.5)
            ->setAlign("center", "center")
            ->setBackgroundColor("fff");
        $bgFrame->addChild($quad);

        $quad = Quad::create();
        $quad->addClass("bg")
            ->setPosition(0, 0)
            ->setSize(322, 182)
            ->setAlign("center", "center")
            ->setStyles("Bgs1", "BgDialogBlur");
        $bgFrame->addChild($quad);



        $manialink->addChild($btnFrame);
        $manialink->addChild($bgFrame);

        $manialink->addChild($this->menuScriptFactory->createScript([]));
    }

    /**
     * @param Widget $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        // Do stuff Here.
    }

}
