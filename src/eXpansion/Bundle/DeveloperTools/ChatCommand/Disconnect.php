<?php

namespace eXpansion\Bundle\DeveloperTools\ChatCommand;

use eXpansion\Bundle\Admin\Plugins\Gui\ScriptSettingsWindowFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use Maniaplanet\DedicatedServer\Connection;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Records
 *
 * @package eXpansion\Bundle\Admin\ChatCommand;
 * @author  reaby
 */
class Disconnect extends AbstractAdminChatCommand
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * Disconnect constructor.
     *
     * @param $command
     * @param $permission
     * @param array $aliases
     * @param Factory $factory
     * @param AdminGroups $adminGroups
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        Factory $factory,
        AdminGroups $adminGroups
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->factory->getConnection()->disconnectFakePlayer("*");
    }
}
