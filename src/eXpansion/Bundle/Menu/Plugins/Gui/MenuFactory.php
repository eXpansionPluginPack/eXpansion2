<?php

namespace eXpansion\Bundle\Menu\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
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

    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        ManiaScriptFactory $menuScriptFactory,
        AdminGroups $adminGroupsHelper
    ){
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->menuScriptFactory = $menuScriptFactory;
        $this->adminGroupsHelper = $adminGroupsHelper;
    }


    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $label = new Label("open");
        $label->setText("Open")
            ->setAreaFocusColor("5ff")
            ->setAreaColor("3af")
            ->setPosition(100, 60)
            ->setScriptEvents(true);
        // $manialink->addChild($label); // disables open menu button from main view

        /* Button frame first */
        $btnFrame = new Frame("buttons");
        $btnFrame->setZ(101)->setPosition(0, 30);

        $y = 0;
        $baseLabel = new Label();
        $baseLabel->setPosition(0, $y)
            ->setAreaColor("0000")
            ->setAreaFocusColor("0000")
            ->setSize(48, 7)
            ->setTextColor("FFF");
        $baseLabel->setAlign("center", "center2")
            ->addClass("button")
            ->setScriptEvents(true);

        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 8)
            ->setText("Help")
            ->setDataAttributes(["do" => "!help"]);
        $btnFrame->addChild($label);

        $label = clone $baseLabel;
        $openSettingsId = $this->actionFactory->createManialinkAction($manialink, [$this, "showSettings"], []);
        $label->setPosition(0, $y -= 8)
            ->setText("Server Settings")
            ->setDataAttributes(["action" => $openSettingsId]);
        $btnFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 8)
            ->setText("Spectate")
            ->setDataAttributes(["action" => ""]);
        $btnFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setPosition(0, $y -= 8)
            ->setText("Exit server")
            ->setDataAttributes(["do" => "!exit"]);
        $btnFrame->addChild($label);


        $label = clone $baseLabel;
        $label->setPosition(0, $y - 12)
            ->setText("Back to game")
            ->setDataAttributes(["action" => ""]);
        $btnFrame->addChild($label);

        $bgFrame = Frame::create("background");
        $bgFrame->setZ(100);

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
        $bgFrame->addChild($label);

        $label = clone $baseLabel;
        $label->setText("Server Menu")
            ->setTextSize(8)
            ->setPosition(0, 35)
            ->setTextFont('Oswald');
        $bgFrame->addChild($label);


        $quad = new Quad();
        $quad->addClass("bg")
            ->setPosition(0, 28)
            ->setSize(100, 0.5)
            ->setAlign("center", "center")
            ->setBackgroundColor("fff");
        $bgFrame->addChild($quad);


        $quad = new Quad();
        $quad->addClass("bg")
            ->setId("mainBg")
            ->setPosition(0, 0)
            ->setSize(322, 182);
        $quad->setAlign("center", "center")
            ->setStyles("Bgs1", "BgDialogBlur");
        $bgFrame->addChild($quad);
        $manialink->addChild($btnFrame);

        $manialink->addChild($bgFrame);

        $manialink->getFmlManialink()->addChild($this->menuScriptFactory->createScript(["settingsId" => $openSettingsId]));
    }

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        // Do stuff Here.
    }

    public function showSettings($login)
    {
        echo "Show settings: ".$login."\n";
    }

}
