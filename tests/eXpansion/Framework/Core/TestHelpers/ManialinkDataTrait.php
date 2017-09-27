<?php


namespace Tests\eXpansion\Framework\Core\TestHelpers;


use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\Dispatcher;

trait ManialinkDataTrait
{
    /**
     * @param $logins
     *
     * @return Manialink
     */
    protected function getManialink($logins)
    {
        $group = new Group("test", $this->container->get(Dispatcher::class));
        foreach ($logins as $login) {
            $group->addLogin($login);
        }

        $manialink = new Manialink($group, 'test', 1, 1,1,1);

        return $manialink;
    }
}
