<?php

namespace eXpansion\Framework\PlayersBundle\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Framework\PlayersBundle\Model\Player as ChildPlayer;
use eXpansion\Framework\PlayersBundle\Model\PlayerQuery as ChildPlayerQuery;
use eXpansion\Framework\PlayersBundle\Model\Map\PlayerTableMap;

/**
 * Base class that represents a query for the 'player' table.
 *
 *
 *
 * @method     ChildPlayerQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPlayerQuery orderByLogin($order = Criteria::ASC) Order by the login column
 * @method     ChildPlayerQuery orderByNickname($order = Criteria::ASC) Order by the nickname column
 * @method     ChildPlayerQuery orderByNicknameStripped($order = Criteria::ASC) Order by the nickname_stripped column
 * @method     ChildPlayerQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method     ChildPlayerQuery orderByWins($order = Criteria::ASC) Order by the wins column
 * @method     ChildPlayerQuery orderByOnlineTime($order = Criteria::ASC) Order by the online_time column
 * @method     ChildPlayerQuery orderByLastOnline($order = Criteria::ASC) Order by the last_online column
 *
 * @method     ChildPlayerQuery groupById() Group by the id column
 * @method     ChildPlayerQuery groupByLogin() Group by the login column
 * @method     ChildPlayerQuery groupByNickname() Group by the nickname column
 * @method     ChildPlayerQuery groupByNicknameStripped() Group by the nickname_stripped column
 * @method     ChildPlayerQuery groupByPath() Group by the path column
 * @method     ChildPlayerQuery groupByWins() Group by the wins column
 * @method     ChildPlayerQuery groupByOnlineTime() Group by the online_time column
 * @method     ChildPlayerQuery groupByLastOnline() Group by the last_online column
 *
 * @method     ChildPlayerQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPlayerQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPlayerQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPlayerQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPlayerQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPlayerQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPlayerQuery leftJoinMaprating($relationAlias = null) Adds a LEFT JOIN clause to the query using the Maprating relation
 * @method     ChildPlayerQuery rightJoinMaprating($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Maprating relation
 * @method     ChildPlayerQuery innerJoinMaprating($relationAlias = null) Adds a INNER JOIN clause to the query using the Maprating relation
 *
 * @method     ChildPlayerQuery joinWithMaprating($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Maprating relation
 *
 * @method     ChildPlayerQuery leftJoinWithMaprating() Adds a LEFT JOIN clause and with to the query using the Maprating relation
 * @method     ChildPlayerQuery rightJoinWithMaprating() Adds a RIGHT JOIN clause and with to the query using the Maprating relation
 * @method     ChildPlayerQuery innerJoinWithMaprating() Adds a INNER JOIN clause and with to the query using the Maprating relation
 *
 * @method     ChildPlayerQuery leftJoinRecord($relationAlias = null) Adds a LEFT JOIN clause to the query using the Record relation
 * @method     ChildPlayerQuery rightJoinRecord($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Record relation
 * @method     ChildPlayerQuery innerJoinRecord($relationAlias = null) Adds a INNER JOIN clause to the query using the Record relation
 *
 * @method     ChildPlayerQuery joinWithRecord($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Record relation
 *
 * @method     ChildPlayerQuery leftJoinWithRecord() Adds a LEFT JOIN clause and with to the query using the Record relation
 * @method     ChildPlayerQuery rightJoinWithRecord() Adds a RIGHT JOIN clause and with to the query using the Record relation
 * @method     ChildPlayerQuery innerJoinWithRecord() Adds a INNER JOIN clause and with to the query using the Record relation
 *
 * @method     \eXpansion\Bundle\LocalMapRatings\Model\MapratingQuery|\eXpansion\Bundle\LocalRecords\Model\RecordQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPlayer findOne(ConnectionInterface $con = null) Return the first ChildPlayer matching the query
 * @method     ChildPlayer findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPlayer matching the query, or a new ChildPlayer object populated from the query conditions when no match is found
 *
 * @method     ChildPlayer findOneById(int $id) Return the first ChildPlayer filtered by the id column
 * @method     ChildPlayer findOneByLogin(string $login) Return the first ChildPlayer filtered by the login column
 * @method     ChildPlayer findOneByNickname(string $nickname) Return the first ChildPlayer filtered by the nickname column
 * @method     ChildPlayer findOneByNicknameStripped(string $nickname_stripped) Return the first ChildPlayer filtered by the nickname_stripped column
 * @method     ChildPlayer findOneByPath(string $path) Return the first ChildPlayer filtered by the path column
 * @method     ChildPlayer findOneByWins(int $wins) Return the first ChildPlayer filtered by the wins column
 * @method     ChildPlayer findOneByOnlineTime(int $online_time) Return the first ChildPlayer filtered by the online_time column
 * @method     ChildPlayer findOneByLastOnline(string $last_online) Return the first ChildPlayer filtered by the last_online column *

 * @method     ChildPlayer requirePk($key, ConnectionInterface $con = null) Return the ChildPlayer by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOne(ConnectionInterface $con = null) Return the first ChildPlayer matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayer requireOneById(int $id) Return the first ChildPlayer filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByLogin(string $login) Return the first ChildPlayer filtered by the login column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByNickname(string $nickname) Return the first ChildPlayer filtered by the nickname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByNicknameStripped(string $nickname_stripped) Return the first ChildPlayer filtered by the nickname_stripped column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByPath(string $path) Return the first ChildPlayer filtered by the path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByWins(int $wins) Return the first ChildPlayer filtered by the wins column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByOnlineTime(int $online_time) Return the first ChildPlayer filtered by the online_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPlayer requireOneByLastOnline(string $last_online) Return the first ChildPlayer filtered by the last_online column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPlayer[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPlayer objects based on current ModelCriteria
 * @method     ChildPlayer[]|ObjectCollection findById(int $id) Return ChildPlayer objects filtered by the id column
 * @method     ChildPlayer[]|ObjectCollection findByLogin(string $login) Return ChildPlayer objects filtered by the login column
 * @method     ChildPlayer[]|ObjectCollection findByNickname(string $nickname) Return ChildPlayer objects filtered by the nickname column
 * @method     ChildPlayer[]|ObjectCollection findByNicknameStripped(string $nickname_stripped) Return ChildPlayer objects filtered by the nickname_stripped column
 * @method     ChildPlayer[]|ObjectCollection findByPath(string $path) Return ChildPlayer objects filtered by the path column
 * @method     ChildPlayer[]|ObjectCollection findByWins(int $wins) Return ChildPlayer objects filtered by the wins column
 * @method     ChildPlayer[]|ObjectCollection findByOnlineTime(int $online_time) Return ChildPlayer objects filtered by the online_time column
 * @method     ChildPlayer[]|ObjectCollection findByLastOnline(string $last_online) Return ChildPlayer objects filtered by the last_online column
 * @method     ChildPlayer[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PlayerQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \eXpansion\Framework\PlayersBundle\Model\Base\PlayerQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'expansion', $modelName = '\\eXpansion\\Framework\\PlayersBundle\\Model\\Player', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPlayerQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPlayerQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPlayerQuery) {
            return $criteria;
        }
        $query = new ChildPlayerQuery();
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
     * @return ChildPlayer|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PlayerTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PlayerTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPlayer A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, login, nickname, nickname_stripped, path, wins, online_time, last_online FROM player WHERE id = :p0';
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
            /** @var ChildPlayer $obj */
            $obj = new ChildPlayer();
            $obj->hydrate($row);
            PlayerTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPlayer|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByLogin($login = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($login)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_LOGIN, $login, $comparison);
    }

    /**
     * Filter the query on the nickname column
     *
     * Example usage:
     * <code>
     * $query->filterByNickname('fooValue');   // WHERE nickname = 'fooValue'
     * $query->filterByNickname('%fooValue%', Criteria::LIKE); // WHERE nickname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nickname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByNickname($nickname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nickname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_NICKNAME, $nickname, $comparison);
    }

    /**
     * Filter the query on the nickname_stripped column
     *
     * Example usage:
     * <code>
     * $query->filterByNicknameStripped('fooValue');   // WHERE nickname_stripped = 'fooValue'
     * $query->filterByNicknameStripped('%fooValue%', Criteria::LIKE); // WHERE nickname_stripped LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nicknameStripped The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByNicknameStripped($nicknameStripped = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nicknameStripped)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_NICKNAME_STRIPPED, $nicknameStripped, $comparison);
    }

    /**
     * Filter the query on the path column
     *
     * Example usage:
     * <code>
     * $query->filterByPath('fooValue');   // WHERE path = 'fooValue'
     * $query->filterByPath('%fooValue%', Criteria::LIKE); // WHERE path LIKE '%fooValue%'
     * </code>
     *
     * @param     string $path The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByPath($path = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($path)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_PATH, $path, $comparison);
    }

    /**
     * Filter the query on the wins column
     *
     * Example usage:
     * <code>
     * $query->filterByWins(1234); // WHERE wins = 1234
     * $query->filterByWins(array(12, 34)); // WHERE wins IN (12, 34)
     * $query->filterByWins(array('min' => 12)); // WHERE wins > 12
     * </code>
     *
     * @param     mixed $wins The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByWins($wins = null, $comparison = null)
    {
        if (is_array($wins)) {
            $useMinMax = false;
            if (isset($wins['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_WINS, $wins['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($wins['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_WINS, $wins['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_WINS, $wins, $comparison);
    }

    /**
     * Filter the query on the online_time column
     *
     * Example usage:
     * <code>
     * $query->filterByOnlineTime(1234); // WHERE online_time = 1234
     * $query->filterByOnlineTime(array(12, 34)); // WHERE online_time IN (12, 34)
     * $query->filterByOnlineTime(array('min' => 12)); // WHERE online_time > 12
     * </code>
     *
     * @param     mixed $onlineTime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByOnlineTime($onlineTime = null, $comparison = null)
    {
        if (is_array($onlineTime)) {
            $useMinMax = false;
            if (isset($onlineTime['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ONLINE_TIME, $onlineTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($onlineTime['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_ONLINE_TIME, $onlineTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_ONLINE_TIME, $onlineTime, $comparison);
    }

    /**
     * Filter the query on the last_online column
     *
     * Example usage:
     * <code>
     * $query->filterByLastOnline('2011-03-14'); // WHERE last_online = '2011-03-14'
     * $query->filterByLastOnline('now'); // WHERE last_online = '2011-03-14'
     * $query->filterByLastOnline(array('max' => 'yesterday')); // WHERE last_online > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastOnline The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByLastOnline($lastOnline = null, $comparison = null)
    {
        if (is_array($lastOnline)) {
            $useMinMax = false;
            if (isset($lastOnline['min'])) {
                $this->addUsingAlias(PlayerTableMap::COL_LAST_ONLINE, $lastOnline['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastOnline['max'])) {
                $this->addUsingAlias(PlayerTableMap::COL_LAST_ONLINE, $lastOnline['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PlayerTableMap::COL_LAST_ONLINE, $lastOnline, $comparison);
    }

    /**
     * Filter the query by a related \eXpansion\Bundle\LocalMapRatings\Model\Maprating object
     *
     * @param \eXpansion\Bundle\LocalMapRatings\Model\Maprating|ObjectCollection $maprating the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByMaprating($maprating, $comparison = null)
    {
        if ($maprating instanceof \eXpansion\Bundle\LocalMapRatings\Model\Maprating) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $maprating->getPlayerId(), $comparison);
        } elseif ($maprating instanceof ObjectCollection) {
            return $this
                ->useMapratingQuery()
                ->filterByPrimaryKeys($maprating->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMaprating() only accepts arguments of type \eXpansion\Bundle\LocalMapRatings\Model\Maprating or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Maprating relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinMaprating($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Maprating');

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
            $this->addJoinObject($join, 'Maprating');
        }

        return $this;
    }

    /**
     * Use the Maprating relation Maprating object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \eXpansion\Bundle\LocalMapRatings\Model\MapratingQuery A secondary query class using the current class as primary query
     */
    public function useMapratingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMaprating($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Maprating', '\eXpansion\Bundle\LocalMapRatings\Model\MapratingQuery');
    }

    /**
     * Filter the query by a related \eXpansion\Bundle\LocalRecords\Model\Record object
     *
     * @param \eXpansion\Bundle\LocalRecords\Model\Record|ObjectCollection $record the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPlayerQuery The current query, for fluid interface
     */
    public function filterByRecord($record, $comparison = null)
    {
        if ($record instanceof \eXpansion\Bundle\LocalRecords\Model\Record) {
            return $this
                ->addUsingAlias(PlayerTableMap::COL_ID, $record->getPlayerId(), $comparison);
        } elseif ($record instanceof ObjectCollection) {
            return $this
                ->useRecordQuery()
                ->filterByPrimaryKeys($record->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByRecord() only accepts arguments of type \eXpansion\Bundle\LocalRecords\Model\Record or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Record relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function joinRecord($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Record');

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
            $this->addJoinObject($join, 'Record');
        }

        return $this;
    }

    /**
     * Use the Record relation Record object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \eXpansion\Bundle\LocalRecords\Model\RecordQuery A secondary query class using the current class as primary query
     */
    public function useRecordQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRecord($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Record', '\eXpansion\Bundle\LocalRecords\Model\RecordQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPlayer $player Object to remove from the list of results
     *
     * @return $this|ChildPlayerQuery The current query, for fluid interface
     */
    public function prune($player = null)
    {
        if ($player) {
            $this->addUsingAlias(PlayerTableMap::COL_ID, $player->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the player table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PlayerTableMap::clearInstancePool();
            PlayerTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PlayerTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PlayerTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PlayerTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PlayerTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PlayerQuery
