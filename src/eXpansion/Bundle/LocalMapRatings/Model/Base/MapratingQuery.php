<?php

namespace eXpansion\Bundle\LocalMapRatings\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use eXpansion\Bundle\LocalMapRatings\Model\Maprating as ChildMaprating;
use eXpansion\Bundle\LocalMapRatings\Model\MapratingQuery as ChildMapratingQuery;
use eXpansion\Bundle\LocalMapRatings\Model\Map\MapratingTableMap;

/**
 * Base class that represents a query for the 'maprating' table.
 *
 *
 *
 * @method     ChildMapratingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMapratingQuery orderByLogin($order = Criteria::ASC) Order by the login column
 * @method     ChildMapratingQuery orderByMapuid($order = Criteria::ASC) Order by the mapUid column
 * @method     ChildMapratingQuery orderByScore($order = Criteria::ASC) Order by the score column
 * @method     ChildMapratingQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMapratingQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildMapratingQuery groupById() Group by the id column
 * @method     ChildMapratingQuery groupByLogin() Group by the login column
 * @method     ChildMapratingQuery groupByMapuid() Group by the mapUid column
 * @method     ChildMapratingQuery groupByScore() Group by the score column
 * @method     ChildMapratingQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMapratingQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildMapratingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMapratingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMapratingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMapratingQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMapratingQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMapratingQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMaprating findOne(ConnectionInterface $con = null) Return the first ChildMaprating matching the query
 * @method     ChildMaprating findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMaprating matching the query, or a new ChildMaprating object populated from the query conditions when no match is found
 *
 * @method     ChildMaprating findOneById(int $id) Return the first ChildMaprating filtered by the id column
 * @method     ChildMaprating findOneByLogin(string $login) Return the first ChildMaprating filtered by the login column
 * @method     ChildMaprating findOneByMapuid(string $mapUid) Return the first ChildMaprating filtered by the mapUid column
 * @method     ChildMaprating findOneByScore(int $score) Return the first ChildMaprating filtered by the score column
 * @method     ChildMaprating findOneByCreatedAt(string $created_at) Return the first ChildMaprating filtered by the created_at column
 * @method     ChildMaprating findOneByUpdatedAt(string $updated_at) Return the first ChildMaprating filtered by the updated_at column *

 * @method     ChildMaprating requirePk($key, ConnectionInterface $con = null) Return the ChildMaprating by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMaprating requireOne(ConnectionInterface $con = null) Return the first ChildMaprating matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMaprating requireOneById(int $id) Return the first ChildMaprating filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMaprating requireOneByLogin(string $login) Return the first ChildMaprating filtered by the login column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMaprating requireOneByMapuid(string $mapUid) Return the first ChildMaprating filtered by the mapUid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMaprating requireOneByScore(int $score) Return the first ChildMaprating filtered by the score column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMaprating requireOneByCreatedAt(string $created_at) Return the first ChildMaprating filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMaprating requireOneByUpdatedAt(string $updated_at) Return the first ChildMaprating filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMaprating[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMaprating objects based on current ModelCriteria
 * @method     ChildMaprating[]|ObjectCollection findById(int $id) Return ChildMaprating objects filtered by the id column
 * @method     ChildMaprating[]|ObjectCollection findByLogin(string $login) Return ChildMaprating objects filtered by the login column
 * @method     ChildMaprating[]|ObjectCollection findByMapuid(string $mapUid) Return ChildMaprating objects filtered by the mapUid column
 * @method     ChildMaprating[]|ObjectCollection findByScore(int $score) Return ChildMaprating objects filtered by the score column
 * @method     ChildMaprating[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildMaprating objects filtered by the created_at column
 * @method     ChildMaprating[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildMaprating objects filtered by the updated_at column
 * @method     ChildMaprating[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MapratingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \eXpansion\Bundle\LocalMapRatings\Model\Base\MapratingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'expansion', $modelName = '\\eXpansion\\Bundle\\LocalMapRatings\\Model\\Maprating', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMapratingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMapratingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMapratingQuery) {
            return $criteria;
        }
        $query = new ChildMapratingQuery();
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
     * @return ChildMaprating|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MapratingTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MapratingTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMaprating A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, login, mapUid, score, created_at, updated_at FROM maprating WHERE id = :p0';
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
            /** @var ChildMaprating $obj */
            $obj = new ChildMaprating();
            $obj->hydrate($row);
            MapratingTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMaprating|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MapratingTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MapratingTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MapratingTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MapratingTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapratingTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the login column
     *
     * Example usage:
     * <code>
     * $query->filterByLogin('fooValue');   // WHERE login = 'fooValue'
     * $query->filterByLogin('%fooValue%', Criteria::LIKE); // WHERE login LIKE '%fooValue%'
     * </code>
     *
     * @param     string $login The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterByLogin($login = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($login)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapratingTableMap::COL_LOGIN, $login, $comparison);
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
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterByMapuid($mapuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mapuid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapratingTableMap::COL_MAPUID, $mapuid, $comparison);
    }

    /**
     * Filter the query on the score column
     *
     * Example usage:
     * <code>
     * $query->filterByScore(1234); // WHERE score = 1234
     * $query->filterByScore(array(12, 34)); // WHERE score IN (12, 34)
     * $query->filterByScore(array('min' => 12)); // WHERE score > 12
     * </code>
     *
     * @param     mixed $score The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(MapratingTableMap::COL_SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(MapratingTableMap::COL_SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapratingTableMap::COL_SCORE, $score, $comparison);
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
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MapratingTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MapratingTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapratingTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MapratingTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MapratingTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MapratingTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMaprating $maprating Object to remove from the list of results
     *
     * @return $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function prune($maprating = null)
    {
        if ($maprating) {
            $this->addUsingAlias(MapratingTableMap::COL_ID, $maprating->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the maprating table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapratingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MapratingTableMap::clearInstancePool();
            MapratingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MapratingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MapratingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MapratingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MapratingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MapratingTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MapratingTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MapratingTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MapratingTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MapratingTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildMapratingQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MapratingTableMap::COL_CREATED_AT);
    }

} // MapratingQuery
