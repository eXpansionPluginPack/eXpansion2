<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory as BaseWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiCheckbox;
use eXpansion\Framework\Gui\Components\uiDropdown;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Components\uiLine;
use eXpansion\Framework\Gui\Layouts\layoutLine;
use eXpansion\Framework\Gui\Layouts\layoutRow;
use FML\Controls\Quad;

class WindowFactory extends BaseWindowFactory
{

    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $label = new uiLabel("Test", uiLabel::TYPE_NORMAL);
        $manialink->addChild($label);

        $checkbox = new uiCheckbox("test checkbox 1", "checkbox1");
        $checkbox2 = new uiCheckbox("test checkbox 2", "checkbox2");
        $line1 = new layoutRow(0, 0, [$checkbox, $checkbox2], 0);

        $ok = new uiButton("Apply", uiButton::TYPE_DECORATED);
        $ok->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "ok"]));

        $cancel = new uiButton("Cancel");
        $cancel->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "cancel"]));

        $line2 = new layoutLine(0, 0, [$ok, $cancel], 1);


        $line3 = new layoutRow(55, 0, [], 1);

        for ($x = 0; $x < 10; $x++) {
            $btn = new uiCheckbox('box'.$x, 'cb_'.$x);
            $line3->addChild($btn);
        }

        $manialink->addChild($line3);

        $row = new layoutRow(0, -10, [$line1, $line2], 0);
        $manialink->addChild($row);


        $dropdown = new uiDropdown("dropdown", ["option1" => 1, "option2" => 2]);
        $dropdown->setPosition(0, -30);
        $manialink->addChild($dropdown);

        $dropdown = new uiDropdown("style", ["tech" => "tech", "fullspeed" => "fullspeed", "speedtech" => "speedtech"]);
        $dropdown->setPosition(40, -30);
        $manialink->addChild($dropdown);


        $quad = new Quad();
        $quad->setPosition(20, -40)
            ->setAlign("center", "center")
            ->setSize(2,2)
            ->setBackgroundColor("0f0");
        $manialink->addChild($quad);

        $quad = new Quad();
        $quad->setPosition(40, -20)
            ->setAlign("center", "center")
            ->setSize(2,2)
            ->setBackgroundColor("0ff");
        $manialink->addChild($quad);

        $line = new uiLine(20.1, -40);
        $line->to(20.1, 0);
        $manialink->addChild($line);

        $line = new uiLine(20, -40);
        $line->to(40, -20);

        $manialink->addChild($line);

    }


    public function ok($login, $params, $args)
    {
        var_dump($login);
        print_r($params);
        print_r($args);


    }


}
