<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class CustomSmSpeedWidget extends WidgetFactory
{

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
     * @inheritdoc
     */
    protected function createManialink(Group $group, $hideable = true)
    {
        return parent::createManialink($group, false);
    }

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $frame = Frame::create("Frame_Main");
        $frame->setScale(1);
        $manialink->addChild($frame);

        $lbl = $this->uiFactory->createLabel("100");
        $lbl->setPosition(2, 0)->setAlign("right", "center")
            ->setSize(20, 4)->setTextSize(3)->setTextFont("RajdhaniMono")->setId("Label_Speed");
        $frame->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("km/h");
        $lbl->setPosition(5, 0)->setAlign("center", "center")
            ->setSize(5, 4)->setTextSize(1);
        $frame->addChild($lbl);

        $rpm1 = Frame::create();
        $rpm1->setSize(21, 42)->setAlign("right", "center");
        $quad = Quad::create();
        $quad->setSize(21, 42)->setAlign("right", "center")->setId("Quad_RPM1");
        $quad->setImageUrl('file://Media/MEDIABROWSER_HiddenResources/Common/Images/Ingame/NewSpeed-gauge1.dds');
        $quad->setColorize("fff")->setRotation(-180);
        $rpm1->addChild($quad);
        $frame->addChild($rpm1);

        $rpm2 = Frame::create();
        $rpm2->setSize(21, 42)->setAlign("left", "center");
        $quad = Quad::create();
        $quad->setSize(21, 42)->setAlign("right", "center")->setId("Quad_RPM2");
        $quad->setImageUrl('file://Media/MEDIABROWSER_HiddenResources/Common/Images/Ingame/NewSpeed-gauge1.dds');
        $quad->setColorize("fff")->setRotation(0);
        $rpm2->addChild($quad);
        $frame->addChild($rpm2);


        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
            declare CMlFrame Frame = (Page.GetFirstChild("Frame_Main") as CMlFrame);
            declare CMlQuad RPM1 = (Page.GetFirstChild("Quad_RPM1") as CMlQuad);
            declare CMlQuad RPM2 = (Page.GetFirstChild("Quad_RPM2") as CMlQuad);
            declare CMlLabel Speed = (Page.GetFirstChild("Label_Speed") as CMlLabel);                       
            declare CMlLabel Countdown = (Page.GetFirstChild("Label_Countdown") as CMlLabel);
            
            declare netread Integer Net_LibUI_SettingsUpdate for Teams[0];
            declare netread Text[Text] Net_LibUI_Settings for Teams[0];
  
            declare PrevSettingsUpdate = -1;
            declare CutOffTimeLimit = -1;                         
                                           
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
            
           if (GUIPlayer != Null) {                    
                    Speed.Value = "" ^ MathLib::FloorInteger(GUIPlayer.Speed);
                    declare Real rpm = GUIPlayer.Speed / 100;
                    RPM1.RelativeRotation  = -180 + (MathLib::Clamp(rpm * 2. , 0., 1.) * 180);
                    RPM2.RelativeRotation  = (MathLib::Clamp((rpm -0.5)*2. ,0., 1.) * 180);               
           }
           
EOL
        );

    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
    }


}
