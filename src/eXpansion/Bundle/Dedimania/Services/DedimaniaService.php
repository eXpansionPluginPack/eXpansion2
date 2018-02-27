<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 10.06
 */

namespace eXpansion\Bundle\Dedimania\Services;


use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;

class DedimaniaService
{
    /**
     * @var DedimaniaRecord[]
     */
    protected $dedimaniaRecords = [];
    protected $serverMaxRank = 15;

    protected $updatedRecords0 = [];

    /**
     * @return DedimaniaRecord[]
     */
    public function getDedimaniaRecords(): array
    {
        return $this->dedimaniaRecords;
    }

    /**
     * @param DedimaniaRecord[] $dedimaniaRecords
     */
    public function setDedimaniaRecords($dedimaniaRecords)
    {
        if (empty($dedimaniaRecords)) {
            $this->dedimaniaRecords = [];
        } else {
            $this->dedimaniaRecords = $dedimaniaRecords;
        }

    }

    /**
     * @return mixed
     */
    public function getServerMaxRank()
    {
        return $this->serverMaxRank;
    }

    /**
     * @param mixed $serverMaxRank
     */
    public function setServerMaxRank($serverMaxRank)
    {
        $this->serverMaxRank = $serverMaxRank;
    }

}