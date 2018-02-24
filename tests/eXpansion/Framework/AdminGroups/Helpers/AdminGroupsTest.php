<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 12:24
 */

namespace Tests\eXpansion\Framework\AdminGroups\Helpers;

use eXpansion\Framework\AdminGroups\Exceptions\UnknownGroupException;
use Tests\eXpansion\Framework\AdminGroups\TestAdminGroups;


/**
 * Class AdminGroups
 *
 * @package Tests\eXpansion\Framework\AdminGroups\Helpers;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class AdminGroupsTest extends TestAdminGroups
{

    public function testGetUserGroups()
    {
        $helper = $this->getAdminGroupHelper();

        $this->assertCount(count($this->getAdminGroupConfiguration()) + 1, $helper->getUserGroups());
    }

    public function testGetLoginUserGroup()
    {
        $helper = $this->getAdminGroupHelper();

        $this->assertEquals('admin:master_admin', $helper->getLoginUserGroups('toto1')->getName());
        $this->assertEquals('admin:admin', $helper->getLoginUserGroups('toto10')->getName());
        $this->assertEquals('admin:operator', $helper->getLoginUserGroups('toto21')->getName());
        $this->assertEquals('admin:guest', $helper->getLoginUserGroups('toto_invalid')->getName());


    }

    public function testHasPermission()
    {
        $helper = $this->getAdminGroupHelper();

        // master admin tests
        $this->assertTrue($helper->hasPermission('toto1', 'p1'));
        $this->assertTrue($helper->hasPermission('toto1', 'p10'));
        $this->assertTrue($helper->hasPermission('toto1', 'p20'));
        $this->assertTrue($helper->hasPermission('toto1', 'p_invalid'));

        // if admin has permission
        $this->assertFalse($helper->hasPermission('toto10', 'p1'));
        $this->assertTrue($helper->hasPermission('toto10', 'p10'));
        $this->assertFalse($helper->hasPermission('toto10', 'p20'));
        $this->assertFalse($helper->hasPermission('toto10', 'p_invalid'));

        // if operator has permission
        $this->assertFalse($helper->hasPermission('toto20', 'p1'));
        $this->assertFalse($helper->hasPermission('toto20', 'p10'));
        $this->assertTrue($helper->hasPermission('toto20', 'p20'));
        $this->assertFalse($helper->hasPermission('toto20', 'p_invalid'));


        // guest group
        $this->assertFalse($helper->hasPermission('toto_invalid', 'p1'));
        $this->assertFalse($helper->hasPermission('toto_invalid', 'p10'));
        $this->assertFalse($helper->hasPermission('toto_invalid', 'p20'));
        $this->assertFalse($helper->hasPermission('toto_invalid', 'p_invalid'));
    }

    public function testHasGroupPermission()
    {
        $helper = $this->getAdminGroupHelper();

        $this->assertTrue($helper->hasGroupPermission('admin:master_admin', 'wrong_permission'));
        $this->assertTrue($helper->hasGroupPermission('admin:master_admin', 'p1'));
        $this->assertTrue($helper->hasGroupPermission('admin:master_admin', 'p10'));
        $this->assertTrue($helper->hasGroupPermission('admin:master_admin', 'p20'));

        $this->assertFalse($helper->hasGroupPermission('admin:admin', 'wrong_permission'));
        $this->assertFalse($helper->hasGroupPermission('admin:admin', 'p1'));
        $this->assertTrue($helper->hasGroupPermission('admin:admin', 'p10'));
        $this->assertFalse($helper->hasGroupPermission('admin:admin', 'p20'));

        $this->assertFalse($helper->hasGroupPermission('admin:operator', 'wrong_permission'));
        $this->assertFalse($helper->hasGroupPermission('admin:operator', 'p1'));
        $this->assertFalse($helper->hasGroupPermission('admin:operator', 'p10'));
        $this->assertTrue($helper->hasGroupPermission('admin:operator', 'p20'));

        // empty group
        $this->assertFalse($helper->hasGroupPermission('admin:empty', 'wrong_permission'));

        // invalid group
        $this->expectException(UnknownGroupException::class);
        $this->assertFalse($helper->hasGroupPermission('invalid_group', 'wrong_permission'));
    }
    
    public function testGuestUserGroupPermission()
    {
        $helper = $this->getAdminGroupHelper();
        $this->assertFalse($helper->hasGroupPermission('admin:guest', 'p1'));
    }

    public function testHasGroupPermissionsException()
    {
        $helper = $this->getAdminGroupHelper();

        $this->expectException(UnknownGroupException::class);

        $helper->hasGroupPermission('yoyo', 'p20');
    }
}
