<?php

namespace eXpansion\Bundle\Acme\ChatCommand;

use eXpansion\Bundle\Admin\Plugins\Gui\ScriptSettingsWindowFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Records
 *
 * @package eXpansion\Bundle\Admin\ChatCommand;
 * @author  reaby
 */
class Connect extends AbstractAdminChatCommand
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * ScriptPanel constructor.
     *
     * @param                      $command
     * @param                      $permission
     * @param array $aliases
     * @param Connection $connection
     * @param AdminGroups $adminGroups
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        Connection $connection,
        AdminGroups $adminGroups
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {

        for ($x=0; $x < 200; $x++) {
            $this->connection->connectFakePlayer(false);
        }

    }
}
