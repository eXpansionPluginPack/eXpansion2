<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 12:20
 */

namespace Tests\eXpansion\Framework\AdminGroups;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Services\AdminGroupConfiguration;
use eXpansion\Framework\Config\Model\BooleanConfig;
use eXpansion\Framework\Config\Model\TextConfig;
use eXpansion\Framework\Config\Model\TextListConfig;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use PHPUnit\Framework\TestCase;
use Tests\eXpansion\Framework\Core\TestCore;


/**
 * Class TestAdminGroups
 *
 * @package Tests\eXpansion\Framework\AdminGroups;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class TestAdminGroups extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConfigManager;

    /** @var Factory */
    protected $userGroupFactory;

    /** @var AdminGroupConfiguration */
    protected $adminGroupConfiguration;

    /** @var AdminGroups */
    protected $adminGroupsHelper;

    protected function setUp()
    {
        parent::setUp();

        $this->mockConfigManager = $this->getMockBuilder(ConfigManagerInterface::class)->getMock();
        $this->mockConfigManager
            ->method('getConfigDefinitionTree')
            ->willReturn(new AssociativeArray(['path' => $this->getAdminGroupConfiguration()]));
        $this->userGroupFactory = new Factory(Group::class, $this->getMockBuilder(DispatcherInterface::class)->getMock());

        $this->adminGroupConfiguration = new AdminGroupConfiguration($this->mockConfigManager, 'path');
        $this->adminGroupsHelper = new AdminGroups($this->adminGroupConfiguration, $this->userGroupFactory);
    }

    public function getAdminGroupConfigurationService()
    {
        return $this->adminGroupConfiguration;
    }

    public function getAdminGroupConfiguration()
    {
        $configs = [
            'master_admin' => [
                'logins' => new TextListConfig('', '', '', '', ['toto1', 'toto2']),
                'label' => new TextConfig('', '', '', '','Master Admin')
            ],
            'admin' => [
                'logins' => new TextListConfig('', '', '', '', ['toto10', 'toto11']),
                'label' => new TextConfig('', '', '', '', 'Admin'),
                'perm_p10' => new BooleanConfig('', '', '', '', 1),
                'perm_p11' => new BooleanConfig('', '', '', '', 1),
            ],
            'operator' => [
                'logins' => new TextListConfig('', '', '', '', ['toto20', 'toto21']),
                'label' => new TextConfig('', '', '', '', 'Operator'),
                'perm_p20' => new BooleanConfig('', '', '', '', 1),
                'perm_p21' => new BooleanConfig('', '', '', '', 1),
            ],
            'empty' => [
                'logins' => new TextListConfig('', '', '', '', []),
                'label' => 'Empty',
                'perm_p20' => new BooleanConfig('', '', '', '', 1),
            ]
        ];

        foreach ($configs as $config) {
            foreach ($config as $conf) {
                if (!is_string($conf)) {
                    $conf->setConfigManager($this->mockConfigManager);
                }
            }
        }

        return $configs;
    }


    /**
     * @return \eXpansion\Framework\AdminGroups\Helpers\AdminGroups|object
     */
    protected function getAdminGroupHelper()
    {
        return $this->adminGroupsHelper;
    }
}
