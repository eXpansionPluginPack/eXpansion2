<?php


namespace Tests\eXpansion\Core\TestHelpers;


use eXpansion\Core\Model\Gui\Manialink;
use eXpansion\Core\Model\UserGroups\Group;

trait ManialinkDataTrait
{
    /**
     * @param $logins
     *
     * @return Manialink
     */
    protected function getManialink($logins)
    {
        $group = new Group($this->container->get('expansion.core.services.application.dispatcher'), "test");
        foreach ($logins as $login) {
            $group->addLogin($login);
        }

        $manialink = new Manialink($group, 'test', 1, 1,1,1);

        return $manialink;
    }
}