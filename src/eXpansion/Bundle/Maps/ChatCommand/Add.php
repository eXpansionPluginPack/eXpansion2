<?php

namespace eXpansion\Bundle\Maps\ChatCommand;

use eXpansion\Bundle\AdminChat\ChatCommand\AbstractConnectionCommand;
use eXpansion\Bundle\Maps\Plugins\ManiaExchange;
use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Tests\eXpansion\Framework\AdminGroups\TestHelpers\AdminChatCommand;

/**
 *
 * @author  Reaby
 *
 */
class Add extends AdminChatCommand
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

    public function setMxPlugin(ManiaExchange $plugin)
    {
        $this->plugin = $plugin;
    }
}


