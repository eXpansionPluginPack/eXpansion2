<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Frame;
use FML\Types\Renderable;

interface ManialinkInterface
{
    /**
     *
     * @return string
     */
    public function getXml();

    /**
     * Get the factory responsible of this manialink.
     *
     * @return ManialinkFactoryInterface
     */
    public function getManialinkFactory(): ManialinkFactoryInterface;

    /**
     *
     * @return Group
     */
    public function getUserGroup();

    /**
     *
     * @return string
     */
    public function getId();

    /**
     * Add a new child
     *
     * @api
     *
     * @param Renderable $child Child Control to add
     */
    public function addChild(Renderable $child);

    /**
     * Get the children
     *
     * @api
     * @return Renderable[]
     */
    public function getChildren();

    /**
     * @return Frame
     */
    public function getContentFrame();

    /**
     * Removes a child.
     *
     * @param Renderable $child
     *
     * @return mixed
     */
    public function removeChild(Renderable $child);

    /**
     * Gets saved manialink data
     *
     * @param string $name
     * @return mixed
     */
    public function getData($name);

    /**
     * Sets manialink data
     *
     * @param string $name
     * @param mixed $data
     */
    public function setData($name, $data);

    /**
     * Destroys a manialink.
     *
     * @return mixed
     */
    public function destroy();
}
