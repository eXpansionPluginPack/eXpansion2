<?php

namespace Tests\eXpansion\Bundle\LocalRecords\Services;

use eXpansion\Bundle\LocalRecords\Repository\RecordRepository;
use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Bundle\LocalRecords\Services\RecordHandlerFactory;

class RecordHandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $recordRepositoryMock;

    protected function setUp()
    {
        parent::setUp();

        $this->recordRepositoryMock = $this->getMockBuilder(RecordRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreate()
    {
        $factory = new RecordHandlerFactory($this->recordRepositoryMock, RecordHandler::ORDER_ASC, 10);

        $this->assertInstanceOf(RecordHandler::class, $factory->create());

    }
}
