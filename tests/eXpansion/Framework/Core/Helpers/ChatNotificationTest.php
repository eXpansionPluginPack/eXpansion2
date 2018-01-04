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
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Translation\Translator;
use Tests\eXpansion\Framework\Core\SimpleTestCore;

class ChatNotificationTest extends SimpleTestCore
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConnection;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConsole;

    /** @var ChatNotification */
    protected $chatNotification;

    protected function setUp()
    {
        parent::setUp();

        $this->mockConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPlayerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockConsole = $this->getMockBuilder(Console::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->chatNotification = new ChatNotification(
            $this->mockConnection,
            new Translations(
                ['fr', 'de', 'fi', 'nl', 'en'],
                $this->container->getParameter('expansion.config.core_chat_color_codes'),
                $this->container->getParameter('expansion.config.core_chat_glyph_icons'),
                $this->container->get('translator')
            ),
            $this->mockPlayerStorage,
            $this->mockConsole
        );
    }


    public function testSendMessageLogin() {

        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $this->mockConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with('$z$s' . $colorCode . 'This is a test translation : Toto', 'toto');

        $player = new Player();
        $player->merge(['language' => 'en']);
        $this->mockPlayerStorage->method('getPlayerInfo')
            ->willReturn($player);

        $this->chatNotification->sendMessage('expansion_core.test_color', 'toto', ['%test%' => 'Toto']);
    }

    public function testSendMessageToAll() {

        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $translate = [
            0 => ['Lang' => 'fr', 'Text' => '$z$s' . $colorCode . 'Ceci est une trad de test : Toto'],
            1 => ['Lang' => 'de', 'Text' => '$z$s' . $colorCode . 'This is a test translation : Toto'],
        ];

        $this->mockConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with(new \PHPUnit_Framework_Constraint_ArraySubset($translate), null);

        $player = new Player();
        $player->merge(['language' => 'en']);
        $this->mockPlayerStorage->method('getPlayerInfo')
            ->willReturn($player);

        $this->chatNotification->sendMessage('expansion_core.test_color', null, ['%test%' => 'Toto']);
    }

    public function testSendMessageToList()
    {
        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $translate = [
            0 => ['Lang' => 'fr', 'Text' => '$z$s' . $colorCode . 'Ceci est une trad de test : Toto'],
            1 => ['Lang' => 'de', 'Text' => '$z$s' . $colorCode . 'This is a test translation : Toto'],
        ];

        $this->mockConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with(new \PHPUnit_Framework_Constraint_ArraySubset($translate), 'toto1,toto2');

        $this->chatNotification->sendMessage('expansion_core.test_color', ['toto1', 'toto2'], ['%test%' => 'Toto']);
    }

    public function testGetMessage()
    {
        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $translation = $this->chatNotification->getMessage('expansion_core.test_color', ['%test%' => 'Toto'], 'en');

        $this->assertEquals('$z$s' . $colorCode . 'This is a test translation : Toto', $translation);

    }
}
