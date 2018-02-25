<?php

namespace eXpansion\Framework\PlayersBundle\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use eXpansion\Framework\PlayersBundle\Model\Gamecurrency as ChildGamecurrency;
use eXpansion\Framework\PlayersBundle\Model\GamecurrencyQuery as ChildGamecurrencyQuery;
use eXpansion\Framework\PlayersBundle\Model\Map\GamecurrencyTableMap;

/**
 * Base class that represents a query for the 'gamecurrency' table.
 *
 *
 *
 * @method     ChildGamecurrencyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildGamecurrencyQuery orderBySenderlogin($order = Criteria::ASC) Order by the senderLogin column
 * @method     ChildGamecurrencyQuery orderByReceiverlogin($order = Criteria::ASC) Order by the receiverLogin column
 * @method     ChildGamecurrencyQuery orderByTransactionid($order = Criteria::ASC) Order by the transactionId column
 * @method     ChildGamecurrencyQuery orderByBillid($order = Criteria::ASC) Order by the billId column
 * @method     ChildGamecurrencyQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildGamecurrencyQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     ChildGamecurrencyQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildGamecurrencyQuery orderByDatetime($order = Criteria::ASC) Order by the datetime column
 *
 * @method     ChildGamecurrencyQuery groupById() Group by the id column
 * @method     ChildGamecurrencyQuery groupBySenderlogin() Group by the senderLogin column
 * @method     ChildGamecurrencyQuery groupByReceiverlogin() Group by the receiverLogin column
 * @method     ChildGamecurrencyQuery groupByTransactionid() Group by the transactionId column
 * @method     ChildGamecurrencyQuery groupByBillid() Group by the billId column
 * @method     ChildGamecurrencyQuery groupByAmount() Group by the amount column
 * @method     ChildGamecurrencyQuery groupByMessage() Group by the message column
 * @method     ChildGamecurrencyQuery groupByStatus() Group by the status column
 * @method     ChildGamecurrencyQuery groupByDatetime() Group by the datetime column
 *
 * @method     ChildGamecurrencyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildGamecurrencyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildGamecurrencyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildGamecurrencyQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildGamecurrencyQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildGamecurrencyQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildGamecurrency findOne(ConnectionInterface $con = null) Return the first ChildGamecurrency matching the query
 * @method     ChildGamecurrency findOneOrCreate(ConnectionInterface $con = null) Return the first ChildGamecurrency matching the query, or a new ChildGamecurrency object populated from the query conditions when no match is found
 *
 * @method     ChildGamecurrency findOneById(int $id) Return the first ChildGamecurrency filtered by the id column
 * @method     ChildGamecurrency findOneBySenderlogin(string $senderLogin) Return the first ChildGamecurrency filtered by the senderLogin column
 * @method     ChildGamecurrency findOneByReceiverlogin(string $receiverLogin) Return the first ChildGamecurrency filtered by the receiverLogin column
 * @method     ChildGamecurrency findOneByTransactionid(int $transactionId) Return the first ChildGamecurrency filtered by the transactionId column
 * @method     ChildGamecurrency findOneByBillid(int $billId) Return the first ChildGamecurrency filtered by the billId column
 * @method     ChildGamecurrency findOneByAmount(int $amount) Return the first ChildGamecurrency filtered by the amount column
 * @method     ChildGamecurrency findOneByMessage(string $message) Return the first ChildGamecurrency filtered by the message column
 * @method     ChildGamecurrency findOneByStatus(int $status) Return the first ChildGamecurrency filtered by the status column
 * @method     ChildGamecurrency findOneByDatetime(string $datetime) Return the first ChildGamecurrency filtered by the datetime column *

 * @method     ChildGamecurrency requirePk($key, ConnectionInterface $con = null) Return the ChildGamecurrency by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOne(ConnectionInterface $con = null) Return the first ChildGamecurrency matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGamecurrency requireOneById(int $id) Return the first ChildGamecurrency filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneBySenderlogin(string $senderLogin) Return the first ChildGamecurrency filtered by the senderLogin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneByReceiverlogin(string $receiverLogin) Return the first ChildGamecurrency filtered by the receiverLogin column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneByTransactionid(int $transactionId) Return the first ChildGamecurrency filtered by the transactionId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneByBillid(int $billId) Return the first ChildGamecurrency filtered by the billId column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneByAmount(int $amount) Return the first ChildGamecurrency filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneByMessage(string $message) Return the first ChildGamecurrency filtered by the message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneByStatus(int $status) Return the first ChildGamecurrency filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildGamecurrency requireOneByDatetime(string $datetime) Return the first ChildGamecurrency filtered by the datetime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildGamecurrency[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildGamecurrency objects based on current ModelCriteria
 * @method     ChildGamecurrency[]|ObjectCollection findById(int $id) Return ChildGamecurrency objects filtered by the id column
 * @method     ChildGamecurrency[]|ObjectCollection findBySenderlogin(string $senderLogin) Return ChildGamecurrency objects filtered by the senderLogin column
 * @method     ChildGamecurrency[]|ObjectCollection findByReceiverlogin(string $receiverLogin) Return ChildGamecurrency objects filtered by the receiverLogin column
 * @method     ChildGamecurrency[]|ObjectCollection findByTransactionid(int $transactionId) Return ChildGamecurrency objects filtered by the transactionId column
 * @method     ChildGamecurrency[]|ObjectCollection findByBillid(int $billId) Return ChildGamecurrency objects filtered by the billId column
 * @method     ChildGamecurrency[]|ObjectCollection findByAmount(int $amount) Return ChildGamecurrency objects filtered by the amount column
 * @method     ChildGamecurrency[]|ObjectCollection findByMessage(string $message) Return ChildGamecurrency objects filtered by the message column
 * @method     ChildGamecurrency[]|ObjectCollection findByStatus(int $status) Return ChildGamecurrency objects filtered by the status column
 * @method     ChildGamecurrency[]|ObjectCollection findByDatetime(string $datetime) Return ChildGamecurrency objects filtered by the datetime column
 * @method     ChildGamecurrency[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class GamecurrencyQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \eXpansion\Framework\PlayersBundle\Model\Base\GamecurrencyQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'expansion', $modelName = '\\eXpansion\\Framework\\PlayersBundle\\Model\\Gamecurrency', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildGamecurrencyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildGamecurrencyQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildGamecurrencyQuery) {
            return $criteria;
        }
        $query = new ChildGamecurrencyQuery();
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
     * @return ChildGamecurrency|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(GamecurrencyTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = GamecurrencyTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildGamecurrency A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, senderLogin, receiverLogin, transactionId, billId, amount, message, status, datetime FROM gamecurrency WHERE id = :p0';
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
            /** @var ChildGamecurrency $obj */
            $obj = new ChildGamecurrency();
            $obj->hydrate($row);
            GamecurrencyTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildGamecurrency|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(GamecurrencyTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(GamecurrencyTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the senderLogin column
     *
     * Example usage:
     * <code>
     * $query->filterBySenderlogin('fooValue');   // WHERE senderLogin = 'fooValue'
     * $query->filterBySenderlogin('%fooValue%', Criteria::LIKE); // WHERE senderLogin LIKE '%fooValue%'
     * </code>
     *
     * @param     string $senderlogin The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterBySenderlogin($senderlogin = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($senderlogin)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_SENDERLOGIN, $senderlogin, $comparison);
    }

    /**
     * Filter the query on the receiverLogin column
     *
     * Example usage:
     * <code>
     * $query->filterByReceiverlogin('fooValue');   // WHERE receiverLogin = 'fooValue'
     * $query->filterByReceiverlogin('%fooValue%', Criteria::LIKE); // WHERE receiverLogin LIKE '%fooValue%'
     * </code>
     *
     * @param     string $receiverlogin The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByReceiverlogin($receiverlogin = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($receiverlogin)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_RECEIVERLOGIN, $receiverlogin, $comparison);
    }

    /**
     * Filter the query on the transactionId column
     *
     * Example usage:
     * <code>
     * $query->filterByTransactionid(1234); // WHERE transactionId = 1234
     * $query->filterByTransactionid(array(12, 34)); // WHERE transactionId IN (12, 34)
     * $query->filterByTransactionid(array('min' => 12)); // WHERE transactionId > 12
     * </code>
     *
     * @param     mixed $transactionid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByTransactionid($transactionid = null, $comparison = null)
    {
        if (is_array($transactionid)) {
            $useMinMax = false;
            if (isset($transactionid['min'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_TRANSACTIONID, $transactionid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($transactionid['max'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_TRANSACTIONID, $transactionid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_TRANSACTIONID, $transactionid, $comparison);
    }

    /**
     * Filter the query on the billId column
     *
     * Example usage:
     * <code>
     * $query->filterByBillid(1234); // WHERE billId = 1234
     * $query->filterByBillid(array(12, 34)); // WHERE billId IN (12, 34)
     * $query->filterByBillid(array('min' => 12)); // WHERE billId > 12
     * </code>
     *
     * @param     mixed $billid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByBillid($billid = null, $comparison = null)
    {
        if (is_array($billid)) {
            $useMinMax = false;
            if (isset($billid['min'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_BILLID, $billid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($billid['max'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_BILLID, $billid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_BILLID, $billid, $comparison);
    }

    /**
     * Filter the query on the amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%', Criteria::LIKE); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the datetime column
     *
     * Example usage:
     * <code>
     * $query->filterByDatetime('2011-03-14'); // WHERE datetime = '2011-03-14'
     * $query->filterByDatetime('now'); // WHERE datetime = '2011-03-14'
     * $query->filterByDatetime(array('max' => 'yesterday')); // WHERE datetime > '2011-03-13'
     * </code>
     *
     * @param     mixed $datetime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function filterByDatetime($datetime = null, $comparison = null)
    {
        if (is_array($datetime)) {
            $useMinMax = false;
            if (isset($datetime['min'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_DATETIME, $datetime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datetime['max'])) {
                $this->addUsingAlias(GamecurrencyTableMap::COL_DATETIME, $datetime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(GamecurrencyTableMap::COL_DATETIME, $datetime, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildGamecurrency $gamecurrency Object to remove from the list of results
     *
     * @return $this|ChildGamecurrencyQuery The current query, for fluid interface
     */
    public function prune($gamecurrency = null)
    {
        if ($gamecurrency) {
            $this->addUsingAlias(GamecurrencyTableMap::COL_ID, $gamecurrency->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the gamecurrency table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(GamecurrencyTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            GamecurrencyTableMap::clearInstancePool();
            GamecurrencyTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(GamecurrencyTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(GamecurrencyTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            GamecurrencyTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            GamecurrencyTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // GamecurrencyQuery
