<?php

namespace eXpansion\Framework\AdminGroups\Model;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;


/**
 * Class AbstractAdminChatCommand
 *
 * @package eXpansion\Framework\AdminGroups\Model;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
abstract class AbstractAdminChatCommand extends AbstractChatCommand
{
    protected $adminGroupsHelper;

    protected $permission;

    /**
     * AbstractAdminChatCommand constructor.
     * @param $command
     * @param string $permission
     * @param array $aliases
     * @param AdminGroups $adminGroupsHelper
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroupsHelper
    ){
        $newAliases = [];
        $newAliases[] = "adm $command";
        foreach ($aliases as $alias) {
            $newAliases[] = "admin $alias";
            $newAliases[] = "adm $alias";
        }

        $command = "admin $command";

        parent::__construct($command, $newAliases);

        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->permission = $permission;
    }

    public function validate($login, $parameter)
    {
        if (!$this->adminGroupsHelper->hasPermission($login, $this->permission)) {
            return 'expansion_admingroups.chat_commands.no_permission';
        }

        return '';
    }
}