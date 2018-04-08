<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 21.13
 */

namespace eXpansionExperimantal\Bundle\Dedimania\Structures;


use Maniaplanet\DedicatedServer\Structures\AbstractStructure;

class DedimaniaRecord extends AbstractStructure
{
    /** @var string */
    public $login = "";
    /** @var string */
    public $nickName = "";
    /** @var int */
    public $best = -1;
    /** @var int */
    public $rank = -1;
    /** @var int */
    public $maxRank = 15;
    /** @var string */
    public $checks = "";
    /** @var int */
    public $vote = -1;

    public function __construct($login = "")
    {
        $this->login = $login;
    }

    public function getCheckpoints()
    {
        $checks = explode(",", $this->checks);
        $out = [];
        foreach ($checks as $check) {
            $out[] = intval($check);
        }

        return $out;
    }

}