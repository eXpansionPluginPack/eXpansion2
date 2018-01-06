<?php

namespace eXpansion\Framework\Core\Model\Gui;

class Action
{
    protected $id;

    protected $callable;

    protected $args;

    protected $permanent;

    /**
     * Action constructor.
     *
     * @param $callable
     * @param $args
     * @param boolean $permanent
     */
    public function __construct($callable, $args, $permanent = false)
    {
        $this->callable = $callable;
        $this->args = $args;
        $this->id = spl_object_hash($this);
        $this->permanent = $permanent;
    }

    /**
     * Get the id of the action.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Execute the action.
     *
     * @param ManialinkInterface $manialink
     * @param                    $login
     * @param                    $answerValues
     */
    public function execute(ManialinkInterface $manialink, $login, $answerValues)
    {
        call_user_func_array($this->callable, [$manialink, $login, $answerValues, $this->args]);
    }

    /**
     * Is this action to be destroyed on each page update.
     *
     * @return boomean
     */
    public function isPermanent()
    {
        return $this->permanent;
    }
}
