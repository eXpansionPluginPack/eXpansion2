<?php

namespace eXpansion\Bundle\Maps\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use eXpansion\Bundle\Maps\Model\Map as ChildMap;
use eXpansion\Bundle\Maps\Model\MapQuery as ChildMapQuery;
use eXpansion\Bundle\Maps\Model\Map\MapTableMap;

/**
 * Base class that represents a query for the 'map' table.
 *
 *
 *
 * @method     ChildMapQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMapQuery orderByMapuid($order = Criteria::ASC) Order by the mapUid column
 * @method     ChildMapQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMapQuery orderByFilename($order = Criteria::ASC) Order by the fileName column
 * @method     ChildMapQuery orderByAuthor($order = Criteria::ASC) Order by the author column
 * @method     ChildMapQuery orderByEnvironment($order = Criteria::ASC) Order by the environment column
 * @method     ChildMapQuery orderByMood($order = Criteria::ASC) Order by the mood column
 * @method     ChildMapQuery orderByBronzetime($order = Criteria::ASC) Order by the bronzeTime column
 * @method     ChildMapQuery orderBySilvertime($order = Criteria::ASC) Order by the silverTime column
 * @method     ChildMapQuery orderByGoldtime($order = Criteria::ASC) Order by the goldTime column
 * @method     ChildMapQuery orderByAuthortime($order = Criteria::ASC) Order by the authorTime column
 * @method     ChildMapQuery orderByCopperprice($order = Criteria::ASC) Order by the copperPrice column
 * @method     ChildMapQuery orderByLaprace($order = Criteria::ASC) Order by the lapRace column
 * @method     ChildMapQuery orderByNblaps($order = Criteria::ASC) Order by the nbLaps column
 * @method     ChildMapQuery orderByNpcheckpoints($order = Criteria::ASC) Order by the npCheckpoints column
 * @method     ChildMapQuery orderByMaptype($order = Criteria::ASC) Order by the mapType column
 * @method     ChildMapQuery orderByMapstyle($order = Criteria::ASC) Order by the mapStyle column
 * @method     ChildMapQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMapQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildMapQuery groupById() Group by the id column
 * @method     ChildMapQuery groupByMapuid() Group by the mapUid column
 * @method     ChildMapQuery groupByName() Group by the name column
 * @method     ChildMapQuery groupByFilename() Group by the fileName column
 * @method     ChildMapQuery groupByAuthor() Group by the author column
 * @method     ChildMapQuery groupByEnvironment() Group by the environment column
 * @method     ChildMapQuery groupByMood() Group by the mood column
 * @method     ChildMapQuery groupByBronzetime() Group by the bronzeTime column
 * @method     ChildMapQuery groupBySilvertime() Group by the silverTime column
 * @method     ChildMapQuery groupByGoldtime() Group by the goldTime column
 * @method     ChildMapQuery groupByAuthortime() Group by the authorTime column
 * @method     ChildMapQuery groupByCopperprice() Group by the copperPrice column
 * @method     ChildMapQuery groupByLaprace() Group by the lapRace column
 * @method     ChildMapQuery groupByNblaps() Group by the nbLaps column
 * @method     ChildMapQuery groupByNpcheckpoints() Group by the npCheckpoints column
 * @method     ChildMapQuery groupByMaptype() Group by the mapType column
 * @method     ChildMapQuery groupByMapstyle() Group by the mapStyle column
 * @method     ChildMapQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMapQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildMapQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMapQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMapQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMapQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMapQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMapQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMapQuery leftJoinMxmap($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mxmap relation
 * @method     ChildMapQuery rightJoinMxmap($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mxmap relation
 * @method     ChildMapQuery innerJoinMxmap($relationAlias = null) Adds a INNER JOIN clause to the query using the Mxmap relation
 *
 * @method     ChildMapQuery joinWithMxmap($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Mxmap relation
 *
 * @method     ChildMapQuery leftJoinWithMxmap() Adds a LEFT JOIN clause and with to the query using the Mxmap relation
 * @method     ChildMapQuery rightJoinWithMxmap() Adds a RIGHT JOIN clause and with to the query using the Mxmap relation
 * @method     ChildMapQuery innerJoinWithMxmap() Adds a INNER JOIN clause and with to the query using the Mxmap relation
 *
 * @method     \eXpansion\Bundle\Maps\Model\MxmapQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMap findOne(ConnectionInterface $con = null) Return the first ChildMap matching the query
 * @method     ChildMap findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMap matching the query, or a new ChildMap object populated from the query conditions when no match is found
 *
 * @method     ChildMap findOneById(int $id) Return the first ChildMap filtered by the id column
 * @method     ChildMap findOneByMapuid(string $mapUid) Return the first ChildMap filtered by the mapUid column
 * @method     ChildMap findOneByName(string $name) Return the first ChildMap filtered by the name column
 * @method     ChildMap findOneByFilename(string $fileName) Return the first ChildMap filtered by the fileName column
 * @method     ChildMap findOneByAuthor(string $author) Return the first ChildMap filtered by the author column
 * @method     ChildMap findOneByEnvironment(string $environment) Return the first ChildMap filtered by the environment column
 * @method     ChildMap findOneByMood(string $mood) Return the first ChildMap filtered by the mood column
 * @method     ChildMap findOneByBronzetime(int $bronzeTime) Return the first ChildMap filtered by the bronzeTime column
 * @method     ChildMap findOneBySilvertime(int $silverTime) Return the first ChildMap filtered by the silverTime column
 * @method     ChildMap findOneByGoldtime(int $goldTime) Return the first ChildMap filtered by the goldTime column
 * @method     ChildMap findOneByAuthortime(int $authorTime) Return the first ChildMap filtered by the authorTime column
 * @method     ChildMap findOneByCopperprice(int $copperPrice) Return the first ChildMap filtered by the copperPrice column
 * @method     ChildMap findOneByLaprace(boolean $lapRace) Return the first ChildMap filtered by the lapRace column
 * @method     ChildMap findOneByNblaps(int $nbLaps) Return the first ChildMap filtered by the nbLaps column
 * @method     ChildMap findOneByNpcheckpoints(int $npCheckpoints) Return the first ChildMap filtered by the npCheckpoints column
 * @method     ChildMap findOneByMaptype(string $mapType) Return the first ChildMap filtered by the mapType column
 * @method     ChildMap findOneByMapstyle(string $mapStyle) Return the first ChildMap filtered by the mapStyle column
 * @method     ChildMap findOneByCreatedAt(string $created_at) Return the first ChildMap filtered by the created_at column
 * @method     ChildMap findOneByUpdatedAt(string $updated_at) Return the first ChildMap filtered by the updated_at column *

 * @method     ChildMap requirePk($key, ConnectionInterface $con = null) Return the ChildMap by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOne(ConnectionInterface $con = null) Return the first ChildMap matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMap requireOneById(int $id) Return the first ChildMap filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByMapuid(string $mapUid) Return the first ChildMap filtered by the mapUid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByName(string $name) Return the first ChildMap filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByFilename(string $fileName) Return the first ChildMap filtered by the fileName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByAuthor(string $author) Return the first ChildMap filtered by the author column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByEnvironment(string $environment) Return the first ChildMap filtered by the environment column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByMood(string $mood) Return the first ChildMap filtered by the mood column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByBronzetime(int $bronzeTime) Return the first ChildMap filtered by the bronzeTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneBySilvertime(int $silverTime) Return the first ChildMap filtered by the silverTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByGoldtime(int $goldTime) Return the first ChildMap filtered by the goldTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByAuthortime(int $authorTime) Return the first ChildMap filtered by the authorTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByCopperprice(int $copperPrice) Return the first ChildMap filtered by the copperPrice column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByLaprace(boolean $lapRace) Return the first ChildMap filtered by the lapRace column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByNblaps(int $nbLaps) Return the first ChildMap filtered by the nbLaps column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByNpcheckpoints(int $npCheckpoints) Return the first ChildMap filtered by the npCheckpoints column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByMaptype(string $mapType) Return the first ChildMap filtered by the mapType column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByMapstyle(string $mapStyle) Return the first ChildMap filtered by the mapStyle column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByCreatedAt(string $created_at) Return the first ChildMap filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMap requireOneByUpdatedAt(string $updated_at) Return the first ChildMap filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMap[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMap objects based on current ModelCriteria
 * @method     ChildMap[]|ObjectCollection findById(int $id) Return ChildMap objects filtered by the id column
 * @method     ChildMap[]|ObjectCollection findByMapuid(string $mapUid) Return ChildMap objects filtered by the mapUid column
 * @method     ChildMap[]|ObjectCollection findByName(string $name) Return ChildMap objects filtered by the name column
 * @method     ChildMap[]|ObjectCollection findByFilename(string $fileName) Return ChildMap objects filtered by the fileName column
 * @method     ChildMap[]|ObjectCollection findByAuthor(string $author) Return ChildMap objects filtered by the author column
 * @method     ChildMap[]|ObjectCollection findByEnvironment(string $environment) Return ChildMap objects filtered by the environment column
 * @method     ChildMap[]|ObjectCollection findByMood(string $mood) Return ChildMap objects filtered by the mood column
 * @method     ChildMap[]|ObjectCollection findByBronzetime(int $bronzeTime) Return ChildMap objects filtered by the bronzeTime column
 * @method     ChildMap[]|ObjectCollection findBySilvertime(int $silverTime) Return ChildMap objects filtered by the silverTime column
 * @method     ChildMap[]|ObjectCollection findByGoldtime(int $goldTime) Return ChildMap objects filtered by the goldTime column
 * @method     ChildMap[]|ObjectCollection findByAuthortime(int $authorTime) Return ChildMap objects filtered by the authorTime column
 * @method     ChildMap[]|ObjectCollection findByCopperprice(int $copperPrice) Return ChildMap objects filtered by the copperPrice column
 * @method     ChildMap[]|ObjectCollection findByLaprace(boolean $lapRace) Return ChildMap objects filtered by the lapRace column
 * @method     ChildMap[]|ObjectCollection findByNblaps(int $nbLaps) Return ChildMap objects filtered by the nbLaps column
 * @method     ChildMap[]|ObjectCollection findByNpcheckpoints(int $npCheckpoints) Return ChildMap objects filtered by the npCheckpoints column
 * @method     ChildMap[]|ObjectCollection findByMaptype(string $mapType) Return ChildMap objects filtered by the mapType column
 * @method     ChildMap[]|ObjectCollection findByMapstyle(string $mapStyle) Return ChildMap objects filtered by the mapStyle column
 * @method     ChildMap[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildMap objects filtered by the created_at column
 * @method     ChildMap[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildMap objects filtered by the updated_at column
 * @method     ChildMap[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MapQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \eXpansion\Bundle\Maps\Model\Base\MapQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'expansion', $modelName = '\\eXpansion\\Bundle\\Maps\\Model\\Map', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMapQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMapQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMapQuery) {
            return $criteria;
        }
        $query = new ChildMapQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMap|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MapTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MapTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMap A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, mapUid, name, fileName, author, environment, mood, bronzeTime, silverTime, goldTime, authorTime, copperPrice, lapRace, nbLaps, npCheckpoints, mapType, mapStyle, created_at, updated_at FROM map WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMap $obj */
            $obj = new ChildMap();
            $obj->hydrate($row);
            MapTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildMap|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MapTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MapTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MapTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MapTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the mapUid column
     *
     * Example usage:
     * <code>
     * $query->filterByMapuid('fooValue');   // WHERE mapUid = 'fooValue'
     * $query->filterByMapuid('%fooValue%', Criteria::LIKE); // WHERE mapUid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mapuid The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByMapuid($mapuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mapuid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_MAPUID, $mapuid, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the fileName column
     *
     * Example usage:
     * <code>
     * $query->filterByFilename('fooValue');   // WHERE fileName = 'fooValue'
     * $query->filterByFilename('%fooValue%', Criteria::LIKE); // WHERE fileName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $filename The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByFilename($filename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filename)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_FILENAME, $filename, $comparison);
    }

    /**
     * Filter the query on the author column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthor('fooValue');   // WHERE author = 'fooValue'
     * $query->filterByAuthor('%fooValue%', Criteria::LIKE); // WHERE author LIKE '%fooValue%'
     * </code>
     *
     * @param     string $author The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByAuthor($author = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($author)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_AUTHOR, $author, $comparison);
    }

    /**
     * Filter the query on the environment column
     *
     * Example usage:
     * <code>
     * $query->filterByEnvironment('fooValue');   // WHERE environment = 'fooValue'
     * $query->filterByEnvironment('%fooValue%', Criteria::LIKE); // WHERE environment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $environment The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByEnvironment($environment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($environment)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_ENVIRONMENT, $environment, $comparison);
    }

    /**
     * Filter the query on the mood column
     *
     * Example usage:
     * <code>
     * $query->filterByMood('fooValue');   // WHERE mood = 'fooValue'
     * $query->filterByMood('%fooValue%', Criteria::LIKE); // WHERE mood LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mood The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByMood($mood = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mood)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_MOOD, $mood, $comparison);
    }

    /**
     * Filter the query on the bronzeTime column
     *
     * Example usage:
     * <code>
     * $query->filterByBronzetime(1234); // WHERE bronzeTime = 1234
     * $query->filterByBronzetime(array(12, 34)); // WHERE bronzeTime IN (12, 34)
     * $query->filterByBronzetime(array('min' => 12)); // WHERE bronzeTime > 12
     * </code>
     *
     * @param     mixed $bronzetime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByBronzetime($bronzetime = null, $comparison = null)
    {
        if (is_array($bronzetime)) {
            $useMinMax = false;
            if (isset($bronzetime['min'])) {
                $this->addUsingAlias(MapTableMap::COL_BRONZETIME, $bronzetime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bronzetime['max'])) {
                $this->addUsingAlias(MapTableMap::COL_BRONZETIME, $bronzetime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_BRONZETIME, $bronzetime, $comparison);
    }

    /**
     * Filter the query on the silverTime column
     *
     * Example usage:
     * <code>
     * $query->filterBySilvertime(1234); // WHERE silverTime = 1234
     * $query->filterBySilvertime(array(12, 34)); // WHERE silverTime IN (12, 34)
     * $query->filterBySilvertime(array('min' => 12)); // WHERE silverTime > 12
     * </code>
     *
     * @param     mixed $silvertime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterBySilvertime($silvertime = null, $comparison = null)
    {
        if (is_array($silvertime)) {
            $useMinMax = false;
            if (isset($silvertime['min'])) {
                $this->addUsingAlias(MapTableMap::COL_SILVERTIME, $silvertime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($silvertime['max'])) {
                $this->addUsingAlias(MapTableMap::COL_SILVERTIME, $silvertime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_SILVERTIME, $silvertime, $comparison);
    }

    /**
     * Filter the query on the goldTime column
     *
     * Example usage:
     * <code>
     * $query->filterByGoldtime(1234); // WHERE goldTime = 1234
     * $query->filterByGoldtime(array(12, 34)); // WHERE goldTime IN (12, 34)
     * $query->filterByGoldtime(array('min' => 12)); // WHERE goldTime > 12
     * </code>
     *
     * @param     mixed $goldtime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByGoldtime($goldtime = null, $comparison = null)
    {
        if (is_array($goldtime)) {
            $useMinMax = false;
            if (isset($goldtime['min'])) {
                $this->addUsingAlias(MapTableMap::COL_GOLDTIME, $goldtime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($goldtime['max'])) {
                $this->addUsingAlias(MapTableMap::COL_GOLDTIME, $goldtime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_GOLDTIME, $goldtime, $comparison);
    }

    /**
     * Filter the query on the authorTime column
     *
     * Example usage:
     * <code>
     * $query->filterByAuthortime(1234); // WHERE authorTime = 1234
     * $query->filterByAuthortime(array(12, 34)); // WHERE authorTime IN (12, 34)
     * $query->filterByAuthortime(array('min' => 12)); // WHERE authorTime > 12
     * </code>
     *
     * @param     mixed $authortime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByAuthortime($authortime = null, $comparison = null)
    {
        if (is_array($authortime)) {
            $useMinMax = false;
            if (isset($authortime['min'])) {
                $this->addUsingAlias(MapTableMap::COL_AUTHORTIME, $authortime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($authortime['max'])) {
                $this->addUsingAlias(MapTableMap::COL_AUTHORTIME, $authortime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_AUTHORTIME, $authortime, $comparison);
    }

    /**
     * Filter the query on the copperPrice column
     *
     * Example usage:
     * <code>
     * $query->filterByCopperprice(1234); // WHERE copperPrice = 1234
     * $query->filterByCopperprice(array(12, 34)); // WHERE copperPrice IN (12, 34)
     * $query->filterByCopperprice(array('min' => 12)); // WHERE copperPrice > 12
     * </code>
     *
     * @param     mixed $copperprice The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByCopperprice($copperprice = null, $comparison = null)
    {
        if (is_array($copperprice)) {
            $useMinMax = false;
            if (isset($copperprice['min'])) {
                $this->addUsingAlias(MapTableMap::COL_COPPERPRICE, $copperprice['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($copperprice['max'])) {
                $this->addUsingAlias(MapTableMap::COL_COPPERPRICE, $copperprice['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_COPPERPRICE, $copperprice, $comparison);
    }

    /**
     * Filter the query on the lapRace column
     *
     * Example usage:
     * <code>
     * $query->filterByLaprace(true); // WHERE lapRace = true
     * $query->filterByLaprace('yes'); // WHERE lapRace = true
     * </code>
     *
     * @param     boolean|string $laprace The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByLaprace($laprace = null, $comparison = null)
    {
        if (is_string($laprace)) {
            $laprace = in_array(strtolower($laprace), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MapTableMap::COL_LAPRACE, $laprace, $comparison);
    }

    /**
     * Filter the query on the nbLaps column
     *
     * Example usage:
     * <code>
     * $query->filterByNblaps(1234); // WHERE nbLaps = 1234
     * $query->filterByNblaps(array(12, 34)); // WHERE nbLaps IN (12, 34)
     * $query->filterByNblaps(array('min' => 12)); // WHERE nbLaps > 12
     * </code>
     *
     * @param     mixed $nblaps The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByNblaps($nblaps = null, $comparison = null)
    {
        if (is_array($nblaps)) {
            $useMinMax = false;
            if (isset($nblaps['min'])) {
                $this->addUsingAlias(MapTableMap::COL_NBLAPS, $nblaps['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nblaps['max'])) {
                $this->addUsingAlias(MapTableMap::COL_NBLAPS, $nblaps['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_NBLAPS, $nblaps, $comparison);
    }

    /**
     * Filter the query on the npCheckpoints column
     *
     * Example usage:
     * <code>
     * $query->filterByNpcheckpoints(1234); // WHERE npCheckpoints = 1234
     * $query->filterByNpcheckpoints(array(12, 34)); // WHERE npCheckpoints IN (12, 34)
     * $query->filterByNpcheckpoints(array('min' => 12)); // WHERE npCheckpoints > 12
     * </code>
     *
     * @param     mixed $npcheckpoints The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByNpcheckpoints($npcheckpoints = null, $comparison = null)
    {
        if (is_array($npcheckpoints)) {
            $useMinMax = false;
            if (isset($npcheckpoints['min'])) {
                $this->addUsingAlias(MapTableMap::COL_NPCHECKPOINTS, $npcheckpoints['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($npcheckpoints['max'])) {
                $this->addUsingAlias(MapTableMap::COL_NPCHECKPOINTS, $npcheckpoints['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_NPCHECKPOINTS, $npcheckpoints, $comparison);
    }

    /**
     * Filter the query on the mapType column
     *
     * Example usage:
     * <code>
     * $query->filterByMaptype('fooValue');   // WHERE mapType = 'fooValue'
     * $query->filterByMaptype('%fooValue%', Criteria::LIKE); // WHERE mapType LIKE '%fooValue%'
     * </code>
     *
     * @param     string $maptype The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByMaptype($maptype = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($maptype)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_MAPTYPE, $maptype, $comparison);
    }

    /**
     * Filter the query on the mapStyle column
     *
     * Example usage:
     * <code>
     * $query->filterByMapstyle('fooValue');   // WHERE mapStyle = 'fooValue'
     * $query->filterByMapstyle('%fooValue%', Criteria::LIKE); // WHERE mapStyle LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mapstyle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByMapstyle($mapstyle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mapstyle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_MAPSTYLE, $mapstyle, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MapTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MapTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MapTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MapTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \eXpansion\Bundle\Maps\Model\Mxmap object
     *
     * @param \eXpansion\Bundle\Maps\Model\Mxmap|ObjectCollection $mxmap the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMapQuery The current query, for fluid interface
     */
    public function filterByMxmap($mxmap, $comparison = null)
    {
        if ($mxmap instanceof \eXpansion\Bundle\Maps\Model\Mxmap) {
            return $this
                ->addUsingAlias(MapTableMap::COL_MAPUID, $mxmap->getTrackuid(), $comparison);
        } elseif ($mxmap instanceof ObjectCollection) {
            return $this
                ->useMxmapQuery()
                ->filterByPrimaryKeys($mxmap->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMxmap() only accepts arguments of type \eXpansion\Bundle\Maps\Model\Mxmap or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Mxmap relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function joinMxmap($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Mxmap');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Mxmap');
        }

        return $this;
    }

    /**
     * Use the Mxmap relation Mxmap object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \eXpansion\Bundle\Maps\Model\MxmapQuery A secondary query class using the current class as primary query
     */
    public function useMxmapQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMxmap($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Mxmap', '\eXpansion\Bundle\Maps\Model\MxmapQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMap $map Object to remove from the list of results
     *
     * @return $this|ChildMapQuery The current query, for fluid interface
     */
    public function prune($map = null)
    {
        if ($map) {
            $this->addUsingAlias(MapTableMap::COL_ID, $map->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the map table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MapTableMap::clearInstancePool();
            MapTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MapTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MapTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MapTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildMapQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MapTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildMapQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MapTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildMapQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MapTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildMapQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MapTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildMapQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MapTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildMapQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MapTableMap::COL_CREATED_AT);
    }

} // MapQuery
