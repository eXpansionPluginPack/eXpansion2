<?php

namespace Tests\eXpansion\Bundle\LocalRecords\DataProviders;

use eXpansion\Bundle\LocalRecords\DataProviders\MapRatingsDataProvider;
use eXpansion\Bundle\LocalRecords\Services\RecordHandler;

class RecordsDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  MapRatingsDataProvider */
    protected $provider;

    protected function setUp()
    {
        parent::setUp();

        $this->provider =  new MapRatingsDataProvider();
    }

    public function testFirstRecord()
    {
        $this->provider->onRecordsLoaded(
            [
                RecordHandler::COL_RECORDS => [],
            ]
        );
        $this->provider->onFirstRecord(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_POS => 1,
            ]
        );
        $this->provider->onSameScore(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_OLD_RECORD => [],
                RecordHandler::COL_RECORDS => [],
            ]
        );
        $this->provider->onBetterPosition(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_OLD_RECORD => [],
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_POS => 1,
                RecordHandler::COL_OLD_POS => 2,
            ]
        );
        $this->provider->onSamePosition(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_OLD_RECORD => [],
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_POS => 1,
            ]
        );
    }
}
