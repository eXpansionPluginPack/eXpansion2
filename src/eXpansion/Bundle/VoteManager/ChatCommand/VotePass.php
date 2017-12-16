<?php

namespace eXpansion\Bundle\VoteManager\ChatCommand;


use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class VotePass extends AbstractAdminChatCommand
{

    /** @var VoteService */
    private $voteService;

    /** @var PlayerStorage */
    private $playerStorage;
    /**
     * @var ChatNotification
     */
    private $chatNotification;

    /**
     * VoteStart constructor.
     *
     * @param                  $command
     * @param array            $aliases
     * @param AdminGroups      $adminGroups
     * @param VoteService      $voteService
     * @param PlayerStorage    $playerStorage
     * @param ChatNotification $chatNotification
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroups,
        VoteService $voteService,
        PlayerStorage $playerStorage,
        ChatNotification $chatNotification
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);
        $this->voteService = $voteService;
        $this->playerStorage = $playerStorage;
        $this->chatNotification = $chatNotification;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {

        $level = $this->adminGroupsHelper->getLoginGroupLabel($login);
        $admin = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $this->chatNotification->sendMessage('expansion_votemanager.chat.admin_pass', null, ["%level%" => $level, "%admin%"
        => $admin]);
        $this->voteService->pass();

    }
}
