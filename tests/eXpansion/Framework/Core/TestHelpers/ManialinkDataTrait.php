<?php


namespace Tests\eXpansion\Framework\Core\TestHelpers;


use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\Dispatcher;

trait ManialinkDataTrait
{
    /**
     * @param $logins
     *
     * @return Manialink
     */
    protected function getManialink($logins, $factory = null)
    {
        $group = new Group("test", $this->container->get(Dispatcher::class));
        foreach ($logins as $login) {
            $group->addLogin($login);
        }

        if (is_null($factory)) {
            $factory = $this->getMockBuilder(ManialinkFactoryInterface::class)->getMock();
        }

        $manialink = new Manialink($factory, $group, 'test', 1, 1,1,1);

        return $manialink;
    }
}
