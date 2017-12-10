<?php

namespace eXpansion\Bundle\Acme\ChatCommand;

use eXpansion\Bundle\Acme\Plugins\Test;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Console\Input\InputArgument;
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
     * @var Test
     */
    private $testPlugin;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * ScriptPanel constructor.
     *
     * @param                      $command
     * @param                      $permission
     * @param array                $aliases
     * @param Connection           $connection
     * @param AdminGroups          $adminGroups
     * @param Test                 $testPlugin
     * @param PlayerStorage        $playerStorage
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        Connection $connection,
        AdminGroups $adminGroups,
        Test $testPlugin,
        PlayerStorage $playerStorage
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->connection = $connection;
        $this->testPlugin = $testPlugin;
        $this->playerStorage = $playerStorage;
    }

    public function configure()
    {
        parent::configure();
        $this->inputDefinition->addArgument(
            new InputArgument('count', InputArgument::REQUIRED, "count of fake players to connect")
        );
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $count = $input->getArgument("count");
        $online = count($this->playerStorage->getOnline());
        if ($online <= $count) {
            $this->testPlugin->connectQueue = $count - $online;
        }
    }
}
