<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory as BaseWindowFactory;
use eXpansion\Framework\Gui\Builders\uiBuilder;

class WindowFactory extends BaseWindowFactory
{

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $builder = new uiBuilder($this->uiFactory, $this->actionFactory, $manialink, $this);

        $manialink->addChild($builder->build(<<<EOL
<window id="main">
    <uiLayoutRow margin="-2.">
        <uiLayoutLine pos="30,0" margin="0.5">
            <uiLabel>This is test</uiLabel>
            <uiLabel>This is test 1</uiLabel>
            <uiLabel>This is test 2</uiLabel>
        </uiLayoutLine>
        <uiLayoutLine margin="2">
            <uiButton actionCallback="callbackOk" type="decorated" >Ok</uiButton>
            <uiButton>Cancel</uiButton>
        </uiLayoutLine>
    </uiLayoutRow>
</window>
EOL
        ));
        echo "\n".$manialink->getXml()."\n";


    }


    public function callbackOk($manialink, $login, $entries, $args)
    {
        echo "\n".$login."- pressed ok\n";

    }
}
