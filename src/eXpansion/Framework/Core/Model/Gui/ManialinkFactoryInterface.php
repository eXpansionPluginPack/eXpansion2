<?php


namespace eXpansion\Framework\Core\Model\Gui;


use eXpansion\Framework\Core\Model\UserGroups\Group;

interface ManialinkFactoryInterface
{

    /**
     * Get unique id for the factory.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Create & display manialink for user.
     *
     * @param Group|string|string[] $group
     *
     * @return Group
     */
    public function create($group);

    /**
     * Update the content of a manialink.
     *
     * @param $group
     *
     * @return mixed
     */
    public function update($group);

    /**
     * Hide & destoy manialink fr user.
     *
     * @param Group $group
     *
     * @return void
     */
    public function destroy(Group $group);
}