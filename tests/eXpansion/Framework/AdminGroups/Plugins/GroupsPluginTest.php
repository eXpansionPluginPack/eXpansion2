<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 12:43
 */

namespace Tests\eXpansion\Framework\AdminGroups\Plugins;

use eXpansion\Framework\AdminGroups\Plugins\GroupsPlugin;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Tests\eXpansion\Framework\AdminGroups\TestAdminGroups;


/**
 * Class GroupsPluginTest
 *
 * @package Tests\eXpansion\Framework\AdminGroups\Plugins;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class GroupsPluginTest extends TestAdminGroups
{
    public function testPlayerConnect()
    {
        /** @var GroupsPlugin $plugin */
        $plugin = new GroupsPlugin(
            $this->getAdminGroupConfigurationService(),
            $this->container->get(Factory::class),
            $this->container->get(PlayerStorage::class)
        );

        $userGroupFactory = $this->getUserGroupFactory();

        $playerM = new Player();
        $playerM->merge(['login' => 'toto1']);
        $playerA = new Player();
        $playerA->merge(['login' => 'toto10']);
        $playerO = new Player();
        $playerO->merge(['login' => 'toto20']);
        $playerG = new Player();
        $playerG->merge(['login' => 'toto_guest']);

        $plugin->onPlayerConnect($playerM);
        $plugin->onPlayerConnect($playerA);
        $plugin->onPlayerConnect($playerO);
        $plugin->onPlayerConnect($playerG);

        $this->assertTrue($userGroupFactory->getGroup('admin:master_admin')->hasLogin('toto1'));
        $this->assertFalse($userGroupFactory->getGroup('admin:master_admin')->hasLogin('toto10'));
        $this->assertFalse($userGroupFactory->getGroup('admin:master_admin')->hasLogin('toto20'));
        $this->assertFalse($userGroupFactory->getGroup('admin:master_admin')->hasLogin('toto_guest'));

        $this->assertFalse($userGroupFactory->getGroup('admin:admin')->hasLogin('toto1'));
        $this->assertTrue($userGroupFactory->getGroup('admin:admin')->hasLogin('toto10'));
        $this->assertFalse($userGroupFactory->getGroup('admin:admin')->hasLogin('toto20'));
        $this->assertFalse($userGroupFactory->getGroup('admin:admin')->hasLogin('toto_guest'));

        $this->assertFalse($userGroupFactory->getGroup('admin:operator')->hasLogin('toto1'));
        $this->assertFalse($userGroupFactory->getGroup('admin:operator')->hasLogin('toto10'));
        $this->assertTrue($userGroupFactory->getGroup('admin:operator')->hasLogin('toto20'));
        $this->assertFalse($userGroupFactory->getGroup('admin:operator')->hasLogin('toto_guest'));

        $this->assertFalse($userGroupFactory->getGroup('admin:guest')->hasLogin('toto1'));
        $this->assertFalse($userGroupFactory->getGroup('admin:guest')->hasLogin('toto10'));
        $this->assertFalse($userGroupFactory->getGroup('admin:guest')->hasLogin('toto20'));
        $this->assertTrue($userGroupFactory->getGroup('admin:guest')->hasLogin('toto_guest'));
    }

    public function testDummyMethods()
    {
        /** @var GroupsPlugin $plugin */
        $plugin = $this->container->get(GroupsPlugin::class);

        $playerM = new Player();

        $plugin->onPlayerAlliesChanged($playerM, $playerM);
        $plugin->onPlayerInfoChanged($playerM, $playerM);
        $plugin->onPlayerDisconnect($playerM, '');
    }

    /**
     * @return Factory|object
     */
    protected function getUserGroupFactory()
    {
        /** @var Factory $group */
        return $this->container->get(Factory::class);
    }
}
