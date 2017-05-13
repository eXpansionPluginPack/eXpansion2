<?php


namespace Tests\eXpansion\Framework\Core\TestHelpers;


use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\UserGroups\Group;

trait ManialinkDataTrait
{
    /**
     * @param $logins
     *
     * @return Manialink
     */
    protected function getManialink($logins)
    {
        $group = new Group($this->container->get('expansion.framework.core.services.application.dispatcher'), "test");
        foreach ($logins as $login) {
            $group->addLogin($login);
        }

        $manialink = new Manialink($group, 'test', 1, 1,1,1);

        return $manialink;
    }
}