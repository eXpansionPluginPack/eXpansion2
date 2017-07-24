<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory as BaseWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiCheckbox;
use eXpansion\Framework\Gui\Components\uiLabel;

class WindowFactory extends BaseWindowFactory
{

    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $label = new uiLabel("Test", uiLabel::TYPE_NORMAL);
        $manialink->addChild($label);

        $checkbox = new uiCheckbox("test checkbox 1 ", "checkbox1");
        $checkbox->setPosition(10, -5);
        $manialink->addChild($checkbox);

        $checkbox = new uiCheckbox("test checkbox 2", "checkbox2");
        $checkbox->setPosition(10, -10);
        $manialink->addChild($checkbox);

        $ok = new uiButton("Apply", uiButton::TYPE_DECORATED);
        $ok->setPosition(10, -25)
            ->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "ok"]));
        $manialink->addChild($ok);

        $ok = new uiButton("Cancel");
        $ok->setPosition(40, -25)
            ->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'ok'], ["ok" => "cancel"]));
        $manialink->addChild($ok);

    }


    public function ok($login, $params, $args)
    {
        var_dump($login);
        print_r($params);
        print_r($args);
    }


}
