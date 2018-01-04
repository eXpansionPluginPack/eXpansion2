<?php

namespace eXpansion\Bundle\Acme\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class DumpMemory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 Smile
 * @package eXpansion\Bundle\Acme\ChatCommand
 */
class DumpMemory extends AbstractAdminChatCommand
{
    /**
     * @var ChatNotification
     */
    protected $chatNotification;

    /**
     * DumpMemory constructor.
     *
     * @param $command
     * @param string $permission
     * @param array $aliases
     * @param AdminGroups $adminGroupsHelper
     * @param ChatNotification $chatNotification
     */
    public function __construct(
        $command,
        string $permission,
        $aliases = [],
        AdminGroups $adminGroupsHelper,
        ChatNotification $chatNotification
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroupsHelper);

        $this->chatNotification = $chatNotification;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        if (function_exists('meminfo_dump')) {
            $date = date(DATE_ISO8601);
            meminfo_dump(fopen("eXpansion-mem-dump-$date.json", 'w'));
        } else {
            $this->chatNotification->sendMessage('meminfo is not installed!', $login);
        }
    }
}