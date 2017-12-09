<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Script\Script;
use FML\Script\ScriptLabel;

class uiConfirmButton extends uiButton
{
    public function __construct(string $text = "button", string $type = self::TYPE_DEFAULT)
    {
        parent::__construct($text, $type);
        $this->buttonLabel->removeAllClasses();
        $this->buttonLabel->addClass("uiConfirmButtonElement");
        $this->buttonLabel->addClasses($this->_classes);
    }

    public function prepare(Script $script)
    {
        $script->addCustomScriptLabel(ScriptLabel::MouseClick, $this->getScriptMouseClick());
        $script->addScriptFunction("", $this->getScriptFunction());
    }

    protected function getScriptMouseClick()
    {
        return /** language=textmate  prefix=#RequireContext\n */
            <<<'EOD'
            if (Event.Control.HasClass("uiConfirmButtonElement") ) {           
                TriggerConfirmButtonClick((Event.Control as CMlLabel));                           
            }
EOD;
    }

    protected function getScriptFunction()
    {
        return /** language=textmate  prefix=#RequireContext\n */
            <<<'EOD'

            ***FML_OnInit***
            ***
                declare Integer[Ident] pendingConfirms for Page = Integer[Ident];     
                declare Text[Ident] pendingConfirmIds for Page = Text[Ident];
                declare CMlLabel[Ident] pendingConfirmControls for Page = CMlLabel[Ident];     
                
                pendingConfirms.clear();
                pendingConfirmIds.clear();
                pendingConfirmControls.clear();
            ***
            
            ***FML_Loop***
            ***
            foreach (Id => Time in pendingConfirms) {                  
                if (Now > Time + (3 * 1000) ) { 
                   if (pendingConfirmIds.existskey(Id))  {             
                        pendingConfirmControls[Id].Value = pendingConfirmIds[Id];
                        pendingConfirmIds.removekey(Id);
                        pendingConfirms.removekey(Id);    
                        pendingConfirmControls.removekey(Id);                    
                    }
                }
            }
            ***
                        
           
            Void TriggerConfirmButtonClick(CMlLabel Control) {                     
                   declare Integer[Ident] pendingConfirms for Page = Integer[Ident];     
                   declare Text[Ident] pendingConfirmIds for Page = Text[Ident];
                   declare CMlLabel[Ident] pendingConfirmControls for Page = CMlLabel[Ident];              
                   
                   if (Control.Parent.HasClass("uiButton")) {                  
                          if (pendingConfirmIds.existskey(Control.Id) == False) {
                                pendingConfirmIds[Control.Id] = Control.Value;
                                pendingConfirmControls[Control.Id] = Control;
                                pendingConfirms[Control.Id] = Now;
                                Control.Value = "Confirm ?";                                                               
                                Control.Parent.RelativeScale = 0.75;
                                AnimMgr.Add(Control.Parent, "<elem scale=\"1.\" />", 200, CAnimManager::EAnimManagerEasing::QuadIn);
                          } else {
                             Control.Value = pendingConfirmIds[Control.Id];
                             pendingConfirmIds.removekey(Control.Id);            
                             pendingConfirms.removekey(Control.Id);
                             pendingConfirmControls.removekey(Control.Id);                                                                            
                             Control.Parent.RelativeScale = 0.75;
                             AnimMgr.Add(Control.Parent, "<elem scale=\"1.\" />", 200, CAnimManager::EAnimManagerEasing::QuadIn); 
                             TriggerPageAction(Control.Parent.DataAttributeGet("action"));
                         }                                                                                                                                              
                   }                
            }
            
 
            Void TriggerConfirmButtonClick(Text ControlId) {            
                declare CMlLabel Control = (Page.GetFirstChild(ControlId) as CMlLabel);
                TriggerConfirmButtonClick(Control);                               
            }


EOD;
    }

}
