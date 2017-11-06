<?php


namespace eXpansion\Bundle\Admin\ChatCommand;

use eXpansion\Bundle\Admin\Plugins\Gui\ScriptSettingsWindowFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\Admin\ChatCommand;
 * @author  reaby
 */
class ScriptPanel extends AbstractAdminChatCommand
{
    /** @var ScriptSettingsWindowFactory */
    protected $scriptSettingsWindowFactory;

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
        ScriptSettingsWindowFactory $scriptSettingsWindowFactory,
        AdminGroups $adminGroups
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);
        $this->scriptSettingsWindowFactory = $scriptSettingsWindowFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->scriptSettingsWindowFactory->create($login);
    }
}
