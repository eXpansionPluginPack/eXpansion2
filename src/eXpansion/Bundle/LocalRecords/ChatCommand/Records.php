<?php


namespace eXpansion\Bundle\LocalRecords\ChatCommand;
use eXpansion\Bundle\LocalRecords\Plugins\Gui\RecordsWindowFactory;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Records extends AbstractChatCommand
{
    /** @var RecordsWindowFactory */
    protected $recordsWindowFactory;

    /**
     * Records constructor.
     *
     * @param                      $command
     * @param array                $aliases
     * @param RecordsWindowFactory $recordsWindowFactory
     */
    public function __construct($command, array $aliases = [], RecordsWindowFactory $recordsWindowFactory)
    {
        parent::__construct($command, $aliases);

        $this->recordsWindowFactory = $recordsWindowFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->recordsWindowFactory->create($login);
    }
}