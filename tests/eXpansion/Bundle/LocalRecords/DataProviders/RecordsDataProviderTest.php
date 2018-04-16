<?php

namespace Tests\eXpansion\Bundle\LocalRecords\DataProviders;

use eXpansion\Bundle\LocalRecords\DataProviders\RecordsDataProvider;
use eXpansion\Bundle\LocalRecords\Plugins\BaseRecords;
use eXpansion\Bundle\LocalRecords\Services\RecordHandler;

class RecordsDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  RecordsDataProvider */
    protected $provider;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlugin;

    protected function setUp()
    {
        parent::setUp();

        $this->provider =  new RecordsDataProvider();
        $this->mockPlugin = $this->getMockBuilder(BaseRecords::class)->disableOriginalConstructor()->getMock();
    }

    public function testFirstRecord()
    {
        $this->provider->onRecordsLoaded(
            [
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_PLUGIN => $this->mockPlugin,
            ]
        );
        $this->provider->onFirstRecord(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_POS => 1,
                RecordHandler::COL_PLUGIN => $this->mockPlugin,
            ]
        );
        $this->provider->onSameScore(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_OLD_RECORD => [],
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_PLUGIN => $this->mockPlugin,
            ]
        );
        $this->provider->onBetterPosition(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_OLD_RECORD => [],
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_POS => 1,
                RecordHandler::COL_OLD_POS => 2,
                RecordHandler::COL_PLUGIN => $this->mockPlugin,
            ]
        );
        $this->provider->onSamePosition(
            [
                RecordHandler::COL_RECORD => [],
                RecordHandler::COL_OLD_RECORD => [],
                RecordHandler::COL_RECORDS => [],
                RecordHandler::COL_POS => 1,
                RecordHandler::COL_PLUGIN => $this->mockPlugin,
            ]
        );
    }
}
