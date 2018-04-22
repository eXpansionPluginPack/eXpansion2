<?php

namespace eXpansion\Framework\Core\ChatCommand;

use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class UpdateExpansion
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\Core\ChatCommand
 */
class UpdateExpansion extends AbstractAdminChatCommand
{
    /** @var ChatNotification */
    protected $chatNotificaiton;

    /**
     * UpdateExpansion constructor.
     *
     * @param $command
     * @param $permission
     * @param array $aliases
     * @param AdminGroups $adminGroups
     * @param ChatNotification $chatNotification
     */
    function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroups,
        ChatNotification $chatNotification
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);
    }

    public function getDescription()
    {
        return "expansion_core.chat_commands.stop.help";
    }

    public function getHelp()
    {
        return "expansion_core.chat_commands.stop.help";
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $process = new Process("php bin/console eXpansion:update $login &");
        $process->start();
    }
}