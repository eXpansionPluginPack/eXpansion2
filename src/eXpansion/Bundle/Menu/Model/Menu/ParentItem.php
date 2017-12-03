<?php

namespace eXpansion\Bundle\Menu\Model\Menu;
use eXpansion\Bundle\Menu\Services\ItemBuilder;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
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
     * @param string      $id
     * @param string      $path
     * @param string      $labelId
     * @param AdminGroups $adminGroups
     * @param null        $permission
     */
    public function __construct(
        ItemBuilder $itemBuilder,
        $id,
        $path,
        $labelId,
        AdminGroups $adminGroups,
        $permission = null
    ) {
        parent::__construct($id, $path, $labelId, $adminGroups, $permission);
        $this->itemBuilder = $itemBuilder;
    }


    /**
     * @inheritdoc
     */
    public function execute(
        ManialinkFactory $manialinkFactory,
        ManialinkInterface $manialink,
        $login,
        $answerValues,
        $args
    ) {
        $manialink->setData('current_path', $this->getPath());
        $manialinkFactory->update($manialink->getUserGroup());
    }

    /**
     * Add a new child element to the parent node.
     *
     * @param string $class Class of the item.
     * @param string $id Id of the item
     * @param string $label
     * @param string $permission
     * @param array $options
     *
     * @return ItemInterface
     */
    public function addChild($class, $id, $label, $permission, $options =[])
    {
        if (is_string($id)) {
            $id = explode('/', $id);
        }

        if (count($id) == 1) {
            if (isset($this->childItems[$id[0]])) {
                return $this->childItems[$id[0]];
            }

            $item = $this->itemBuilder->create(
                $class,
                $id[0],
                $this->getPath() . '/' . $id[0],
                $label,
                $permission,
                $options
            );
            $this->childItems[$id[0]] = $item;
            return $item;
        }

        $parent = array_splice($id, 0, count($id) - 1);
        return $this->getChild($parent)->addChild($class, $id, $label, $permission, $options);
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

    /**
     * Get childrens of this parent.
     *
     * @return ItemInterface[]
     */
    public function getChilds()
    {
        return $this->childItems;
    }

    /**
     * Check if menu is visible and at least one children is visible.
     *
     * @param string $login
     *
     * @return bool|mixed
     */
    public function isVisibleFor($login)
    {
        $personalVisibility = parent::isVisibleFor($login);
        if (!$personalVisibility) {
            return $personalVisibility;
        }


        foreach ($this->getChilds() as $childItem) {
            if ($childItem->isVisibleFor($login)) {
                return true;
            }
        }

        return false;
    }
}
