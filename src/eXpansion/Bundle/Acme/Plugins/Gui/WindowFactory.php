<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory as BaseWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiCheckbox;
use eXpansion\Framework\Gui\Components\uiDropdown;
use eXpansion\Framework\Gui\Components\uiInput;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Components\uiTextbox;
use eXpansion\Framework\Gui\Components\uiTooltip;
use eXpansion\Framework\Gui\Layouts\layoutLine;
use eXpansion\Framework\Gui\Layouts\layoutRow;
use eXpansion\Framework\Gui\Layouts\layoutScrollable;
use FML\Controls\Quad;

class WindowFactory extends BaseWindowFactory
{

    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $tooltip = new uiTooltip();
        $manialink->addChild($tooltip);

        $label = new uiLabel("Test", uiLabel::TYPE_NORMAL);
        $tooltip->addTooltip($label, "tooltip test");

        $manialink->addChild($label);

        $checkbox = new uiCheckbox("test checkbox 1", "checkbox1");
        $tooltip->addTooltip($checkbox, "testing 123");

        $checkbox2 = new uiCheckbox("test checkbox 2", "checkbox2");
        $tooltip->addTooltip($checkbox2, "testing");
        $line1 = new layoutRow(0, 0, [$checkbox, $checkbox2], 0);

        $ok = new uiButton("Apply", uiButton::TYPE_DECORATED);
        $tooltip->addTooltip($ok, "ridicolously long description text is here!");
        $ok->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "ok"]));

        $cancel = new uiButton("Cancel");
        $cancel->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "cancel"]));

        $line2 = new layoutLine(0, 0, [$ok, $cancel], 1);

        $line3 = new layoutRow(55, 0, [], 1);

        for ($x = 0; $x < 10; $x++) {
            $btn = new uiCheckbox('box'.$x, 'cb_'.$x);
            $btn->setPosition(0, 0);
            $tooltip->addTooltip($btn, "long description that should go over the bounding box".$x);
            $line3->addChild($btn);
        }
        $btn = new uiCheckbox('box11', 'cb_11');
        $btn->setPosition(20, 0);
        $line3->addChild($btn);

        $btn = new uiCheckbox('box11', 'cb_11');
        $btn->setPosition(50, 0);
        $line3->addChild($btn);

        $scrollable = new layoutScrollable($line3, 55, 30);
        $scrollable->setAxis(true, true);
        $manialink->addChild($scrollable);


        $row = new layoutRow(0, -10, [$line1, $line2], 0);

        $scrollable = new layoutScrollable($row, 40, 30);
        $scrollable->setAxis(true, true);
        $manialink->addChild($scrollable);


        $dropdown = new uiDropdown("dropdown", ["option1" => 1, "option2" => 2]);
        $dropdown->setPosition(97, 0);
        $tooltip->addTooltip($dropdown, "test");
        $manialink->addChild($dropdown);

        $dropdown = new uiDropdown("style", ["tech" => "tech", "fullspeed" => "fullspeed", "speedtech" => "speedtech"]);
        $dropdown->setPosition(130, 0);
        $manialink->addChild($dropdown);

        
        $input = new uiInput("input1", "test text", 30, "Password");
        $input->setPosition(130, -20);
        $tooltip->addTooltip($input, "test");
        $manialink->addChild($input);

        $input = new uiTextbox("input2", "test\ntest2\ntest3\nest4\ntest5", 5, 30);
        $input->setPosition(130, -30);
        $tooltip->addTooltip($input, "test2");
        $manialink->addChild($input);

    }


    public function ok($login, $params, $args)
    {
        // do nothing at the moment
    }

}
