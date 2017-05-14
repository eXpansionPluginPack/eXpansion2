<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 13:17
 */

namespace Tests\eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\UserGroups\AllPlayers;
use Tests\eXpansion\Framework\Core\TestCore;


/**
 * Class ManialinkTest
 *
 * @package Tests\eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ManialinkTest extends TestCore
{
    public function testManialinl()
    {
        $group = $this->getSpectatorsGroup();

        $manialink = new Manialink($group, 'test', 0,0,0,0);

        $this->assertEquals($group, $manialink->getUserGroup());
        $this->assertNotNull($manialink->getId());

        $this->assertInstanceOf(\SimpleXMLElement::class, simplexml_load_string($manialink->getXml()));
    }

    /**
     * @return Group
     */
    protected function getSpectatorsGroup()
    {
        return $this->container->get('expansion.framework.core.user_groups.spectators');
    }
}