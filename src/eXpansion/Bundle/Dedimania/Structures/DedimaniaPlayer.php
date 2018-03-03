<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 21.13
 */

namespace eXpansion\Bundle\Dedimania\Structures;


use Maniaplanet\DedicatedServer\Structures\AbstractStructure;

class DedimaniaPlayer extends AbstractStructure
{
    /** @var string */
    public $login = "";
    /** @var int */
    public $maxRank = 15;
    /** @var int */
    public $banned = 0;
    /** @var bool */
    public $optionsEnabled = false;
    /** @var string */
    public $toolOption = "";

}