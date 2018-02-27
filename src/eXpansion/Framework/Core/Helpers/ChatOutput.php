<?php

namespace eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class ChatOutput can be used to redirect default symfony output to the maniaplanet in game chat.
 *
 * @package eXpansion\Framework\Core\Helpers;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class ChatOutput implements OutputInterface
{
    /** @var Factory */
    protected $factory;

    /** @var ChatNotificationInterface */
    protected $chatNotification;

    protected $login;

    /**
     * ChatOutput constructor.
     *
     * @param Factory $factory
     * @param ChatNotificationInterface $chatNotification
     */
    public function __construct(Factory $factory, ChatNotificationInterface $chatNotification)
    {
        $this->fa = $factory;
        $this->chatNotification = $chatNotification;
    }

    /**
     * @return ChatNotificationInterface
     */
    public function getChatNotification()
    {
        return $this->chatNotification;
    }

    /**
     * Login to send messages to.
     *
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @inheritdoc
     */
    public function write($messages, $newline = false, $options = 0)
    {
        $this->writeln($messages, $options);
    }

    /**
     * @inheritdoc
     */
    public function writeln($messages, $options = 0)
    {
        $this->factory->createConnection()->chatSendServerMessage(strip_tags($messages), $this->login);
    }

    /**
     * @inheritdoc
     */
    public function setVerbosity($level)
    {
        // Nothing to do.
    }

    /**
     * @inheritdoc
     */
    public function getVerbosity()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function isQuiet()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isVerbose()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isVeryVerbose()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isDebug()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function setDecorated($decorated)
    {
    }

    /**
     * @inheritdoc
     */
    public function isDecorated()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function setFormatter(OutputFormatterInterface $formatter)
    {
    }

    /**
     * @inheritdoc
     */
    public function getFormatter()
    {
        return null;
    }
}
