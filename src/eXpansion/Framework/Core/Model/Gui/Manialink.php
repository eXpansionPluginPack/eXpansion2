<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\Data\DataStorageTrait;
use eXpansion\Framework\Core\Model\DestroyableObject;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Types\Renderable;

class Manialink implements ManialinkInterface
{
    use DataStorageTrait;

    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var ManialinkFactoryInterface */
    private $manialinkFactory;

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

    /** @var int */
    protected $timeout = 0;

    /**
     * Manialink constructor
     *
     * @param ManialinkFactoryInterface $manialinkFactory
     * @param Group                     $group
     * @param string                    $name
     * @param int                       $sizeX
     * @param int                       $sizeY
     * @param float|null                $posX
     * @param float|null                $posY
     */
    public function __construct(
        ManialinkFactoryInterface $manialinkFactory,
        Group $group,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        $this->manialinkFactory = $manialinkFactory;
        $this->group = $group;
        $this->name = $name;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->posX = $posX;
        $this->posY = $posY;
        $this->id = uniqid("ml_", true);
    }

    /**
     *
     * @return string
     */
    public function getXml()
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>'
            .'<manialink version="3" id="'.$this->getId().'">'
            .'<label text="This is empty manialink!" />'
            .'</manialink>';
    }

    /**
     * Change the position of the manialink.
     *
     * @param float $posX
     * @param float $posY
     *
     * @return void
     */
    public function setPosition($posX, $posY)
    {
        $this->posY = $posY;
        $this->posX = $posX;
    }

    /**
     * @inheritdoc
     */
    public function getManialinkFactory(): ManialinkFactoryInterface
    {
        return $this->manialinkFactory;
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
     *
     * @return Group
     */
    public function getUserGroup()
    {
        return $this->group;
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

    public function removeChild(Renderable $child)
    {

    }

    /**
     * @inheritdoc
     */
    public function getContentFrame()
    {
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * getter for manialink name
     *
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * Destroys a manialink.
     *
     * @return mixed
     */
    public function destroy()
    {
        // This is not mandatory as GC will free the memory eventually but allows memory to be freed faster
        // by removing circular dependencies.
        if (!empty($this->data)) {
            foreach ($this->data as $data) {
                if ($data instanceof DestroyableObject) {
                    $data->destroy();
                }
            }
        }
        $this->data = [];
        $this->manialinkFactory = null;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
