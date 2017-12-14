<?php


namespace Tests\eXpansion\Framework\Core\TestHelpers;


use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;

trait ManialinkDataTrait
{
    /**
     * @param $logins
     *
     * @return Manialink
     */
    protected function getManialink($logins, $factory = null)
    {
        $dispatcher = $this->getMockBuilder(DispatcherInterface::class)->getMock();

        $group = new Group("test", $dispatcher);
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
