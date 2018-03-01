<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 10:51
 */

namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\ChatOutput;
use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use Tests\eXpansion\Framework\Core\TestCore;

class ChatOutputTest extends ChatNotificationTest
{
    public function testGetChatNotification()
    {
        $chatOutput = new ChatOutput($this->mockConnectionFactory, $this->chatNotification);

        $this->assertInstanceOf(ChatNotificationInterface::class, $chatOutput->getChatNotification());
    }

    public function testWrite()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $dedicatedConnection */
        $dedicatedConnection = $this->mockConnection;
        $dedicatedConnection->expects($this->exactly(2))
            ->method('chatSendServerMessage')
            ->with("Test message stripped", 'toto');

        $chatOutput = new ChatOutput($this->mockConnectionFactory, $this->chatNotification);
        $chatOutput->setLogin('toto');
        $chatOutput->write('Test message <p>stripped</p>');
        $chatOutput->writeln('Test message <p>stripped</p>');
    }

    public function testMockMethods()
    {
        $chatOutput = new ChatOutput($this->mockConnectionFactory, $this->chatNotification);

        $chatOutput->setVerbosity(0);
        $chatOutput->getVerbosity();
        $chatOutput->isQuiet();
        $chatOutput->isVerbose();
        $chatOutput->isVeryVerbose();
        $chatOutput->isDebug();
        $chatOutput->setDecorated(false);
        $chatOutput->isDecorated();
        $chatOutput->getFormatter();
    }
}
