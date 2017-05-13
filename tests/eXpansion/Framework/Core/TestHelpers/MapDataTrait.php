<?php


namespace Tests\eXpansion\Framework\Core\TestHelpers;


use Maniaplanet\DedicatedServer\Structures\Map;

trait MapDataTrait
{
    /**
     * @param string $uid
     *
     * @return Map
     */
    protected function getAMap($uid)
    {
        $map = new Map();
        $map->uId = $uid;

        return $map;
    }

    /**
     * @param $size
     * @param int $start
     *
     * @return Map[]
     */
    public function getMaps($size, $start = 0)
    {
        $maps = [];
        for ($i = $start; $i < $size + $start; $i++) {
            $maps['TestMap-' . $i] = $this->getAMap('TestMap-' . $i);
        }

        return $maps;
    }
}