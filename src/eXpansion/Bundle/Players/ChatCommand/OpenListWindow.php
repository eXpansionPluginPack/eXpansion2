<?php


namespace eXpansion\Bundle\Players\ChatCommand;

use eXpansion\Bundle\Players\Plugins\Gui\AbstractListWindow;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Players
 *
 * @package eXpansion\Bundle\Players\ChatCommand;
 * @author  reaby
 */
class OpenListWindow extends AbstractAdminChatCommand
{

    /**
     * @var AbstractListWindow
     */
    private $listWindow;

    /**
     * Records constructor.
     *
     * @param               $command
     * @param               $permission
     * @param array         $aliases
     * @param AdminGroups   $adminGroups
     * @param AbstractListWindow    $listWindow
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroups,
        AbstractListWindow $listWindow
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->listWindow = $listWindow;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->listWindow->create($login);
    }
}
