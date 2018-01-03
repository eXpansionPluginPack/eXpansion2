<?php

namespace eXpansion\Framework\Core\Model\Gui;

class Action
{
    protected $id;

    protected $callable;

    protected $args;

    /**
     * Action constructor.
     * @param $callable
     * @param $args
     */
    public function __construct($callable, $args)
    {
        $this->callable = $callable;
        $this->args = $args;
        $this->id = spl_object_hash($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function execute(ManialinkInterface $manialink, $login, $answerValues)
    {
        call_user_func_array($this->callable, [$manialink, $login, $answerValues, $this->args]);
    }
}
