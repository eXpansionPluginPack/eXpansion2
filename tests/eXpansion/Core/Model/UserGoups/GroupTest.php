<?php


namespace Tests\eXpansion\Core\Model\UserGoups;


use eXpansion\Core\Model\UserGroups\Group;
use Tests\eXpansion\Core\TestCore;


class GroupTest extends TestCore
{

    public function testPersistentGroup()
    {
        $group = new Group($this->container->get('expansion.core.services.application.dispatcher'), "test");

        $group->addLogin('l1');
        $group->addLogin('l2');

        $this->assertEquals('test', $group->getName());
        $this->assertEquals(['l1', 'l2'], $group->getLogins());
        $this->assertTrue($group->hasLogin('l1'));
        $this->assertTrue($group->isPersistent());

        $group->removeLogin('l1');
        $this->assertFalse($group->hasLogin('l1'));
    }

    public function testNonPersistentGroup()
    {
        $group = new Group($this->container->get('expansion.core.services.application.dispatcher'));
        $this->assertFalse($group->isPersistent());
    }
}
