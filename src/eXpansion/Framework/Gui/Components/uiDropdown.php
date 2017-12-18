<?php

namespace eXpansion\Framework\Gui\Components;

use eXpansion\Framework\Gui\Layouts\layoutRow;
use FML\Controls\Entry;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Script\ScriptLabel;
use FML\Types\ScriptFeatureable;

class uiDropdown extends abstractUiElement implements ScriptFeatureable
{
    /** @var int */
    protected $selectedIndex;

    /** @var bool */
    protected $isOpened;

    /** @var string */
    protected $name;

    /** @var string[] */
    protected $options;

    /** @var string */
    protected $id = "";

    /**
     * uiDropdown constructor \n
     *
     * Options ["display name" => "value"], both needs to be string
     *
     * @param string $name
     * @param array  $options
     * @param int    $selectedIndex
     * @param bool   $isOpened
     */
    public function __construct($name, $options, $selectedIndex = -1, $isOpened = false)
    {
        $this->name = $name;
        $this->options = $options;
        $this->selectedIndex = $selectedIndex;
        $this->isOpened = $isOpened;
        $this->setSize(30, 4);
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return static
     */
    public function prepare(Script $script)
    {
        $script->addScriptFunction("uiDropdownFunctions", $this->getScriptDropdown());
        $script->addCustomScriptLabel(ScriptLabel::MouseClick, $this->getScriptMouseClick());
        $script->addCustomScriptLabel(ScriptLabel::OnInit, $this->getScriptInit());
    }

    protected function getScriptInit()
    {
        return /** language=textmate  prefix=#RequireContext\n */
            <<<'EOD'
            Page.GetClassChildren ("uiContainer", Page.MainFrame, True);
            foreach (frame in Page.GetClassChildren_Result) {
               if (frame.HasClass("uiDropdown")) {
					uiRenderDropdown((frame as CMlFrame));				
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
            
            if (Event.Control.HasClass("uiSelectElement")) {
                if (Event.Control.Parent.HasClass("uiDropdown")) {									
                    uiToggleDropdown(Event.Control.Parent);	
                }		
                if (Event.Control.Parent.HasClass("uiDropdownSelect")) {			
                    uiSelectDropdown((Event.Control as CMlLabel));	
                }
		    }	
		    																
EOD;
    }


    /**
     * @return string
     */
    protected function getScriptDropdown()
    {
        return /** @lang textmate */
            <<<'EOD'

Void uiRenderDropdown(CMlFrame frame) {
	declare selected = TextLib::ToInteger(frame.DataAttributeGet("selected"));
	declare index = 0;
	
    declare options = (frame.Controls[3] as CMlFrame);
    
    	if (frame.DataAttributeGet("open") == "1") {		   
		    frame.Controls[3].Show();
	    } else {
    		 frame.Controls[3].Hide();
    	}
	
        foreach (option in options.Controls) {
            if (selected == index) {
                (frame.Controls[1] as CMlLabel).Value = (option as CMlLabel).Value;
                (frame.Controls[2] as CMlEntry).Value = option.DataAttributeGet("value");
            }										
        index+= 1;
    }
}	

Void uiToggleDropdown (CMlFrame frame) { 
    if (frame.DataAttributeGet("open") == "1") {	
        frame.DataAttributeSet("open","0");
    } else {
        frame.DataAttributeSet("open","1");
    }
     uiRenderDropdown(frame);
}

Void uiSelectDropdown (CMlLabel label) {
    declare uiDropdown = label.Parent.Parent;
	uiDropdown.DataAttributeSet("selected", label.DataAttributeGet("index"));
	uiDropdown.DataAttributeSet("value", label.DataAttributeGet("value"));
	uiRenderDropdown(uiDropdown);
	uiToggleDropdown(uiDropdown);
	+++onSelectDropdown+++
}

EOD;
    } // end of getScriptRenderCheckbox

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     *
     * <frame pos="40 20" class="uiContainer uiDropdown" data-selected="1" data-open="0">
     * <label pos="20 0" z-index="0" size="5 5" text="⏷"  focusareacolor1="0000" focusareacolor2="0000"/>
     * <label pos="0 0" z-index="0" size="25 5" text="select" focusareacolor1="000" focusareacolor2="111" scriptevents="1" class="uiElement"/>
     * <entry pos="45 -3" z-index="0" size="26 6" textemboss="1" text="1" textsize="3" valign="center2" halign="center" textformat="Basic" name="checkbox"  hidden="0"/>
     * <frame pos="0 -5" class="uiDropdownSelect" size="40 40">
     * <label pos="0 0" z-index="0" size="25 5" text="option 1" data-value="asd" data-index="0" focusareacolor1="000" focusareacolor2="222" scriptevents="1" class="uiElement"/>
     * <label pos="0 -5" z-index="0" size="25 5" text="option 2" data-value="das" data-index="1" focusareacolor1="000" focusareacolor2="222" scriptevents="1" class="uiElement"/>
     * <label pos="0 -10" z-index="0" size="25 5" text="option 3" data-value="sad" data-index="2" focusareacolor1="000" focusareacolor2="222" scriptevents="1" class="uiElement"/>
     * </frame>
     * </frame>
     */
    public function render(\DOMDocument $domDocument)
    {
        $frame = new Frame();
        $frame->addClasses(['uiContainer uiDropdown'])
            ->setPosition($this->posX, $this->posY, $this->posZ)
            ->addDataAttribute("selected", $this->selectedIndex)
            ->addDataAttribute("open", $this->isOpened ? "1" : "0");

        if ($this->id) {
            $frame->setId($this->id);
        }

        $labelMark = new uiLabel("⏷");
        $labelMark->setAlign("left", "center");
        $labelMark->setPosition(0, -($this->height / 2));
        $labelMark->setSize(5, 5)->setX($this->width - 5);

        $baseLabel = new Label();
        $baseLabel->setAreaColor("000")->setAreaFocusColor("333")
            ->setScriptEvents(true)->addClass("uiSelectElement")
            ->setSize($this->width, $this->height)
            ->setPosition(0, -($this->height / 2))
            ->setTextPrefix(" ")
            ->setTextSize(1)
            ->setAlign("left", "center")
            ->addClasses($this->_classes)
            ->setDataAttributes($this->_dataAttributes);

        $labelTitle = clone $baseLabel;
        $labelTitle->setText("Select...")
            ->setSize($this->width, $this->height);

        $entry = new Entry();
        $entry->setPosition(900, 900)
            ->setName($this->name);

        $frameOptions = new layoutRow(0, -($this->height + ($this->height / 2)));
        $frameOptions->addClass('uiDropdownSelect');

        $idx = 0;
        foreach ($this->options as $key => $value) {
            $option = clone $baseLabel;
            $option->setText($key)->addDataAttribute('value', $value)
                ->addDataAttribute("index", $idx);
            $frameOptions->addChild($option);
            $idx += 1;
        }

        $frame->addChild($labelMark);
        $frame->addChild($labelTitle);
        $frame->addChild($entry);
        $frame->addChild($frameOptions);

        return $frame->render($domDocument);
    }

    /**
     * @return mixed
     */
    public function getIsOpened()
    {
        return $this->isOpened;
    }

    /**
     * @param mixed $isOpened
     */
    public function setIsOpened($isOpened)
    {
        $this->isOpened = $isOpened;
    }

    /**
     * @return mixed
     */
    public function getSelectedIndex()
    {
        return $this->selectedIndex;
    }

    /**
     * @param mixed $selectedIndex
     */
    public function setSelectedIndex($selectedIndex)
    {
        $this->selectedIndex = $selectedIndex;
    }


    /**
     * Sets selected index by entry return value
     * @param $value
     */
    public function setSelectedByValue($value)
    {
        $x = 0;
        foreach ($this->options as $idx => $data) {
            if ($value == $data) {
                $this->setSelectedIndex($x);
            }
            $x++;
        }

        $this->setSelectedIndex(-1);
    }

    /**
     * Get the Script Features
     *
     * @return ScriptFeature[]
     */
    public function getScriptFeatures()
    {
        return ScriptFeature::collect($this);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }
}
