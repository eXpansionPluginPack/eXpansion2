<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 11:02
 */

namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Output\NullOutput;
use Tests\eXpansion\Framework\Core\TestCore;

class ChatNotificationTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $this->container->get(Console::class)->init(new NullOutput(), $this->container->get(Dispatcher::class));
    }


    public function testSendMessageLogin()
    {
        $dedicatedConnection = $this->container->get('expansion.service.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage');

        $player = new Player();
        $player->merge(['language' => 'en']);
        $this->container->set(PlayerStorage::class, $this->getMockPlayerStorage($player));

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', 'toto', ['%test%' => 'Toto']);
    }

    public function testSendMessageToAll()
    {
        $dedicatedConnection = $this->container->get('expansion.service.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with(new \PHPUnit_Framework_Constraint_Not(new \PHPUnit_Framework_Constraint_IsEmpty()), null);

        $player = new Player();
        $player->merge(['language' => 'en']);
        $this->container->set(PlayerStorage::class, $this->getMockPlayerStorage($player));

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', null, ['%test%' => 'Toto']);
    }

    public function testSendMessageToList()
    {
        $dedicatedConnection = $this->container->get('expansion.service.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with(new \PHPUnit_Framework_Constraint_Not(new \PHPUnit_Framework_Constraint_IsEmpty()), 'toto1,toto2');

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', ['toto1', 'toto2'], ['%test%' => 'Toto']);
    }

    /**
     * @return ChatNotification
     */
    protected function getChatNotificationHelper()
    {
        return $this->container->get(ChatNotification::class);
    }
}
