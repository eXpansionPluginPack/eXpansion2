<?php

namespace eXpansion\Bundle\Maps\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use eXpansion\Bundle\Maps\Model\Map;
use eXpansion\Bundle\Maps\Model\MapQuery;


/**
 * This class defines the structure of the 'map' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MapTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src\eXpansion\Bundle\Maps.Model.Map.MapTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'expansion';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'map';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\eXpansion\\Bundle\\Maps\\Model\\Map';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'src\eXpansion\Bundle\Maps.Model.Map';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 19;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 19;

    /**
     * the column name for the id field
     */
    const COL_ID = 'map.id';

    /**
     * the column name for the mapUid field
     */
    const COL_MAPUID = 'map.mapUid';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'map.name';

    /**
     * the column name for the fileName field
     */
    const COL_FILENAME = 'map.fileName';

    /**
     * the column name for the author field
     */
    const COL_AUTHOR = 'map.author';

    /**
     * the column name for the environment field
     */
    const COL_ENVIRONMENT = 'map.environment';

    /**
     * the column name for the mood field
     */
    const COL_MOOD = 'map.mood';

    /**
     * the column name for the bronzeTime field
     */
    const COL_BRONZETIME = 'map.bronzeTime';

    /**
     * the column name for the silverTime field
     */
    const COL_SILVERTIME = 'map.silverTime';

    /**
     * the column name for the goldTime field
     */
    const COL_GOLDTIME = 'map.goldTime';

    /**
     * the column name for the authorTime field
     */
    const COL_AUTHORTIME = 'map.authorTime';

    /**
     * the column name for the copperPrice field
     */
    const COL_COPPERPRICE = 'map.copperPrice';

    /**
     * the column name for the lapRave field
     */
    const COL_LAPRAVE = 'map.lapRave';

    /**
     * the column name for the nbLaps field
     */
    const COL_NBLAPS = 'map.nbLaps';

    /**
     * the column name for the npCheckpoints field
     */
    const COL_NPCHECKPOINTS = 'map.npCheckpoints';

    /**
     * the column name for the mapType field
     */
    const COL_MAPTYPE = 'map.mapType';

    /**
     * the column name for the mapStyle field
     */
    const COL_MAPSTYLE = 'map.mapStyle';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'map.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'map.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'Mapuid', 'Name', 'Filename', 'Author', 'Environment', 'Mood', 'Bronzetime', 'Silvertime', 'Goldtime', 'Authortime', 'Copperprice', 'Laprave', 'Nblaps', 'Npcheckpoints', 'Maptype', 'Mapstyle', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'mapuid', 'name', 'filename', 'author', 'environment', 'mood', 'bronzetime', 'silvertime', 'goldtime', 'authortime', 'copperprice', 'laprave', 'nblaps', 'npcheckpoints', 'maptype', 'mapstyle', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(MapTableMap::COL_ID, MapTableMap::COL_MAPUID, MapTableMap::COL_NAME, MapTableMap::COL_FILENAME, MapTableMap::COL_AUTHOR, MapTableMap::COL_ENVIRONMENT, MapTableMap::COL_MOOD, MapTableMap::COL_BRONZETIME, MapTableMap::COL_SILVERTIME, MapTableMap::COL_GOLDTIME, MapTableMap::COL_AUTHORTIME, MapTableMap::COL_COPPERPRICE, MapTableMap::COL_LAPRAVE, MapTableMap::COL_NBLAPS, MapTableMap::COL_NPCHECKPOINTS, MapTableMap::COL_MAPTYPE, MapTableMap::COL_MAPSTYLE, MapTableMap::COL_CREATED_AT, MapTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'mapUid', 'name', 'fileName', 'author', 'environment', 'mood', 'bronzeTime', 'silverTime', 'goldTime', 'authorTime', 'copperPrice', 'lapRave', 'nbLaps', 'npCheckpoints', 'mapType', 'mapStyle', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Mapuid' => 1, 'Name' => 2, 'Filename' => 3, 'Author' => 4, 'Environment' => 5, 'Mood' => 6, 'Bronzetime' => 7, 'Silvertime' => 8, 'Goldtime' => 9, 'Authortime' => 10, 'Copperprice' => 11, 'Laprave' => 12, 'Nblaps' => 13, 'Npcheckpoints' => 14, 'Maptype' => 15, 'Mapstyle' => 16, 'CreatedAt' => 17, 'UpdatedAt' => 18, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'mapuid' => 1, 'name' => 2, 'filename' => 3, 'author' => 4, 'environment' => 5, 'mood' => 6, 'bronzetime' => 7, 'silvertime' => 8, 'goldtime' => 9, 'authortime' => 10, 'copperprice' => 11, 'laprave' => 12, 'nblaps' => 13, 'npcheckpoints' => 14, 'maptype' => 15, 'mapstyle' => 16, 'createdAt' => 17, 'updatedAt' => 18, ),
        self::TYPE_COLNAME       => array(MapTableMap::COL_ID => 0, MapTableMap::COL_MAPUID => 1, MapTableMap::COL_NAME => 2, MapTableMap::COL_FILENAME => 3, MapTableMap::COL_AUTHOR => 4, MapTableMap::COL_ENVIRONMENT => 5, MapTableMap::COL_MOOD => 6, MapTableMap::COL_BRONZETIME => 7, MapTableMap::COL_SILVERTIME => 8, MapTableMap::COL_GOLDTIME => 9, MapTableMap::COL_AUTHORTIME => 10, MapTableMap::COL_COPPERPRICE => 11, MapTableMap::COL_LAPRAVE => 12, MapTableMap::COL_NBLAPS => 13, MapTableMap::COL_NPCHECKPOINTS => 14, MapTableMap::COL_MAPTYPE => 15, MapTableMap::COL_MAPSTYLE => 16, MapTableMap::COL_CREATED_AT => 17, MapTableMap::COL_UPDATED_AT => 18, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'mapUid' => 1, 'name' => 2, 'fileName' => 3, 'author' => 4, 'environment' => 5, 'mood' => 6, 'bronzeTime' => 7, 'silverTime' => 8, 'goldTime' => 9, 'authorTime' => 10, 'copperPrice' => 11, 'lapRave' => 12, 'nbLaps' => 13, 'npCheckpoints' => 14, 'mapType' => 15, 'mapStyle' => 16, 'created_at' => 17, 'updated_at' => 18, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
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
        $this->setName('map');
        $this->setPhpName('Map');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\eXpansion\\Bundle\\Maps\\Model\\Map');
        $this->setPackage('src\eXpansion\Bundle\Maps.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('mapUid', 'Mapuid', 'VARCHAR', false, 255, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('fileName', 'Filename', 'VARCHAR', false, 255, null);
        $this->addColumn('author', 'Author', 'VARCHAR', false, 100, null);
        $this->addColumn('environment', 'Environment', 'VARCHAR', false, 100, null);
        $this->addColumn('mood', 'Mood', 'VARCHAR', false, 100, null);
        $this->addColumn('bronzeTime', 'Bronzetime', 'INTEGER', false, null, null);
        $this->addColumn('silverTime', 'Silvertime', 'INTEGER', false, null, null);
        $this->addColumn('goldTime', 'Goldtime', 'INTEGER', false, null, null);
        $this->addColumn('authorTime', 'Authortime', 'INTEGER', false, null, null);
        $this->addColumn('copperPrice', 'Copperprice', 'INTEGER', false, null, null);
        $this->addColumn('lapRave', 'Laprave', 'BOOLEAN', false, 1, null);
        $this->addColumn('nbLaps', 'Nblaps', 'INTEGER', false, null, null);
        $this->addColumn('npCheckpoints', 'Npcheckpoints', 'INTEGER', false, null, null);
        $this->addColumn('mapType', 'Maptype', 'VARCHAR', false, 255, null);
        $this->addColumn('mapStyle', 'Mapstyle', 'VARCHAR', false, 255, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Mxmap', '\\eXpansion\\Bundle\\Maps\\Model\\Mxmap', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':trackUID',
    1 => ':mapUid',
  ),
), null, null, 'Mxmaps', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

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
        return $withPrefix ? MapTableMap::CLASS_DEFAULT : MapTableMap::OM_CLASS;
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
     * @return array           (Map object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MapTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MapTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MapTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MapTableMap::OM_CLASS;
            /** @var Map $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MapTableMap::addInstanceToPool($obj, $key);
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
            $key = MapTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MapTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Map $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MapTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MapTableMap::COL_ID);
            $criteria->addSelectColumn(MapTableMap::COL_MAPUID);
            $criteria->addSelectColumn(MapTableMap::COL_NAME);
            $criteria->addSelectColumn(MapTableMap::COL_FILENAME);
            $criteria->addSelectColumn(MapTableMap::COL_AUTHOR);
            $criteria->addSelectColumn(MapTableMap::COL_ENVIRONMENT);
            $criteria->addSelectColumn(MapTableMap::COL_MOOD);
            $criteria->addSelectColumn(MapTableMap::COL_BRONZETIME);
            $criteria->addSelectColumn(MapTableMap::COL_SILVERTIME);
            $criteria->addSelectColumn(MapTableMap::COL_GOLDTIME);
            $criteria->addSelectColumn(MapTableMap::COL_AUTHORTIME);
            $criteria->addSelectColumn(MapTableMap::COL_COPPERPRICE);
            $criteria->addSelectColumn(MapTableMap::COL_LAPRAVE);
            $criteria->addSelectColumn(MapTableMap::COL_NBLAPS);
            $criteria->addSelectColumn(MapTableMap::COL_NPCHECKPOINTS);
            $criteria->addSelectColumn(MapTableMap::COL_MAPTYPE);
            $criteria->addSelectColumn(MapTableMap::COL_MAPSTYLE);
            $criteria->addSelectColumn(MapTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(MapTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.mapUid');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.fileName');
            $criteria->addSelectColumn($alias . '.author');
            $criteria->addSelectColumn($alias . '.environment');
            $criteria->addSelectColumn($alias . '.mood');
            $criteria->addSelectColumn($alias . '.bronzeTime');
            $criteria->addSelectColumn($alias . '.silverTime');
            $criteria->addSelectColumn($alias . '.goldTime');
            $criteria->addSelectColumn($alias . '.authorTime');
            $criteria->addSelectColumn($alias . '.copperPrice');
            $criteria->addSelectColumn($alias . '.lapRave');
            $criteria->addSelectColumn($alias . '.nbLaps');
            $criteria->addSelectColumn($alias . '.npCheckpoints');
            $criteria->addSelectColumn($alias . '.mapType');
            $criteria->addSelectColumn($alias . '.mapStyle');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(MapTableMap::DATABASE_NAME)->getTable(MapTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MapTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MapTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MapTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Map or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Map object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MapTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \eXpansion\Bundle\Maps\Model\Map) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MapTableMap::DATABASE_NAME);
            $criteria->add(MapTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MapQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MapTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MapTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the map table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MapQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Map or Criteria object.
     *
     * @param mixed               $criteria Criteria or Map object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Map object
        }

        if ($criteria->containsKey(MapTableMap::COL_ID) && $criteria->keyContainsValue(MapTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MapTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MapQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MapTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MapTableMap::buildTableMap();
