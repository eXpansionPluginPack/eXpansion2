<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Entry;
use FML\Controls\Frame;
use FML\Controls\Labels\Label_Text;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Script\ScriptLabel;
use FML\Types\ScriptFeatureable;

class uiCheckbox extends abstractUiElement implements ScriptFeatureable
{
    /**
     * @var string
     */
    protected $text;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var bool
     */
    protected $checked = false;
    /**
     * @var bool
     */
    protected $disabled = false;

    /**
     * uiCheckbox constructor.
     * @param string $text
     * @param string $name
     * @param bool $checked
     * @param bool $disabled
     */
    public function __construct($text, $name, $checked = false, $disabled = false)
    {
        $this->text = $text;
        $this->name = $name;
        $this->checked = $checked;
        $this->disabled = $disabled;
        $this->setWidth(30);
        $this->setHeight(6);
    }


    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     *
     *
     *
     * <frame pos="40 0" class="uiContainer uiCheckbox" data-checked="0" data-disabled="0">
     * <entry pos="45 -3" z-index="0" size="6 6" textemboss="1" text="1" textsize="3" valign="center2" halign="center" textformat="Basic" name="checkbox" scriptevents="1" hidden="0"/>
     * <label pos="3.5 -2" z-index="0" size="6 6" textemboss="1" textsize="4" text="✔" valign="center2" halign="center" class="uiElement uiChecked" scriptevents="1" focusareacolor1="0000" focusareacolor2="0000" hidden="1"/>
     * <label pos="3 -3" z-index="0" size="6 6" textemboss="1" textsize="4" text="⬜" valign="center2" halign="center" class="uiElement" scriptevents="1" focusareacolor1="0000" focusareacolor2="0000"/>
     * <label pos="6 -2.5" z-index="0" size="43 5" text="Selected Checkbox" textsize="2" scriptevents="1" focusareacolor1="0000" focusareacolor2="0000" valign="center" class="uiElement"/>
     * </frame>
     */
    public function render(\DOMDocument $domDocument)
    {
        $containerFrame = new Frame();
        $containerFrame->setPosition($this->posX, $this->posY)
            ->setZ($this->posZ)
            ->addClasses(['uiContainer', 'uiCheckbox'])
            ->addDataAttribute('checked', $this->isChecked() ? "1" : "0")
            ->addDataAttribute('disabled', $this->isDisabled() ? "1" : "0");


        $entry = new Entry();
        $entry->setPosition(900, 900)
            ->setName($this->name);

        $checkedBackground = new uiLabel('⬜');
        $checkedBackground->setTextSize(4)
            ->setAlign('center', 'center2')
            ->setSize(6, 6)
            ->setPosition(3, -3);
        $checkedBackground->setScriptEvents(true)
            ->addClass('uiCheckboxElement');
        $checkedBackground->setDataAttributes($this->_dataAttributes)->addClasses($this->_classes);

        $checkedLabel = clone $checkedBackground;
        $checkedLabel->setText('✔')->setPosition(3.5, -2)->setScale(0);

        $label = new uiLabel();
        $label->setTranslate(false)
            ->setAlign("left", "center2")
            ->setPosition(6, -3)
            ->setSize($this->width - 6, $this->height)
            ->setText($this->getText());

        $containerFrame->addChild($entry);
        $containerFrame->addChild($label);
        $containerFrame->addChild($checkedLabel);
        $containerFrame->addChild($checkedBackground);


        return $containerFrame->render($domDocument);
    }


    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param string $name
     * @return uiCheckbox
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }


    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return static
     */
    public function prepare(Script $script)
    {
        $script->addScriptFunction("uiCheckboxFunctions", $this->getScriptRenderCheckbox());
        $script->addCustomScriptLabel(ScriptLabel::MouseClick, $this->getScriptMouseClick());
        $script->addCustomScriptLabel(ScriptLabel::OnInit, $this->getScriptInit());
    }

    protected function getScriptInit()
    {
        return /** language=textmate  prefix=#RequireContext\n */
            <<<'EOD'
            Page.GetClassChildren ("uiContainer", Page.MainFrame, True);
            foreach (frame in Page.GetClassChildren_Result) {
                if (frame.HasClass("uiCheckbox")) {
                    uiRenderCheckbox((frame as CMlFrame));				
                }								
            }
EOD;
    }

    /**
     * @return string
     */
    protected function getScriptMouseClick()
    {
        return /** language=textmate  prefix=#RequireContext\n */
            <<<'EOD'
            if (Event.Control.HasClass("uiCheckboxElement") ) {
                if (Event.Control.Parent.HasClass("uiCheckbox")) {									
                        uiToggleCheckbox(Event.Control.Parent);	
                }
            }																		
EOD;
    }


    /**
     * @return string
     */
    protected function getScriptRenderCheckbox()
    {
        return /** @lang textmate */
            <<<'EOD'
  
          
Void uiRenderCheckbox(CMlFrame frame) {
    declare uiControl = frame.Controls[2];
	if (frame.DataAttributeGet("checked") == "1") {
       AnimMgr.Add(uiControl, "<frame scale=\"1.\" />", 250, CAnimManager::EAnimManagerEasing::BackOut);            
    } else {
       AnimMgr.Add(uiControl, "<frame scale=\"0.\" />", 100, CAnimManager::EAnimManagerEasing::BackIn);
	}
        declare CMlEntry entry = (frame.Controls[0] as CMlEntry);
	    entry.Value = frame.DataAttributeGet("checked") ;  
}	

Void uiToggleCheckbox(CMlFrame frame) { 
	if  (frame.DataAttributeGet("checked") == "1") {
		frame.DataAttributeSet("checked", "0");
	} else {
		frame.DataAttributeSet("checked", "1");			
	}
	uiRenderCheckbox(frame);			
}

EOD;
    } // end of getScriptRenderCheckbox

    /**
     * Get the Script Features
     *
     * @return ScriptFeature[]
     */
    public function getScriptFeatures()
    {
        return ScriptFeature::collect($this);
    }

    public function setSize($x, $y)
    {
        $this->width = $x;
        $this->height = $y;
    }
}
