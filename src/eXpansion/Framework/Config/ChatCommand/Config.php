<?php

namespace eXpansion\Framework\Config\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Config\Ui\Window\ConfigWindowFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Config
 *
 * @package eXpansion\Framework\Config\ChatCommand;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Config extends AbstractAdminChatCommand
{
    /** @var ConfigWindowFactory */
    protected $configWindow;

    /**
     * Config constructor.
     *
     * @param                     $command
     * @param string              $permission
     * @param array               $aliases
     * @param AdminGroups         $adminGroups
     * @param ConfigWindowFactory $configWindow
     */
    public function __construct(
        $command,
        string $permission,
        $aliases = [],
        AdminGroups $adminGroups,
        ConfigWindowFactory $configWindow

    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);
        $this->configWindow = $configWindow;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('path', InputArgument::REQUIRED, 'Path to the configs.')
        );
    }


    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->configWindow->setCurrentPath($input->getArgument('path'));
        $this->configWindow->create($login);
    }
}
