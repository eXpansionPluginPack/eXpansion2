<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 12:20
 */

namespace Tests\eXpansion\Framework\AdminGroups;

use eXpansion\Framework\AdminGroups\Services\AdminGroupConfiguration;
use Tests\eXpansion\Framework\Core\TestCore;


/**
 * Class TestAdminGroups
 *
 * @package Tests\eXpansion\Framework\AdminGroups;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class TestAdminGroups extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $this->container->set(
            'expansion.framework.admin_groups.services.admin_group_configuration',
            $this->getAdminGroupConfigurationService()
        );
    }

    public function getAdminGroupConfigurationService()
    {
        return new AdminGroupConfiguration($this->getAdminGroupConfiguration());
    }

    public function getAdminGroupConfiguration()
    {
        return [
            'master_admin' => [
                'logins' => ['toto1', 'toto2'],
            ],
            'admin' => [
                'logins' => ['toto10', 'toto11'],
                'permissions' => ['p10', 'p11'],
            ],
            'operator' => [
                'logins' => ['toto20', 'toto21'],
                'permissions' => ['p20', 'p21'],
            ],
            'empty' => [
                'logins' => [],
                'permissions' => ['p20'],
            ]
        ];
    }


    /**
     * @return \eXpansion\Framework\AdminGroups\Helpers\AdminGroups|object
     */
    protected function getAdminGroupHelper()
    {
        return $this->container->get('expansion.helper.admingroups');
    }
}
