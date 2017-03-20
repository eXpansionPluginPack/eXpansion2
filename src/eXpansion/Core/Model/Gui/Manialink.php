<?php

namespace eXpansion\Core\Model\Gui;

use eXpansion\Core\Model\UserGroups\Group;

class Manialink implements ManialinkInerface
{
    /** @var string  */
    protected $id;

    /** @var string */
    protected $name;

    /** @var  Group */
    protected $group;

    /**
     * Manialive constructor.
     * @param Group $group
     */
    public function __construct(Group $group, $name)
    {
        $this->group = $group;
        $this->name = $name;
        $this->id = spl_object_hash($this);
    }

    /**
     *
     * @return string
     */
    public function getXml()
    {
        return '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>'
                .'<manialink version="3" id="' . $this->getId() . '">'
                    .'<label text="Hello World!" />'
                .'</manialink>';
    }

    /**
     *
     * @return Group
     */
    public function getUserGroup()
    {
        return $this->group;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}