<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 11:02
 */

namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Psr\Log\NullLogger;
use Tests\eXpansion\Framework\Core\TestCore;

class ChatNotificationTest extends TestCore
{
    /** @var ChatNotification */
    protected $chatNotification;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockTranslations;

    protected function setUp()
    {
        parent::setUp();

        $this->mockPlayerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockPlayerStorage->method('getPlayerInfo')->willReturn(new Player());

        $this->mockTranslations = $this->getMockBuilder(Translations::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockTranslations->method('getTranslations')->willReturn([['Text' => "Test"]]);

        $this->chatNotification = new ChatNotification(
            $this->mockConnectionFactory,
            $this->mockTranslations,
            $this->mockPlayerStorage,
            $this->mockConsole,
            new NullLogger()
        );
    }


    public function testSendMessageLogin()
    {
        $dedicatedConnection = $this->mockConnection;
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage');

        $player = new Player();
        $player->merge(['language' => 'en']);

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', 'toto', ['%test%' => 'Toto']);
    }

    public function testSendMessageToAll()
    {
        $dedicatedConnection = $this->mockConnection;
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with(new \PHPUnit_Framework_Constraint_Not(new \PHPUnit_Framework_Constraint_IsEmpty()), null);

        $player = new Player();
        $player->merge(['language' => 'en']);

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', null, ['%test%' => 'Toto']);
    }

    public function testSendMessageToList()
    {
        $dedicatedConnection = $this->mockConnection;
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
        return $this->chatNotification;
    }
}
