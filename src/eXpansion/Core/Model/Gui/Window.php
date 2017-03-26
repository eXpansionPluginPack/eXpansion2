<?php

namespace eXpansion\Core\Model\Gui;

use eXpansion\Core\Model\UserGroups\Group;
use Manialib\Manialink\Elements\Quad;

class Window extends Manialink
{

    protected $manialink;

    public function __construct(Group $group, $name, $sizeX, $sizeY, $posX = null, $posY = null)
    {
        parent::__construct($group, $name, $sizeX, $sizeY, $posX, $posY);

        $ml = new \Manialib\Manialink\Elements\Manialink();
        $ml->setVersion(3);
        $ml->setName($name);

        Quad::create()->setSizen($sizeX, $sizeY)->setPosn($posX, $posY)->setBgcolor("0008")->appendTo($ml);

        $this->manialink = $ml;
    }


    public function getXml()
    {
        return (string)$this->manialink;
    }

}
