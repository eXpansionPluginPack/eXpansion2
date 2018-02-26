<?php

namespace eXpansion\Framework\GameCurrencyBundle\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use eXpansion\Framework\GameCurrencyBundle\Model\Gamecurrency;
use eXpansion\Framework\GameCurrencyBundle\Model\GamecurrencyQuery;


/**
 * This class defines the structure of the 'gamecurrency' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class GamecurrencyTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src\eXpansion\Framework\GameCurrencyBundle.Model.Map.GamecurrencyTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'expansion';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'gamecurrency';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\eXpansion\\Framework\\GameCurrencyBundle\\Model\\Gamecurrency';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'src\eXpansion\Framework\GameCurrencyBundle.Model.Gamecurrency';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'gamecurrency.id';

    /**
     * the column name for the senderLogin field
     */
    const COL_SENDERLOGIN = 'gamecurrency.senderLogin';

    /**
     * the column name for the receiverLogin field
     */
    const COL_RECEIVERLOGIN = 'gamecurrency.receiverLogin';

    /**
     * the column name for the transactionId field
     */
    const COL_TRANSACTIONID = 'gamecurrency.transactionId';

    /**
     * the column name for the billId field
     */
    const COL_BILLID = 'gamecurrency.billId';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'gamecurrency.amount';

    /**
     * the column name for the message field
     */
    const COL_MESSAGE = 'gamecurrency.message';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'gamecurrency.status';

    /**
     * the column name for the datetime field
     */
    const COL_DATETIME = 'gamecurrency.datetime';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Senderlogin', 'Receiverlogin', 'Transactionid', 'Billid', 'Amount', 'Message', 'Status', 'Datetime', ),
        self::TYPE_CAMELNAME     => array('id', 'senderlogin', 'receiverlogin', 'transactionid', 'billid', 'amount', 'message', 'status', 'datetime', ),
        self::TYPE_COLNAME       => array(GamecurrencyTableMap::COL_ID, GamecurrencyTableMap::COL_SENDERLOGIN, GamecurrencyTableMap::COL_RECEIVERLOGIN, GamecurrencyTableMap::COL_TRANSACTIONID, GamecurrencyTableMap::COL_BILLID, GamecurrencyTableMap::COL_AMOUNT, GamecurrencyTableMap::COL_MESSAGE, GamecurrencyTableMap::COL_STATUS, GamecurrencyTableMap::COL_DATETIME, ),
        self::TYPE_FIELDNAME     => array('id', 'senderLogin', 'receiverLogin', 'transactionId', 'billId', 'amount', 'message', 'status', 'datetime', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Senderlogin' => 1, 'Receiverlogin' => 2, 'Transactionid' => 3, 'Billid' => 4, 'Amount' => 5, 'Message' => 6, 'Status' => 7, 'Datetime' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'senderlogin' => 1, 'receiverlogin' => 2, 'transactionid' => 3, 'billid' => 4, 'amount' => 5, 'message' => 6, 'status' => 7, 'datetime' => 8, ),
        self::TYPE_COLNAME       => array(GamecurrencyTableMap::COL_ID => 0, GamecurrencyTableMap::COL_SENDERLOGIN => 1, GamecurrencyTableMap::COL_RECEIVERLOGIN => 2, GamecurrencyTableMap::COL_TRANSACTIONID => 3, GamecurrencyTableMap::COL_BILLID => 4, GamecurrencyTableMap::COL_AMOUNT => 5, GamecurrencyTableMap::COL_MESSAGE => 6, GamecurrencyTableMap::COL_STATUS => 7, GamecurrencyTableMap::COL_DATETIME => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'senderLogin' => 1, 'receiverLogin' => 2, 'transactionId' => 3, 'billId' => 4, 'amount' => 5, 'message' => 6, 'status' => 7, 'datetime' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('gamecurrency');
        $this->setPhpName('Gamecurrency');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\eXpansion\\Framework\\GameCurrencyBundle\\Model\\Gamecurrency');
        $this->setPackage('src\eXpansion\Framework\GameCurrencyBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('senderLogin', 'Senderlogin', 'VARCHAR', false, 255, null);
        $this->getColumn('senderLogin')->setPrimaryString(true);
        $this->addColumn('receiverLogin', 'Receiverlogin', 'VARCHAR', false, 255, null);
        $this->addColumn('transactionId', 'Transactionid', 'INTEGER', false, null, null);
        $this->addColumn('billId', 'Billid', 'INTEGER', false, null, null);
        $this->addColumn('amount', 'Amount', 'INTEGER', false, null, null);
        $this->addColumn('message', 'Message', 'VARCHAR', false, 255, null);
        $this->addColumn('status', 'Status', 'INTEGER', false, null, null);
        $this->addColumn('datetime', 'Datetime', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? GamecurrencyTableMap::CLASS_DEFAULT : GamecurrencyTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Gamecurrency object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = GamecurrencyTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = GamecurrencyTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + GamecurrencyTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = GamecurrencyTableMap::OM_CLASS;
            /** @var Gamecurrency $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            GamecurrencyTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = GamecurrencyTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = GamecurrencyTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Gamecurrency $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                GamecurrencyTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_ID);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_SENDERLOGIN);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_RECEIVERLOGIN);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_TRANSACTIONID);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_BILLID);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_MESSAGE);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_STATUS);
            $criteria->addSelectColumn(GamecurrencyTableMap::COL_DATETIME);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.senderLogin');
            $criteria->addSelectColumn($alias . '.receiverLogin');
            $criteria->addSelectColumn($alias . '.transactionId');
            $criteria->addSelectColumn($alias . '.billId');
            $criteria->addSelectColumn($alias . '.amount');
            $criteria->addSelectColumn($alias . '.message');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.datetime');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(GamecurrencyTableMap::DATABASE_NAME)->getTable(GamecurrencyTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(GamecurrencyTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(GamecurrencyTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new GamecurrencyTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Gamecurrency or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Gamecurrency object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GamecurrencyTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \eXpansion\Framework\GameCurrencyBundle\Model\Gamecurrency) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(GamecurrencyTableMap::DATABASE_NAME);
            $criteria->add(GamecurrencyTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = GamecurrencyQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            GamecurrencyTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                GamecurrencyTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the gamecurrency table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return GamecurrencyQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Gamecurrency or Criteria object.
     *
     * @param mixed               $criteria Criteria or Gamecurrency object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GamecurrencyTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Gamecurrency object
        }

        if ($criteria->containsKey(GamecurrencyTableMap::COL_ID) && $criteria->keyContainsValue(GamecurrencyTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.GamecurrencyTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = GamecurrencyQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // GamecurrencyTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
GamecurrencyTableMap::buildTableMap();
