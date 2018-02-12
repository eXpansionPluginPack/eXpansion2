<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 12.2.2018
 * Time: 14.41
 */

namespace eXpansion\Framework\Core\Model\Gui;

use FML\Controls\Frame;
use FML\Elements\Format;
use FML\Types\Renderable;


/**
 * Class FmlManialink, is the object that will be used by the gui handler to display a manialink built with FML.
 *
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface FmlManialinkInterface
{
    /**
     * @return \FML\ManiaLink
     */
    public function getFmlManialink();

    /**
     * @inheritdoc
     */
    public function getXml();

    /**
     * Get the children
     *
     * @api
     * @return Renderable[]
     */
    public function getChildren();

    /**
     * Add a new child
     *
     * @api
     *
     * @param Renderable $child Child Control to add
     *
     * @return static
     */
    public function addChild(Renderable $child);

    /**
     * Add a new child
     *
     * @api
     *
     * @param Renderable $child Child Control to add
     *
     * @return static
     * @deprecated Use addChild()
     * @see        Container::addChild()
     */
    public function add(Renderable $child);

    /**
     * Add new children
     *
     * @api
     *
     * @param Renderable[] $children Child Controls to add
     *
     * @return static
     */
    public function addChildren(array $children);

    /**
     * Remove all children
     *
     * @api
     * @return static
     */
    public function removeAllChildren();

    /**
     * Remove all children
     *
     * @api
     * @return static
     * @deprecated Use removeAllChildren()
     * @see        Container::removeAllChildren()
     */
    public function removeChildren();

    /**
     * Get the Format
     *
     * @api
     *
     * @param bool $createIfEmpty If the format should be created if it doesn't exist yet
     *
     * @return Format
     * @deprecated Use Style
     * @see        Style
     */
    public function getFormat($createIfEmpty = true);

    /**
     * Set the Format
     *
     * @api
     *
     * @param Format $format New Format
     *
     * @return static
     * @deprecated Use Style
     * @see        Style
     *
     */
    public function setFormat(Format $format = null);

    /**
     * @return Frame
     */
    public function getContentFrame();
}