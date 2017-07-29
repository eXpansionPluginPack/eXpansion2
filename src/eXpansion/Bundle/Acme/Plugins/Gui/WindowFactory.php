<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory as BaseWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Quad;

class WindowFactory extends BaseWindowFactory
{
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);

        $label = $this->uiFactory->createLabel("Test", uiLabel::TYPE_NORMAL);
        $tooltip->addTooltip($label, "tooltip test");

        $manialink->addChild($label);

        $checkbox = $this->uiFactory->createCheckbox("test checkbox 1", "checkbox1");
        $tooltip->addTooltip($checkbox, "testing 123");

        $checkbox2 = $this->uiFactory->createCheckbox("test checkbox 2", "checkbox2");
        $tooltip->addTooltip($checkbox2, "testing");
        $line1 = $this->uiFactory->createLayoutRow(0, 0, [$checkbox, $checkbox2], 0);

        $ok = $this->uiFactory->createButton("Apply", uiButton::TYPE_DECORATED);
        $tooltip->addTooltip($ok, "ridicolously long description text is here!");
        $ok->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "ok"]));

        $cancel = $this->uiFactory->createButton("Cancel");
        $cancel->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "cancel"]));

        $line2 = $this->uiFactory->createLayoutLine(0, 0, [$ok, $cancel], 1);

        $line3 = $this->uiFactory->createLayoutRow(55, 0, [], 1);

        for ($x = 0; $x < 10; $x++) {
            $btn = $this->uiFactory->createCheckbox('box'.$x, 'cb_'.$x);
            $line3->addChild($btn);
        }

        $manialink->addChild($line3);

        $row = $this->uiFactory->createLayoutRow(0, -10, [$line1, $line2], 0);
        $manialink->addChild($row);


        $dropdown = $this->uiFactory->createDropdown("dropdown", ["option1" => 1, "option2" => 2]);
        $dropdown->setPosition(90, 0);
        $manialink->addChild($dropdown);

        $dropdown = $this->uiFactory->createDropdown("style", ["tech" => "tech", "fullspeed" => "fullspeed", "speedtech" => "speedtech"]);
        $dropdown->setPosition(130, 0);
        $manialink->addChild($dropdown);


        $quad = new Quad();
        $quad->setPosition(20, -40)
            ->setAlign("center", "center")
            ->setSize(2, 2)
            ->setBackgroundColor("0f0");
        $manialink->addChild($quad);

        $quad = new Quad();
        $quad->setPosition(40, -20)
            ->setAlign("center", "center")
            ->setSize(2, 2)
            ->setBackgroundColor("0ff");
        $manialink->addChild($quad);

        $input = $this->uiFactory->createInput("input1", "test text", 30);
        $input->setPosition(90,-30);
        $manialink->addChild($input);

        $input = $this->uiFactory->createTextbox("input2", "test\ntest2\ntest3\nest4\ntest5", 5, 30);
        $input->setPosition(130,-30);

        $manialink->addChild($input);
    }


    public function ok($login, $params, $args)
    {
        // do nothing at the moment
    }

}
