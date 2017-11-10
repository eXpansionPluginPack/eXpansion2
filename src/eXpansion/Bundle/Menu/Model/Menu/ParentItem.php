<?php

namespace eXpansion\Bundle\Menu\Model\Menu;
use eXpansion\Bundle\Menu\Services\ItemBuilder;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use FML\Controls\Quad;

/**
 * Class ParentItem
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\Model\Menu
 */
class ParentItem extends AbstractItem
{
    /** @var ItemBuilder */
    protected $itemBuilder;

    /** @var ItemInterface[] */
    private $childItems = [];

    /**
     * ParentItem constructor.
     *
     * @param ItemBuilder $itemBuilder
     * @param string $id
     * @param string $path
     * @param string $labelId
     * @param Quad $icon
     * @param null $permission
     */
    public function __construct(ItemBuilder $itemBuilder, $id, $path, $labelId, Quad $icon, $permission = null)
    {
        parent::__construct($id, $path, $labelId, $icon, $permission);

        $this->itemBuilder = $itemBuilder;
    }


    /**
     * @inheritdoc
     */
    public function execute(ManialinkFactory $manialinkFactory, ManialinkInterface $manialink, $login, $answerValues, $args)
    {
        $manialink->setManialinkId($this->getId());
        $manialinkFactory->update($login);
    }

    /**
     * Add a new child element to the parent node.
     *
     * @param string $class Class of the item.
     * @param string $id Id of the item
     * @param string $label
     * @param Quad $icon
     * @param string $permission
     * @param array $options
     *
     * @return ItemInterface
     */
    public function addChild($class, $id, $label, Quad $icon, $permission, $options =[])
    {
        if (is_string($id)) {
            $id = explode('/', $id);
        }

        if (count($id) == 1) {
            $item = $this->itemBuilder->create(
                $class,
                $id[count($id) - 1],
                $this->getPath() . '/' . $id,
                $label,
                $icon,
                $permission
            );
            $this->childItems[$id] = $item;
            return $item;
        }


        $parent = array_splice($id, 1, count($id) - 2);
        $child = array_splice($id, 1, count($id) - 2);
        return $this->getChild($parent)->addChild($class, $child, $label, $icon, $permission, $options);
    }

    /**
     * Get child element from path py searching recursiveley.
     *
     * @param $path
     *
     * @return ItemInterface|null
     */
    public function getChild($path)
    {
        if (is_string($path)) {
            $path = explode('/', $path);
        }
        $remaining = array_splice($path, 1, count($path) - 1);

        foreach ($this->childItems as $childItem) {
            if ($childItem->getId() == $path[0]) {
                if (empty($remaining)) {
                    return $childItem;
                } elseif ($childItem instanceof ParentItem) {
                    return $childItem->getChild($remaining);
                } else {
                    return null;
                }
            }
        }

        return null;
    }
}