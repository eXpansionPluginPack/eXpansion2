<?php


namespace eXpansion\Bundle\Maps\ChatCommand;

use eXpansion\Bundle\Maps\Plugins\Gui\ManiaExchangeWindowFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class MxSearch extends AbstractAdminChatCommand
{
    /** @var ManiaExchangeWindowFactory */
    protected $maniaExchangeWindowFactory;
    /**
     * @var ManiaExchangeWindowFactory
     */
    private $exchangeWindowFactory;

    /**
     * MxSearch constructor.
     * @param $command
     * @param string $permission
     * @param array $aliases
     * @param AdminGroups $adminGroupsHelper
     * @param ManiaExchangeWindowFactory $exchangeWindowFactory
     */
    public function __construct(
        $command,
        $permission,
        $aliases = [],
        AdminGroups $adminGroupsHelper,
        ManiaExchangeWindowFactory $exchangeWindowFactory
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroupsHelper);

        $this->exchangeWindowFactory = $exchangeWindowFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->exchangeWindowFactory->create($login);
    }
}
