<?php

namespace eXpansion\Framework\GameShootmania\Structures;

use Maniaplanet\DedicatedServer\Structures\AbstractStructure;

class Landmark extends AbstractStructure
{
    /** @var string */
    public $tag = "";
    /** @var int */
    public $order = -1;
    /** @var string */
    public $id = "";
    /** @var array */
    public $position;

}
