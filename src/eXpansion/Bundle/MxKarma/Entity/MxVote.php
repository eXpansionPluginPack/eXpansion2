<?php

namespace eXpansion\Bundle\MxKarma\Entity;

class MxVote
{
    /** @var  string */
    public $login;
    /** @var  string */
    public $nickname;
    /** @var integer */
    public $vote;


    public function __construct($obj)
    {
        $this->login = $obj->login;
        $this->vote = (int)$obj->vote;
    }
}
