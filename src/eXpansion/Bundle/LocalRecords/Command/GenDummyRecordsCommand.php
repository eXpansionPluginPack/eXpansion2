<?php

namespace eXpansion\Bundle\LocalRecords\Command;

use eXpansion\Bundle\LocalRecords\Model\Map\RecordTableMap;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Model\RecordQueryBuilder;
use eXpansion\Bundle\Maps\Model\Base\MapQuery;
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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class Records
 *
 * @author  reaby
 */
class GenDummyRecordsCommand extends ContainerAwareCommand
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
            ->setName('eXpansion:testing:generateTestData')
            // the short description shown while running "php bin/console list"
            ->setDescription('creates defined number of users with records to all maps.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command generates defined number of users and records to each map...');
        $this
            // configure an argument
            ->addArgument('count', InputArgument::REQUIRED, 'The number of users and records to generate.');

    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->console->init($output, null);

        $count = $input->getArgument('count');
        $this->console->writeln('Checking if there\'s enough players');

        $preAdd = $this->playerQueryBuilder->findDummy();

        if (count($preAdd) < $count) {

            $this->console->writeln("Generating missing players up to $count");
            $progress = new ProgressBar($output, $count);
            $progress->start();
            $progress->advance(count($preAdd));

            $con = Propel::getWriteConnection(PlayerTableMap::DATABASE_NAME);
            $con->beginTransaction();

            for ($x = count($preAdd); $x < $count; $x++) {
                $player = new Player();
                $login = "dummyplayer_$x";
                $player->setNickname($login);
                $player->setNicknameStripped($login);
                $player->setLogin($login);
                $player->setPath("World|Hello");
                $player->save();
                $progress->advance();
            }
            $con->commit();
            $progress->finish();
        }

        unset($preAdd);

        /** @var Player[] $players */
        $players = $this->playerQueryBuilder->findDummy();

        $this->console->writeln("Generating maximum of $count records for all maps on server");
        $maps = $this->mapQuery->getAllMaps();

        $con = Propel::getWriteConnection(RecordTableMap::DATABASE_NAME);
        $i = 1;
        foreach ($maps as $m => $map) {
            $records = $this->recordQueryBuilder->getMapRecords($map->getMapuid(), 1, "asc", 1000);


            $idsUsed = [];
            if (count($records) < $count) {
                foreach ($records as $record) {
                    $idsUsed[] = $record->getPlayerId();
                }
                $this->console->writeln("Generating records for map ".$i."/".count($maps)." -> ".$map->getName());
                $con->beginTransaction();

                $record = new Record();
                $record->setNblaps(1);
                $record->setNbFinish(1);
                $record->setMapuid($map->getMapuid());
                $record->setCreatedAt(new \DateTime());
                $record->setCheckpoints([]);
                $progress = new ProgressBar($output, $count - count($records));
                $progress->start();
                for ($x = (count($records) + 1); $x < $count; $x++) {
                    if (!in_array($x, $idsUsed)) {
                        $rec = clone $record;
                        $rec->setScore(mt_rand($map->getGoldtime(), $map->getGoldtime() * 5));
                        $rec->setPlayerId($players[$x-1]->getId());
                        $rec->save();
                        $progress->advance();
                    }
                }
                $con->commit();
                $progress->finish();
                $i++;
                RecordTableMap::clearInstancePool();
                RecordTableMap::clearRelatedInstancePool();

            } else {
                $this->console->writeln("skipping map, records alreaady set!");
            }
        }
        PlayerTableMap::clearInstancePool();
        PlayerTableMap::clearRelatedInstancePool();
    }
}
