<?php

namespace eXpansion\Bundle\Maps\ChatCommand;

use eXpansion\Bundle\Maps\Plugins\ManiaExchange;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 *
 * @author  Reaby
 *
 */
class Add extends AbstractAdminChatCommand
{

    /** @var  ManiaExchange */
    protected $plugin;

    public function __construct(
        $command,
        $permission,
        $aliases = [],
        AdminGroups $adminGroupsHelper,
        ManiaExchange $plugin
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroupsHelper);
        $this->plugin = $plugin;
    }


    public function getDescription()
    {
        return 'expansion_mx.command.add.help';
    }

    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('mxid', InputArgument::REQUIRED, 'expansion_mx.command.add.mxid')
        );

        $this->inputDefinition->addArgument(
            new InputArgument('site', InputArgument::OPTIONAL, 'expansion_mx.command.add.site')
        );


    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $site = $input->getArgument('site');
        if (!$input->getArgument('site')) {
            $site = null;
        }

        $id = $input->getArgument('mxid');

        $this->plugin->addMap($login, $id, $site);
    }

}


