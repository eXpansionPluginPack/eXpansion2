<?php


namespace eXpansion\Bundle\Players\ChatCommand;

use eXpansion\Bundle\Players\Plugins\Gui\ListWindow;
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
    private $listMode;
    /**
     * @var ListWindow
     */
    private $listWindow;

    /**
     * Records constructor.
     *
     * @param               $command
     * @param               $permission
     * @param array         $aliases
     * @param AdminGroups   $adminGroups
     * @param ListWindow    $listWindow
     * @param               $listMode
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroups,
        ListWindow $listWindow,
        $listMode
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->listMode = $listMode;
        $this->listWindow = $listWindow;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->listWindow->setMode($this->listMode);
        $this->listWindow->create($login);
    }
}
