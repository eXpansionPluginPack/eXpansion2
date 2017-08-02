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

    /** removes child control */
    public function removeChild(Renderable $child);
}
