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
    /** @var AdminGroups */
    protected $adminGroupsHelper;

    /**
     * @var string
     */
    protected $permission;

    /**
     * AbstractAdminChatCommand constructor.
     *
     * @param $command
     * @param string $permission
     * @param string[] $aliases
     * @param AdminGroups $adminGroupsHelper
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroupsHelper
    ) {
        $newAliases = [];
        $newAliases[] = "adm $command";
        foreach ($aliases as $alias) {
            $newAliases[] = "admin $alias";
            $newAliases[] = "adm $alias";
            $newAliases[] = "/".$alias;
        }

        $command = "admin $command";

        parent::__construct($command, $newAliases);

        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->permission = $permission;
    }

    /**
     * check permissions for this chat command
     *
     * @param $login
     * @param $parameter
     * @return string
     */
    public function validate($login, $parameter)
    {
        if (!$this->hasPermission($login)) {
            return 'expansion_admingroups.chat_commands.no_permission';
        }

        return '';
    }

    /**
     * Check of a player has permission to use chat command.
     *
     * @param string $login
     *
     * @return bool
     */
    public function hasPermission($login) {
        return $this->adminGroupsHelper->hasPermission($login, $this->permission);
    }
}
