<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Psr\Log\LoggerInterface;
use Maniaplanet\DedicatedServer\Xmlrpc\Exception as DedicatedException;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ReasonUserCommand
 *
 * @author  Reaby
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class AdminVoteCommand extends AdminCommand
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * AdminVoteCommand constructor.
     *
     * @param $command
     * @param $permission
     * @param array $aliases
     * @param $functionName
     * @param AdminGroups $adminGroupsHelper
     * @param Factory $factory
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
     * @param LoggerInterface $logger
     * @param Time $timeHelper
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        $functionName,
        AdminGroups $adminGroupsHelper,
        Factory $factory,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        LoggerInterface $logger,
        Time $timeHelper,
        Dispatcher $dispatcher
    ) {
        parent::__construct(
            $command,
            $permission,
            $aliases = [],
            $functionName,
            $adminGroupsHelper,
            $factory,
            $chatNotification,
            $playerStorage,
            $logger,
            $timeHelper
        );

        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        parent::execute($login, $input);
        $player = $this->playerStorage->getPlayerInfo($login);
        $this->dispatcher->dispatch("votemanager.vote.cancelled", [$player, null, null]);

        $level = $this->adminGroupsHelper->getLoginGroupLabel($login);
        $admin = $player->getNickName();

        $logMessage = $this->chatNotification->getMessage('%adminLevel% %admin% cancels current vote.',
            ["%adminLevel%" => $level, "%admin%" => $admin]);
        $this->logger->info("[".$login."] ".TMString::trimStyles($logMessage));
    }
}
