<?php

namespace eXpansion\Bundle\LocalRecords\Command;

use eXpansion\Bundle\LocalRecords\Model\Map\RecordTableMap;
use eXpansion\Bundle\LocalRecords\Model\RecordQueryBuilder;
use eXpansion\Bundle\Maps\Model\MapQueryBuilder;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\PlayersBundle\Model\Map\PlayerTableMap;
use eXpansion\Framework\PlayersBundle\Model\Player;
use eXpansion\Framework\PlayersBundle\Model\PlayerQueryBuilder;
use Maniaplanet\DedicatedServer\Connection;
use Propel\Runtime\Propel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class Records
 *
 * @author  reaby
 */
class DelDummyRecordsCommand extends ContainerAwareCommand
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var PlayerQueryBuilder
     */
    private $playerQueryBuilder;
    /**
     * @var RecordQueryBuilder
     */
    private $recordQueryBuilder;
    /**
     * @var Console
     */
    private $console;
    /**
     * @var MapQueryBuilder
     */
    private $mapQuery;

    /**
     * ScriptPanel constructor.
     *
     * @param Connection         $connection
     * @param MapStorage         $mapStorage
     * @param PlayerQueryBuilder $playerQueryBuilder
     * @param RecordQueryBuilder $recordQueryBuilder
     * @param MapQueryBuilder    $mapQuery
     * @param Console            $console
     */
    public function __construct(
        Connection $connection,
        MapStorage $mapStorage,
        PlayerQueryBuilder $playerQueryBuilder,
        RecordQueryBuilder $recordQueryBuilder,
        MapQueryBuilder $mapQuery,
        Console $console
    ) {
        parent::__construct();
        $this->mapStorage = $mapStorage;
        $this->playerQueryBuilder = $playerQueryBuilder;
        $this->recordQueryBuilder = $recordQueryBuilder;
        $this->mapQuery = $mapQuery;
        $this->console = $console;
        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('eXpansion:testing:deleteTestData')
            // the short description shown while running "php bin/console list"
            ->setDescription('creates defined number of users with records to all maps.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command generates defined number of users and records to each map...');
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->console->init($output, null);


        $i = 1;

        $con = Propel::getWriteConnection(PlayerTableMap::DATABASE_NAME);
        $con->beginTransaction();

        $players = $this->playerQueryBuilder->findDummy();

        $progress = new ProgressBar($output, count($players));
        $progress->start();
        /** @var Player $player */
        foreach ($players as $player) {
            $player->delete();
            $progress->advance();
        }
        $con->commit();
        $progress->finish();


        $this->console->writeln("");
        $this->console->writeln("Removing dummy records... please wait...");
        $maps = $this->mapQuery->getAllMaps();
        $count = count($maps);

        $i = 1;
        foreach ($maps as $m => $map) {
            $records = $this->recordQueryBuilder->getMapRecords($map->getMapuid(), 1, "asc", 2000);

            if (count($records) > 0) {
                $this->console->writeln("Deleting dummy records from map ".$i."/".count($maps)." -> ".$map->getName());
                $con = Propel::getWriteConnection(RecordTableMap::DATABASE_NAME);
                $con->beginTransaction();
                $progress = new ProgressBar($output, count($records));
                $progress->start();
                foreach ($records as $record) {
                    if (strstr($record->getPlayer()->getLogin(), "dummyplayer_")) {
                        $record->delete();
                        $progress->advance();
                    }
                }
                $con->commit();
                $progress->finish();
                $this->console->writeln("");
            } else {
                $this->console->writeln("skipping map ".$i."/".count($maps)." -> ".$map->getName());
            }
            RecordTableMap::clearInstancePool();
            RecordTableMap::clearRelatedInstancePool();
            PlayerTableMap::clearInstancePool();
            PlayerTableMap::clearRelatedInstancePool();
            RecordTableMap::clearInstancePool();
            RecordTableMap::clearRelatedInstancePool();
            $i++;
        }
    }

}
