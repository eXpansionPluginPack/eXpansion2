<?php

namespace eXpansion\Bundle\CustomChat\ChatCommand;

use eXpansion\Bundle\CustomChat\Plugins\CustomChat;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;


/**
 *
 * @package eXpansion\Bundle\CustomChat\ChatCommand;
 * @author reaby
 */
class ControlCommand extends AbstractAdminChatCommand
{
    /** @var string[] */
    protected $messages;

    /** @var ChatNotification */
    protected $chatNotification;

    /**
     * @var CustomChat
     */
    private $customChat;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * BasicEmote constructor.
     *
     * @param string $command The chat command
     * @param $permission
     * @param array $aliases
     * @param AdminGroups $adminGroups
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
     * @param CustomChat $customChat
     * @param LoggerInterface $logger
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroups,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        CustomChat $customChat,
        LoggerInterface $logger
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);
        $this->chatNotification = $chatNotification;
        $this->customChat = $customChat;
        $this->playerStorage = $playerStorage;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('parameter', InputArgument::REQUIRED, "status")
        );
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $group = $this->adminGroupsHelper->getLoginUserGroups($login)->getName();
        $groupName = $this->adminGroupsHelper->getGroupLabel($group);

        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();

        $arg = $input->getArgument('parameter');
        switch ($arg) {
            case "enable":
            case "on":
                $this->chatNotification->sendMessage("expansion_customchat.chat.enabled", null,
                    ['%admin%' => $groupName, '%nickname%' => $nickName]);
                $this->logger->info("[".$login."] Public chat is now enabled!");
                $this->customChat->setStatus(true);
                break;
            case "disable":
            case "off":
                $this->chatNotification->sendMessage("expansion_customchat.chat.disabled", null,
                    ['%admin%' => $groupName, '%nickname%' => $nickName]);
                $this->logger->info("[".$login."] Public chat is now disabled!");
                $this->customChat->setStatus(false);
                break;
            default:
                $this->chatNotification->sendMessage("expansion_customchat.chat.invalid", null, ['%arg' => $arg]);
                break;

        }

    }
}
