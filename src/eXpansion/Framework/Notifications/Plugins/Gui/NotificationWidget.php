<?php

namespace eXpansion\Framework\Notifications\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class NotificationWidget extends WidgetFactory
{
    const toastCount = 4;

    /** @var NotificationUpdater */
    protected $updaterWidgetFactory;

    /***
     * MenuFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WidgetFactoryContext $context
     * @param NotificationUpdater  $notificationUpdater
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        NotificationUpdater $notificationUpdater
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->updaterWidgetFactory = $notificationUpdater;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        for ($x = 0; $x < self::toastCount; $x++) {
            $manialink->addChild($this->createToast($x));
        }

        $manialink->getFmlManialink()->getScript()->addScriptFunction("", /** @lang text */
            <<<EOL

            Text getMessage(Text[Text] _Message) {
                if (_Message.existskey(LocalUser.Language)) {
                       return _Message[LocalUser.Language];           
                }
                
                return _Message["en"];
            }
            
            Void HideToast(CMlFrame _Frame, Boolean _Instant) {
                declare CMlFrame[] Frames for This;
                
                foreach(Element in _Frame.Controls) {
                    if (_Instant) {
                        if (Element is CMlLabel) {
                            (Element as CMlLabel).Opacity = 0.;
                        }
                        
                        if (Element is CMlQuad) {
                            (Element as CMlQuad).Opacity = 0.;
                        }	
                    } else {
                        AnimMgr.Add(Element, "<elem opacity=\"0\" />", 500, CAnimManager::EAnimManagerEasing::QuadOut);
                    }
                }
                
                if (_Instant) {
                    Frames.remove(_Frame);
                }
            }
            
            Void ShowToast(Text[Text][Text] _Notification) {
    
                declare CMlFrame[] Frames for This;
                declare CMlFrame Exp_Window <=> (Page.GetFirstChild("Window") as CMlFrame);
                
                if (Frames.count >= 4) {
                    HideToast(Frames[0], True);
                }
                Page.GetClassChildren("uiToast", Exp_Window, True);
                
                
                foreach (Read in Page.GetClassChildren_Result) {
                    
                    declare Frame = (Read as CMlFrame);
                    
                    // stop at first available toast
                    if ( (Frame.Controls[0] as CMlLabel).Opacity < 0.1) {
                    Frame.Show();
                    Frames.add(Frame);
                    (Frame.Controls[0] as CMlLabel).Opacity = 0.1;
                    declare Prefix = _Notification["params"]["prefix"];
                    
                    (Frame.Controls[1] as CMlLabel).Value = Prefix ^ getMessage(_Notification["title"]);
                    (Frame.Controls[2] as CMlLabel).Value = getMessage(_Notification["message"]);
                    declare Integer Duration =  TextLib::ToInteger(_Notification["params"]["duration"]);
                   
                        foreach(Element in Frame.Controls) {
                                AnimMgr.Add(Element, "<elem opacity=\"1\" />", 500, CAnimManager::EAnimManagerEasing::QuadIn);
                                if (Duration > 0) {
                                    AnimMgr.Add(Element, "<elem opacity=\"0\" />", (Now + Duration), 500, CAnimManager::EAnimManagerEasing::QuadOut);
                                }
                        }
                                    
                        return;
                    }
                }
            }

EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
                                
                    declare CMlFrame[] Frames for This;
                    Frames.clear();
                    Page.GetClassChildren("uiToast", (Page.GetFirstChild("Window") as CMlFrame), True);
                  
                    foreach (Frame in Page.GetClassChildren_Result) {

                        foreach(Element in (Frame as CMlFrame).Controls) {
                            if (Element is CMlLabel) {
                                (Element as CMlLabel).Opacity = 0.;
                            }
                            
                            if (Element is CMlQuad) {
                                (Element as CMlQuad).Opacity = 0.;
                            }
                        }
                    }
                    {$this->updaterWidgetFactory->getScriptInitialization(true)}
EOL
        );
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop, <<<EOL

             {$this->updaterWidgetFactory->getScriptOnChange('
                 if (notification.count > 0) {
                      ShowToast(notification);
                 }
             ')}
             
                declare Index = 0;
                foreach(Frame in Frames) {
                    declare Real posY = 70.;		
                    if ((Frame.Controls[0] as CMlLabel).Opacity > 0.09) {
                        Frame.RelativePosition_V3.Y = posY - (Index* 28. * 0.8);
                        Index +=1;
                    } else {
                        HideToast(Frame, True);
                        Frame.Hide();
                    }
                }
                  
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::MouseClick, <<<EOL
            if (Event.Control.HasClass("toastClose")) {
			    HideToast(Event.Control.Parent, False);
		    }
        
EOL
        );


    }


    private function createToast($idx)
    {
        $frame = Frame::create()->setScale(0.75)->setPosition(-159, 80 - (21 * $idx))->addClass("uiToast");
        $closeButton = Label::create();
        $closeButton->setPosition(74, -4)->setSize(4, 4)->setText("✖")->setAreaColor("0000")->setAreaFocusColor("f00a")
            ->setAlign("center", "center2")->addClass("toastClose")->setScriptEvents(true)->setOpacity(0.5);
        $frame->addChild($closeButton);


        $title = Label::create()->setPosition(2, -2)->setSize(70, 5)->setTextSize(2)
            ->setTextColor("fff")->setText("n/a")->setOpacity(0);
        $frame->addChild($title);


        $message = Label::create()->setPosition(2, -8)->setSize(70,
            15)->setTextSize(2)->setMaxLines(4)->setOpacity(0.5)->setTextColor("fff")->setText("n/a");
        $frame->addChild($message);

        $quad = Quad::create();
        $quad->setSize(78, 27)->setBackgroundColor("000a");
        $frame->addChild($quad);

        return $frame;

    }


}
