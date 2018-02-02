<?php

namespace eXpansion\Bundle\Maps\ChatCommand;

use eXpansion\Bundle\Maps\Plugins\ManiaExchange;
use eXpansion\Bundle\Maps\Plugins\Maps;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 *
 * @author  Reaby
 *
 */
class Remove extends AbstractAdminChatCommand
{

    /** @var  Maps */
    protected $plugin;

    public function __construct(
        $command,
        $permission,
        $aliases = [],
        AdminGroups $adminGroupsHelper,
        Maps $plugin
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroupsHelper);
        $this->plugin = $plugin;
    }


    public function getDescription()
    {
        return 'expansion_mx.command.remove.description';
    }

    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('index', InputArgument::REQUIRED, 'expansion_mx.command.remove.index')
        );

    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $index = $input->getArgument('index');
        $this->plugin->removeMap($login, $index);
    }

}


