<?php


namespace Tests\eXpansion\Core\Model\UserGoups;


use eXpansion\Core\Model\UserGroups\Group;


class GroupTest extends \PHPUnit_Framework_TestCase
{

    public function testPersistentGroup()
    {
        $group = new Group("test");

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
        $group = new Group();
        $this->assertFalse($group->isPersistent());
    }
}
