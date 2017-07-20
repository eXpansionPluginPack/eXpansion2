<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\Data\DataStorageTrait;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Types\Renderable;

class Manialink implements ManialinkInterface
{
    use DataStorageTrait;

    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var  Group */
    protected $group;

    /** @var float */
    protected $sizeX;

    /** @var float */
    protected $sizeY;

    /** @var float */
    protected $posX;

    /** @var float */
    protected $posY;

    /**
     * Manialive constructor.
     * @param Group $group
     */
    public function __construct(
        Group $group,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        $this->group = $group;
        $this->name = $name;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->posX = $posX;
        $this->posY = $posY;
        $this->id = spl_object_hash($this);
    }

    /**
     *
     * @return string
     */
    public function getXml()
    {
        return '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>'
            .'<manialink version="3" id="'.$this->getId().'">'
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

    /**
     * @inheritdoc
     */
    public function addChild(Renderable $child)
    {
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
    }

    /**
     * @inheritdoc
     */
    public function getContentFrame()
    {
    }
}
