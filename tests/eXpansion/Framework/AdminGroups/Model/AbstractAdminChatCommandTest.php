<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 12:11
 */

namespace Tests\eXpansion\Framework\AdminGroups\Model;

use Tests\eXpansion\Framework\AdminGroups\TestAdminGroups;
use Tests\eXpansion\Framework\AdminGroups\TestHelpers\AdminChatCommand;

class AbstractAdminChatCommandTest extends TestAdminGroups
{
    public function testChatCommand()
    {
        $adminHelper = $this->container->get('expansion.framework.admin_groups.helpers.groups');

        $chat = new AdminChatCommand('restart', 'p10', ['res'], $adminHelper);

        $this->assertEquals('admin restart', $chat->getCommand());

        $this->assertEmpty(array_diff(['adm restart', 'adm res', 'admin res'], $chat->getAliases()));

        $this->assertEmpty($chat->validate('toto1', ''));
        $this->assertEmpty($chat->validate('toto10', ''));
        $this->assertNotEmpty($chat->validate('toto20', ''));
        $this->assertNotEmpty($chat->validate('toto_no', ''));
    }
}
