<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid;

use eXpansion\Framework\Core\Model\Data\FilterInterface;


/**
 * Class DataCollectionFactory
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class DataCollectionFactory
{
    /** @var FilterInterface  */
    protected $filterHelper;

    /** @var string  */
    protected $collectionClass;

    /**
     * DataCollectionFactory constructor.
     *
     * @param FilterInterface $filterHelper
     * @param string $collectionClass
     */
    public function __construct(FilterInterface $filterHelper, $collectionClass)
    {
        $this->filterHelper = $filterHelper;
        $this->collectionClass = $collectionClass;
    }

    /**
     * @param $data
     *
     * @return DataCollectionInterface
     */
    public function create($data)
    {
        $class = $this->collectionClass;

        return new $class($data, $this->filterHelper);
    }
}
