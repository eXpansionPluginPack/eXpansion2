<?php

namespace eXpansion\Bundle\LocalRecords\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class RecordRepository
 *
 * @package eXpansion\Bundle\LocalRecords\Repository;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordRepository extends EntityRepository
{
    /**
     * Mass save records.
     *
     * @param $records
     */
    public function massSave($records)
    {
        if (!empty($records)) {
            foreach ($records as $record) {
                $this->getEntityManager()->persist($record);
            }

            $this->getEntityManager()->flush();
        }
    }
}
