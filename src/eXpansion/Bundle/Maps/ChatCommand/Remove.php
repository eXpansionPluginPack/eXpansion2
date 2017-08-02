<?php

namespace eXpansion\Bundle\Maps\ChatCommand;

use eXpansion\Bundle\AdminChat\ChatCommand\AbstractConnectionCommand;
use eXpansion\Bundle\Maps\Plugins\ManiaExchange;
use eXpansion\Bundle\Maps\Plugins\Maps;
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
class Remove extends AdminChatCommand
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

    public function setMxPlugin(ManiaExchange $plugin)
    {
        $this->plugin = $plugin;
    }
}


