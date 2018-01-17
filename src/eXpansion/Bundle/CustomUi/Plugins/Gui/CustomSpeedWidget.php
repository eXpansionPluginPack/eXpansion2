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

class CustomSpeedWidget extends WidgetFactory
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

        $lbl = $this->uiFactory->createLabel("");
        $lbl->setPosition(0, 6)->setAlign("center", "center")
            ->setSize(20, 4)->setTextSize(5)->setTextFont("RajdhaniMono")->setId("Label_Countdown");
        $frame->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("100");
        $lbl->setPosition(2, 0)->setAlign("right", "center")
            ->setSize(20, 4)->setTextSize(3)->setTextFont("RajdhaniMono")->setId("Label_Speed");
        $frame->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("km/h");
        $lbl->setPosition(5, 0)->setAlign("center", "center")
            ->setSize(5, 4)->setTextSize(1);
        $frame->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("1");
        $lbl->setPosition(-6, -8)->setAlign("right", "center")
            ->setSize(20, 4)->setTextSize(1)->setTextFont("RajdhaniMono")->setId("Label_Gear");
        $frame->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("gear");
        $lbl->setPosition(-7, -5)->setAlign("center", "center")
            ->setSize(5, 4)->setTextSize(1);
        $frame->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("0");
        $lbl->setPosition(10, -8)->setAlign("right", "center")
            ->setSize(20, 5)->setTextSize(1)->setTextFont("RajdhaniMono")->setId("Label_Distance");
        $frame->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("distance");
        $lbl->setPosition(5, -5)->setAlign("center", "center")
            ->setSize(15, 4)->setTextSize(1);
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
            declare CMlLabel Distance = (Page.GetFirstChild("Label_Distance") as CMlLabel);
            declare CMlLabel Gear = (Page.GetFirstChild("Label_Gear") as CMlLabel);
            declare CMlLabel Countdown = (Page.GetFirstChild("Label_Countdown") as CMlLabel);
            
            declare netread Integer Net_LibUI_SettingsUpdate for Teams[0];
            declare netread Text[Text] Net_LibUI_Settings for Teams[0];
  
            declare PrevSettingsUpdate = -1;
            declare CutOffTimeLimit = -1;
              
            declare IsIntro = (
                UI.UISequence == CUIConfig::EUISequence::Intro ||
                UI.UISequence == CUIConfig::EUISequence::RollingBackgroundIntro ||
                UI.UISequence == CUIConfig::EUISequence::Outro
            );        
                                           
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
    
            if (Frame.Visible && IsIntro) {
                Frame.Visible = False;
            } else if (!Frame.Visible && !IsIntro) {            
                Frame.Visible = True;                        
            }
            if (PrevSettingsUpdate != Net_LibUI_SettingsUpdate) {
                  PrevSettingsUpdate = Net_LibUI_SettingsUpdate;
                  foreach (SettingName => SettingValue in Net_LibUI_Settings) {
                        switch (SettingName) {                  
                              case "TMUIModule_Countdown_CutOffTimeLimit": {
                                  CutOffTimeLimit = TextLib::ToInteger(SettingValue);
                              }
                        }
                  }              
            }
           
            if (CutOffTimeLimit > 0) {
                if (!Frame.Visible) Frame.Visible = True;
            } else if (Frame.Visible) {
                Frame.Visible = False;
            }
           
           if (CutOffTimeLimit >= GameTime) Countdown.Value = TextLib::TimeToText(CutOffTimeLimit - GameTime + 1, False);
           else Countdown.Value = TextLib::TimeToText(0);

           if (CutOffTimeLimit - GameTime > 30000) { 
                Countdown.TextColor = <1., 1., 1.>;
           } else {
                Countdown.TextColor = <.9, 0.0, 0.0>;
           }
           
            if (GUIPlayer != Null && GUIPlayer.IsSpawned) {
                declare Real speed = GUIPlayer.DisplaySpeed / 1000.;		                
                Gear.Value= "" ^ GUIPlayer.EngineCurGear;
                Distance.Value= "" ^ MathLib::NearestInteger(GUIPlayer.Distance);		
                Speed.Value = "" ^ GUIPlayer.DisplaySpeed;
                declare Real rpm = GUIPlayer.EngineRpm / 11000;
                RPM1.RelativeRotation  = -180 + (MathLib::Clamp(rpm * 2. , 0., 1.) * 180);
                RPM2.RelativeRotation  = (MathLib::Clamp((rpm -0.5)*2. ,0., 1.) * 180);
            } else {
                Speed.Value = "0";
                Gear.Value= "N";
                Distance.Value= "0";		
                RPM1.RelativeRotation  = -180.;
                RPM2.RelativeRotation  = 0.;
            }                        
      
EOL
        );

    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
    }


}
