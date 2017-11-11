<?php


namespace eXpansion\Bundle\Admin\ChatCommand;

use eXpansion\Bundle\Admin\Plugins\Gui\ScriptSettingsWindowFactory;
use eXpansion\Bundle\Admin\Plugins\Gui\ServerSettingsWindowFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\Admin\ChatCommand;
 * @author  reaby
 */
class ServerPanel extends AbstractAdminChatCommand
{
    /** @var ServerSettingsWindowFactory*/
    protected $serverSettingsWindowFactory;

    /**
     * ScriptPanel constructor.
     *
     * @param                      $command
     * @param                      $permission
     * @param array $aliases
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        ServerSettingsWindowFactory $serverSettingsWindowFactory,
        AdminGroups $adminGroups
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);
        $this->serverSettingsWindowFactory = $serverSettingsWindowFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->serverSettingsWindowFactory->create($login);
    }
}
