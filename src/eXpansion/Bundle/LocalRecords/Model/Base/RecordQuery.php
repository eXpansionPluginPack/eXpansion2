<?php

namespace eXpansion\Bundle\LocalRecords\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use eXpansion\Bundle\LocalRecords\Model\Record as ChildRecord;
use eXpansion\Bundle\LocalRecords\Model\RecordQuery as ChildRecordQuery;
use eXpansion\Bundle\LocalRecords\Model\Map\RecordTableMap;
use eXpansion\Framework\PlayersBundle\Model\Player;

/**
 * Base class that represents a query for the 'record' table.
 *
 *
 *
 * @method     ChildRecordQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildRecordQuery orderByMapuid($order = Criteria::ASC) Order by the mapUid column
 * @method     ChildRecordQuery orderByNblaps($order = Criteria::ASC) Order by the nbLaps column
 * @method     ChildRecordQuery orderByScore($order = Criteria::ASC) Order by the score column
 * @method     ChildRecordQuery orderByNbfinish($order = Criteria::ASC) Order by the nbFinish column
 * @method     ChildRecordQuery orderByAvgscore($order = Criteria::ASC) Order by the avgScore column
 * @method     ChildRecordQuery orderByCheckpoints($order = Criteria::ASC) Order by the checkpoints column
 * @method     ChildRecordQuery orderByPlayerId($order = Criteria::ASC) Order by the player_id column
 * @method     ChildRecordQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildRecordQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildRecordQuery groupById() Group by the id column
 * @method     ChildRecordQuery groupByMapuid() Group by the mapUid column
 * @method     ChildRecordQuery groupByNblaps() Group by the nbLaps column
 * @method     ChildRecordQuery groupByScore() Group by the score column
 * @method     ChildRecordQuery groupByNbfinish() Group by the nbFinish column
 * @method     ChildRecordQuery groupByAvgscore() Group by the avgScore column
 * @method     ChildRecordQuery groupByCheckpoints() Group by the checkpoints column
 * @method     ChildRecordQuery groupByPlayerId() Group by the player_id column
 * @method     ChildRecordQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildRecordQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildRecordQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRecordQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRecordQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRecordQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildRecordQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildRecordQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildRecordQuery leftJoinPlayer($relationAlias = null) Adds a LEFT JOIN clause to the query using the Player relation
 * @method     ChildRecordQuery rightJoinPlayer($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Player relation
 * @method     ChildRecordQuery innerJoinPlayer($relationAlias = null) Adds a INNER JOIN clause to the query using the Player relation
 *
 * @method     ChildRecordQuery joinWithPlayer($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Player relation
 *
 * @method     ChildRecordQuery leftJoinWithPlayer() Adds a LEFT JOIN clause and with to the query using the Player relation
 * @method     ChildRecordQuery rightJoinWithPlayer() Adds a RIGHT JOIN clause and with to the query using the Player relation
 * @method     ChildRecordQuery innerJoinWithPlayer() Adds a INNER JOIN clause and with to the query using the Player relation
 *
 * @method     \eXpansion\Framework\PlayersBundle\Model\PlayerQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildRecord findOne(ConnectionInterface $con = null) Return the first ChildRecord matching the query
 * @method     ChildRecord findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRecord matching the query, or a new ChildRecord object populated from the query conditions when no match is found
 *
 * @method     ChildRecord findOneById(int $id) Return the first ChildRecord filtered by the id column
 * @method     ChildRecord findOneByMapuid(string $mapUid) Return the first ChildRecord filtered by the mapUid column
 * @method     ChildRecord findOneByNblaps(int $nbLaps) Return the first ChildRecord filtered by the nbLaps column
 * @method     ChildRecord findOneByScore(int $score) Return the first ChildRecord filtered by the score column
 * @method     ChildRecord findOneByNbfinish(int $nbFinish) Return the first ChildRecord filtered by the nbFinish column
 * @method     ChildRecord findOneByAvgscore(int $avgScore) Return the first ChildRecord filtered by the avgScore column
 * @method     ChildRecord findOneByCheckpoints(string $checkpoints) Return the first ChildRecord filtered by the checkpoints column
 * @method     ChildRecord findOneByPlayerId(int $player_id) Return the first ChildRecord filtered by the player_id column
 * @method     ChildRecord findOneByCreatedAt(string $created_at) Return the first ChildRecord filtered by the created_at column
 * @method     ChildRecord findOneByUpdatedAt(string $updated_at) Return the first ChildRecord filtered by the updated_at column *

 * @method     ChildRecord requirePk($key, ConnectionInterface $con = null) Return the ChildRecord by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOne(ConnectionInterface $con = null) Return the first ChildRecord matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRecord requireOneById(int $id) Return the first ChildRecord filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByMapuid(string $mapUid) Return the first ChildRecord filtered by the mapUid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByNblaps(int $nbLaps) Return the first ChildRecord filtered by the nbLaps column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByScore(int $score) Return the first ChildRecord filtered by the score column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByNbfinish(int $nbFinish) Return the first ChildRecord filtered by the nbFinish column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByAvgscore(int $avgScore) Return the first ChildRecord filtered by the avgScore column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByCheckpoints(string $checkpoints) Return the first ChildRecord filtered by the checkpoints column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByPlayerId(int $player_id) Return the first ChildRecord filtered by the player_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByCreatedAt(string $created_at) Return the first ChildRecord filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildRecord requireOneByUpdatedAt(string $updated_at) Return the first ChildRecord filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildRecord[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildRecord objects based on current ModelCriteria
 * @method     ChildRecord[]|ObjectCollection findById(int $id) Return ChildRecord objects filtered by the id column
 * @method     ChildRecord[]|ObjectCollection findByMapuid(string $mapUid) Return ChildRecord objects filtered by the mapUid column
 * @method     ChildRecord[]|ObjectCollection findByNblaps(int $nbLaps) Return ChildRecord objects filtered by the nbLaps column
 * @method     ChildRecord[]|ObjectCollection findByScore(int $score) Return ChildRecord objects filtered by the score column
 * @method     ChildRecord[]|ObjectCollection findByNbfinish(int $nbFinish) Return ChildRecord objects filtered by the nbFinish column
 * @method     ChildRecord[]|ObjectCollection findByAvgscore(int $avgScore) Return ChildRecord objects filtered by the avgScore column
 * @method     ChildRecord[]|ObjectCollection findByCheckpoints(string $checkpoints) Return ChildRecord objects filtered by the checkpoints column
 * @method     ChildRecord[]|ObjectCollection findByPlayerId(int $player_id) Return ChildRecord objects filtered by the player_id column
 * @method     ChildRecord[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildRecord objects filtered by the created_at column
 * @method     ChildRecord[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildRecord objects filtered by the updated_at column
 * @method     ChildRecord[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class RecordQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \eXpansion\Bundle\LocalRecords\Model\Base\RecordQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'expansion', $modelName = '\\eXpansion\\Bundle\\LocalRecords\\Model\\Record', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRecordQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRecordQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildRecordQuery) {
            return $criteria;
        }
        $query = new ChildRecordQuery();
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
     * @return ChildRecord|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RecordTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = RecordTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildRecord A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, mapUid, nbLaps, score, nbFinish, avgScore, checkpoints, player_id, created_at, updated_at FROM record WHERE id = :p0';
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
            /** @var ChildRecord $obj */
            $obj = new ChildRecord();
            $obj->hydrate($row);
            RecordTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildRecord|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(RecordTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(RecordTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByMapuid($mapuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mapuid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_MAPUID, $mapuid, $comparison);
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
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByNblaps($nblaps = null, $comparison = null)
    {
        if (is_array($nblaps)) {
            $useMinMax = false;
            if (isset($nblaps['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_NBLAPS, $nblaps['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nblaps['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_NBLAPS, $nblaps['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_NBLAPS, $nblaps, $comparison);
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
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByScore($score = null, $comparison = null)
    {
        if (is_array($score)) {
            $useMinMax = false;
            if (isset($score['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_SCORE, $score['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($score['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_SCORE, $score['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_SCORE, $score, $comparison);
    }

    /**
     * Filter the query on the nbFinish column
     *
     * Example usage:
     * <code>
     * $query->filterByNbfinish(1234); // WHERE nbFinish = 1234
     * $query->filterByNbfinish(array(12, 34)); // WHERE nbFinish IN (12, 34)
     * $query->filterByNbfinish(array('min' => 12)); // WHERE nbFinish > 12
     * </code>
     *
     * @param     mixed $nbfinish The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByNbfinish($nbfinish = null, $comparison = null)
    {
        if (is_array($nbfinish)) {
            $useMinMax = false;
            if (isset($nbfinish['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_NBFINISH, $nbfinish['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbfinish['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_NBFINISH, $nbfinish['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_NBFINISH, $nbfinish, $comparison);
    }

    /**
     * Filter the query on the avgScore column
     *
     * Example usage:
     * <code>
     * $query->filterByAvgscore(1234); // WHERE avgScore = 1234
     * $query->filterByAvgscore(array(12, 34)); // WHERE avgScore IN (12, 34)
     * $query->filterByAvgscore(array('min' => 12)); // WHERE avgScore > 12
     * </code>
     *
     * @param     mixed $avgscore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByAvgscore($avgscore = null, $comparison = null)
    {
        if (is_array($avgscore)) {
            $useMinMax = false;
            if (isset($avgscore['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_AVGSCORE, $avgscore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($avgscore['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_AVGSCORE, $avgscore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_AVGSCORE, $avgscore, $comparison);
    }

    /**
     * Filter the query on the checkpoints column
     *
     * Example usage:
     * <code>
     * $query->filterByCheckpoints('fooValue');   // WHERE checkpoints = 'fooValue'
     * $query->filterByCheckpoints('%fooValue%', Criteria::LIKE); // WHERE checkpoints LIKE '%fooValue%'
     * </code>
     *
     * @param     string $checkpoints The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByCheckpoints($checkpoints = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($checkpoints)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_CHECKPOINTS, $checkpoints, $comparison);
    }

    /**
     * Filter the query on the player_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPlayerId(1234); // WHERE player_id = 1234
     * $query->filterByPlayerId(array(12, 34)); // WHERE player_id IN (12, 34)
     * $query->filterByPlayerId(array('min' => 12)); // WHERE player_id > 12
     * </code>
     *
     * @see       filterByPlayer()
     *
     * @param     mixed $playerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByPlayerId($playerId = null, $comparison = null)
    {
        if (is_array($playerId)) {
            $useMinMax = false;
            if (isset($playerId['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_PLAYER_ID, $playerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($playerId['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_PLAYER_ID, $playerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_PLAYER_ID, $playerId, $comparison);
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
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(RecordTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(RecordTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RecordTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \eXpansion\Framework\PlayersBundle\Model\Player object
     *
     * @param \eXpansion\Framework\PlayersBundle\Model\Player|ObjectCollection $player The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildRecordQuery The current query, for fluid interface
     */
    public function filterByPlayer($player, $comparison = null)
    {
        if ($player instanceof \eXpansion\Framework\PlayersBundle\Model\Player) {
            return $this
                ->addUsingAlias(RecordTableMap::COL_PLAYER_ID, $player->getId(), $comparison);
        } elseif ($player instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RecordTableMap::COL_PLAYER_ID, $player->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPlayer() only accepts arguments of type \eXpansion\Framework\PlayersBundle\Model\Player or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Player relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function joinPlayer($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Player');

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
            $this->addJoinObject($join, 'Player');
        }

        return $this;
    }

    /**
     * Use the Player relation Player object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \eXpansion\Framework\PlayersBundle\Model\PlayerQuery A secondary query class using the current class as primary query
     */
    public function usePlayerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayer($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Player', '\eXpansion\Framework\PlayersBundle\Model\PlayerQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRecord $record Object to remove from the list of results
     *
     * @return $this|ChildRecordQuery The current query, for fluid interface
     */
    public function prune($record = null)
    {
        if ($record) {
            $this->addUsingAlias(RecordTableMap::COL_ID, $record->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the record table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RecordTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RecordTableMap::clearInstancePool();
            RecordTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(RecordTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RecordTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            RecordTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RecordTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildRecordQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(RecordTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildRecordQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(RecordTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildRecordQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(RecordTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildRecordQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(RecordTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildRecordQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(RecordTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildRecordQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(RecordTableMap::COL_CREATED_AT);
    }

} // RecordQuery
