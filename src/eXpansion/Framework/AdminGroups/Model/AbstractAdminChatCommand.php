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

    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        $parametersAsArray = true,
        AdminGroups $adminGroupsHelper
    ){
        parent::__construct($command, $aliases, $parametersAsArray);

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