<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Entry;
use FML\Controls\Frame;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Script\ScriptLabel;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class uiCheckbox extends abstractUiElement implements ScriptFeatureable
{
    /**
     * @var string
     */
    private $text;
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $checked = false;
    /**
     * @var bool
     */
    private $disabled = false;


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

        $checkedLabel = new uiLabel('✔');
        $checkedLabel->setTextSize(4)
            ->setAlign('center', 'center2')
            ->setSize(6, 6)
            ->setPosition(3.5, -2);
        $checkedLabel->setScriptEvents(true)
            ->addClass('uiElement')
            ->setVisible(true);
        $checkedBackground = clone $checkedLabel;

        $checkedLabel->addClass('uiChecked');


        $checkedBackground->setText('⬜')->setVisible(true)->setPosition(3, -3);

        $label = new uiLabel($this->getText());
        $label->setTranslate(true)
            ->setPosition(6, -2.5)
            ->setScriptEvents(true)
            ->addClasses(['uiElement']);


        $containerFrame->addChild($entry);
        $containerFrame->addChild($checkedLabel);
        $containerFrame->addChild($checkedBackground);
        $containerFrame->addChild($label);

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

    private function getScriptInit()
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
    private function getScriptMouseClick()
    {
        return /** language=textmate  prefix=#RequireContext\n */
            <<<'EOD'
            if (Event.Control.HasClass("uiElement") ) {
                if (Event.Control.Parent.HasClass("uiCheckbox")) {									
                        uiToggleCheckbox(Event.Control.Parent);	
                }
            }																		
EOD;
    }


    /**
     * @return string
     */
    private function getScriptRenderCheckbox()
    {
        return /** @lang textmate */
            <<<'EOD'
  
          
Void uiRenderCheckbox(CMlFrame frame) {
	foreach (uiControl  in frame.Controls) {		
			if (uiControl.HasClass("uiChecked")) {
				if (frame.DataAttributeGet("checked") == "1") {
						uiControl.Show();
					} else {
						uiControl.Hide();
					}
			}
			if (uiControl is CMlEntry) {
			declare entry <=> (uiControl as CMlEntry);
				entry.Value = frame.DataAttributeGet("checked") ;							
			}
		}
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
}
