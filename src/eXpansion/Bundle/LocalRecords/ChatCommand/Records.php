<?php


namespace eXpansion\Bundle\LocalRecords\ChatCommand;
use eXpansion\Bundle\LocalRecords\Plugins\AllRecords;
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

    /** @var AllRecords */
    protected $allRecords;

    /**
     * Records constructor.
     *
     * @param $command
     * @param array $aliases
     * @param AllRecords $allRecords
     * @param RecordsWindowFactory $recordsWindowFactory
     */
    public function __construct(
        $command,
        array $aliases = [],
        AllRecords $allRecords,
        RecordsWindowFactory $recordsWindowFactory
    ) {
        parent::__construct($command, $aliases);

        $this->recordsWindowFactory = $recordsWindowFactory;
        $this->allRecords = $allRecords;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->recordsWindowFactory->setRecordsData($this->allRecords->getMapRecords());
        $this->recordsWindowFactory->create($login);
    }
}
