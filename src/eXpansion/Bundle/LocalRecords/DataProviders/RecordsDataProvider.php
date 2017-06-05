<?php

namespace eXpansion\Bundle\LocalRecords\DataProviders;

use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class RecordsDataProvider
 *
 * @package eXpansion\Bundle\LocalRecords\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordsDataProvider extends AbstractDataProvider
{
    public function onRecordsLoaded($params)
    {
        $this->dispatch('onLocalRecordsLoaded', [$params['records']]);
    }

    public function onFirstRecord($params)
    {
        $this->dispatch(
            'onLocalRecordsFirstRecord',
            [
                $params[RecordHandler::COL_RECORD],
                $params[RecordHandler::COL_RECORDS],
                $params[RecordHandler::COL_POS],
            ]
        );
    }

    public function onSameScore($params)
    {
        $this->dispatch(
            'onLocalRecordsSameScore',
            [
                $params[RecordHandler::COL_RECORD],
                $params[RecordHandler::COL_OLD_RECORD],
                $params[RecordHandler::COL_RECORDS],
            ]
        );
    }

    public function onBetterPosition($params)
    {
        $this->dispatch(
            'onLocalRecordsBetterPosition',
            [
                $params[RecordHandler::COL_RECORD],
                $params[RecordHandler::COL_OLD_RECORD],
                $params[RecordHandler::COL_RECORDS],
                $params[RecordHandler::COL_POS],
                $params[RecordHandler::COL_OLD_POS],
            ]
        );
    }

    public function onSamePosition($params)
    {
        $this->dispatch(
            'onLocalRecordsSamePosition',
            [
                $params[RecordHandler::COL_RECORD],
                $params[RecordHandler::COL_OLD_RECORD],
                $params[RecordHandler::COL_RECORDS],
                $params[RecordHandler::COL_POS],
            ]
        );
    }

}