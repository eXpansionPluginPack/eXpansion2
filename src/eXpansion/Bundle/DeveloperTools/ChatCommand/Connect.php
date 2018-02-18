<?php

namespace eXpansion\Bundle\DeveloperTools\ChatCommand;

use eXpansion\Bundle\Acme\Plugins\Test;
use eXpansion\Bundle\DeveloperTools\Plugins\DevTools;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Storage\PlayerStorage;
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
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var DevTools
     */
    private $devToolsPlugin;

    /**
     * ScriptPanel constructor.
     *
     * @param                      $command
     * @param                      $permission
     * @param array                $aliases
     * @param AdminGroups          $adminGroups
     * @param DevTools             $devToolsPlugin
     * @param PlayerStorage        $playerStorage
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],

        AdminGroups $adminGroups,
        DevTools $devToolsPlugin,
        PlayerStorage $playerStorage
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->playerStorage = $playerStorage;
        $this->devToolsPlugin = $devToolsPlugin;
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
            $this->devToolsPlugin->connectQueue = $count - $online;
        }
    }
}
