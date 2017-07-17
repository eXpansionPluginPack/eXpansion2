<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 11:57
 */

namespace Tests\eXpansion\Framework\AdminGroups\Services;

use Tests\eXpansion\Framework\AdminGroups\TestAdminGroups;

class AdminGroupConfigurationTest extends TestAdminGroups
{

    public function testGetGroups()
    {
        $config = $this->getAdminGroupConfigurationService();

        $this->assertEquals(
            array_keys($this->getAdminGroupConfiguration()),
            $config->getGroups()
        );
    }

    public function testGroupLogins()
    {
        $configService = $this->getAdminGroupConfigurationService();
        $config = $this->getAdminGroupConfiguration();

        $this->assertEquals(
            $config['master_admin']['logins'],
            $configService->getGroupLogins('master_admin')
        );

        $this->assertEquals(
            $config['admin']['logins'],
            $configService->getGroupLogins('admin')
        );

        $this->assertEquals(
            $config['operator']['logins'],
            $configService->getGroupLogins('operator')
        );

        $this->assertNull(
            $configService->getGroupLogins('operator_toto')
        );
    }

    public function testGroupPermissions()
    {
        $configService = $this->getAdminGroupConfigurationService();
        $config = $this->getAdminGroupConfiguration();

        $this->assertEquals(
            $config['admin']['permissions'],
            $configService->getGroupPermissions('admin')
        );

        $this->assertEquals(
            $config['operator']['permissions'],
            $configService->getGroupPermissions('operator')
        );

        $this->assertEmpty(
            $configService->getGroupPermissions('guest')
        );

        $this->assertEmpty(
            $configService->getGroupPermissions('invalidGroupName')
        );
    }

    public function testLoginGroupName()
    {
        $configService = $this->getAdminGroupConfigurationService();

        $this->assertEquals(
            'master_admin',
            $configService->getLoginGroupName('toto1')
        );

        $this->assertEquals(
            'admin',
            $configService->getLoginGroupName('toto10')
        );

        $this->assertEquals(
            'admin',
            $configService->getLoginGroupName('toto11')
        );

        $this->assertEquals(
            'operator',
            $configService->getLoginGroupName('toto20')
        );

        $this->assertNull(
            $configService->getLoginGroupName('toto_null')
        );
    }

    public function testPermission()
    {
        $configService = $this->getAdminGroupConfigurationService();

        $this->assertTrue($configService->hasPermission('toto1', 'toto_invalid'));
        $this->assertFalse($configService->hasPermission('toto10', 'toto_invalid'));
        $this->assertFalse($configService->hasPermission('toto20', 'toto_invalid'));
        $this->assertFalse($configService->hasPermission('toto_guest', 'toto_invalid'));

        $this->assertTrue($configService->hasPermission('toto1', 'p1'));
        $this->assertFalse($configService->hasPermission('toto10', 'p1'));
        $this->assertFalse($configService->hasPermission('toto20', 'p1'));
        $this->assertFalse($configService->hasPermission('toto_guest', 'p1'));

        $this->assertTrue($configService->hasPermission('toto1', 'p10'));
        $this->assertTrue($configService->hasPermission('toto10', 'p10'));
        $this->assertFalse($configService->hasPermission('toto20', 'p10'));
        $this->assertFalse($configService->hasPermission('toto_guest', 'p10'));


        $this->assertTrue($configService->hasPermission('toto1', 'p20'));
        $this->assertFalse($configService->hasPermission('toto10', 'p20'));
        $this->assertTrue($configService->hasPermission('toto20', 'p20'));
        $this->assertFalse($configService->hasPermission('toto_guest', 'p20'));
    }
}
