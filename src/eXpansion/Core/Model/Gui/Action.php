<?php

namespace eXpansion\Core\Model\Gui;

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
        $this->id = spl_object_hash($this);
        $this->callable = $callable;
        $this->args = $args;
    }

    public function getId()
    {
        return $this->id;
    }

    public function execute($login, $answerValues)
    {
        call_user_func_array($this->callable, [$login, $answerValues, $this->args]);
    }
}