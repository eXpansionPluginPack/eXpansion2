<?php

namespace eXpansion\Bundle\Maps\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use eXpansion\Bundle\Maps\Model\Map as ChildMap;
use eXpansion\Bundle\Maps\Model\MapQuery as ChildMapQuery;
use eXpansion\Bundle\Maps\Model\Mxmap as ChildMxmap;
use eXpansion\Bundle\Maps\Model\MxmapQuery as ChildMxmapQuery;
use eXpansion\Bundle\Maps\Model\Map\MapTableMap;
use eXpansion\Bundle\Maps\Model\Map\MxmapTableMap;

/**
 * Base class that represents a row from the 'map' table.
 *
 *
 *
 * @package    propel.generator.src\eXpansion\Bundle\Maps.Model.Base
 */
abstract class Map implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\eXpansion\\Bundle\\Maps\\Model\\Map\\MapTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the mapuid field.
     *
     * @var        string
     */
    protected $mapuid;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the filename field.
     *
     * @var        string
     */
    protected $filename;

    /**
     * The value for the author field.
     *
     * @var        string
     */
    protected $author;

    /**
     * The value for the environment field.
     *
     * @var        string
     */
    protected $environment;

    /**
     * The value for the mood field.
     *
     * @var        string
     */
    protected $mood;

    /**
     * The value for the bronzetime field.
     *
     * @var        int
     */
    protected $bronzetime;

    /**
     * The value for the silvertime field.
     *
     * @var        int
     */
    protected $silvertime;

    /**
     * The value for the goldtime field.
     *
     * @var        int
     */
    protected $goldtime;

    /**
     * The value for the authortime field.
     *
     * @var        int
     */
    protected $authortime;

    /**
     * The value for the copperprice field.
     *
     * @var        int
     */
    protected $copperprice;

    /**
     * The value for the laprace field.
     *
     * @var        boolean
     */
    protected $laprace;

    /**
     * The value for the nblaps field.
     *
     * @var        int
     */
    protected $nblaps;

    /**
     * The value for the npcheckpoints field.
     *
     * @var        int
     */
    protected $npcheckpoints;

    /**
     * The value for the maptype field.
     *
     * @var        string
     */
    protected $maptype;

    /**
     * The value for the mapstyle field.
     *
     * @var        string
     */
    protected $mapstyle;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildMxmap[] Collection to store aggregation of ChildMxmap objects.
     */
    protected $collMxmaps;
    protected $collMxmapsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMxmap[]
     */
    protected $mxmapsScheduledForDeletion = null;

    /**
     * Initializes internal state of eXpansion\Bundle\Maps\Model\Base\Map object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Map</code> instance.  If
     * <code>obj</code> is an instance of <code>Map</code>, delegates to
     * <code>equals(Map)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Map The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [mapuid] column value.
     *
     * @return string
     */
    public function getMapuid()
    {
        return $this->mapuid;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [filename] column value.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Get the [author] column value.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get the [environment] column value.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get the [mood] column value.
     *
     * @return string
     */
    public function getMood()
    {
        return $this->mood;
    }

    /**
     * Get the [bronzetime] column value.
     *
     * @return int
     */
    public function getBronzetime()
    {
        return $this->bronzetime;
    }

    /**
     * Get the [silvertime] column value.
     *
     * @return int
     */
    public function getSilvertime()
    {
        return $this->silvertime;
    }

    /**
     * Get the [goldtime] column value.
     *
     * @return int
     */
    public function getGoldtime()
    {
        return $this->goldtime;
    }

    /**
     * Get the [authortime] column value.
     *
     * @return int
     */
    public function getAuthortime()
    {
        return $this->authortime;
    }

    /**
     * Get the [copperprice] column value.
     *
     * @return int
     */
    public function getCopperprice()
    {
        return $this->copperprice;
    }

    /**
     * Get the [laprace] column value.
     *
     * @return boolean
     */
    public function getLaprace()
    {
        return $this->laprace;
    }

    /**
     * Get the [laprace] column value.
     *
     * @return boolean
     */
    public function isLaprace()
    {
        return $this->getLaprace();
    }

    /**
     * Get the [nblaps] column value.
     *
     * @return int
     */
    public function getNblaps()
    {
        return $this->nblaps;
    }

    /**
     * Get the [npcheckpoints] column value.
     *
     * @return int
     */
    public function getNpcheckpoints()
    {
        return $this->npcheckpoints;
    }

    /**
     * Get the [maptype] column value.
     *
     * @return string
     */
    public function getMaptype()
    {
        return $this->maptype;
    }

    /**
     * Get the [mapstyle] column value.
     *
     * @return string
     */
    public function getMapstyle()
    {
        return $this->mapstyle;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MapTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [mapuid] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setMapuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mapuid !== $v) {
            $this->mapuid = $v;
            $this->modifiedColumns[MapTableMap::COL_MAPUID] = true;
        }

        return $this;
    } // setMapuid()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[MapTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [filename] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setFilename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->filename !== $v) {
            $this->filename = $v;
            $this->modifiedColumns[MapTableMap::COL_FILENAME] = true;
        }

        return $this;
    } // setFilename()

    /**
     * Set the value of [author] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setAuthor($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->author !== $v) {
            $this->author = $v;
            $this->modifiedColumns[MapTableMap::COL_AUTHOR] = true;
        }

        return $this;
    } // setAuthor()

    /**
     * Set the value of [environment] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setEnvironment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->environment !== $v) {
            $this->environment = $v;
            $this->modifiedColumns[MapTableMap::COL_ENVIRONMENT] = true;
        }

        return $this;
    } // setEnvironment()

    /**
     * Set the value of [mood] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setMood($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mood !== $v) {
            $this->mood = $v;
            $this->modifiedColumns[MapTableMap::COL_MOOD] = true;
        }

        return $this;
    } // setMood()

    /**
     * Set the value of [bronzetime] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setBronzetime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->bronzetime !== $v) {
            $this->bronzetime = $v;
            $this->modifiedColumns[MapTableMap::COL_BRONZETIME] = true;
        }

        return $this;
    } // setBronzetime()

    /**
     * Set the value of [silvertime] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setSilvertime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->silvertime !== $v) {
            $this->silvertime = $v;
            $this->modifiedColumns[MapTableMap::COL_SILVERTIME] = true;
        }

        return $this;
    } // setSilvertime()

    /**
     * Set the value of [goldtime] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setGoldtime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->goldtime !== $v) {
            $this->goldtime = $v;
            $this->modifiedColumns[MapTableMap::COL_GOLDTIME] = true;
        }

        return $this;
    } // setGoldtime()

    /**
     * Set the value of [authortime] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setAuthortime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->authortime !== $v) {
            $this->authortime = $v;
            $this->modifiedColumns[MapTableMap::COL_AUTHORTIME] = true;
        }

        return $this;
    } // setAuthortime()

    /**
     * Set the value of [copperprice] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setCopperprice($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->copperprice !== $v) {
            $this->copperprice = $v;
            $this->modifiedColumns[MapTableMap::COL_COPPERPRICE] = true;
        }

        return $this;
    } // setCopperprice()

    /**
     * Sets the value of the [laprace] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setLaprace($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->laprace !== $v) {
            $this->laprace = $v;
            $this->modifiedColumns[MapTableMap::COL_LAPRACE] = true;
        }

        return $this;
    } // setLaprace()

    /**
     * Set the value of [nblaps] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setNblaps($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->nblaps !== $v) {
            $this->nblaps = $v;
            $this->modifiedColumns[MapTableMap::COL_NBLAPS] = true;
        }

        return $this;
    } // setNblaps()

    /**
     * Set the value of [npcheckpoints] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setNpcheckpoints($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->npcheckpoints !== $v) {
            $this->npcheckpoints = $v;
            $this->modifiedColumns[MapTableMap::COL_NPCHECKPOINTS] = true;
        }

        return $this;
    } // setNpcheckpoints()

    /**
     * Set the value of [maptype] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setMaptype($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->maptype !== $v) {
            $this->maptype = $v;
            $this->modifiedColumns[MapTableMap::COL_MAPTYPE] = true;
        }

        return $this;
    } // setMaptype()

    /**
     * Set the value of [mapstyle] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setMapstyle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mapstyle !== $v) {
            $this->mapstyle = $v;
            $this->modifiedColumns[MapTableMap::COL_MAPSTYLE] = true;
        }

        return $this;
    } // setMapstyle()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MapTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MapTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MapTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MapTableMap::translateFieldName('Mapuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mapuid = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MapTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MapTableMap::translateFieldName('Filename', TableMap::TYPE_PHPNAME, $indexType)];
            $this->filename = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MapTableMap::translateFieldName('Author', TableMap::TYPE_PHPNAME, $indexType)];
            $this->author = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MapTableMap::translateFieldName('Environment', TableMap::TYPE_PHPNAME, $indexType)];
            $this->environment = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MapTableMap::translateFieldName('Mood', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mood = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MapTableMap::translateFieldName('Bronzetime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bronzetime = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MapTableMap::translateFieldName('Silvertime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->silvertime = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : MapTableMap::translateFieldName('Goldtime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->goldtime = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : MapTableMap::translateFieldName('Authortime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->authortime = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : MapTableMap::translateFieldName('Copperprice', TableMap::TYPE_PHPNAME, $indexType)];
            $this->copperprice = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : MapTableMap::translateFieldName('Laprace', TableMap::TYPE_PHPNAME, $indexType)];
            $this->laprace = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : MapTableMap::translateFieldName('Nblaps', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nblaps = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : MapTableMap::translateFieldName('Npcheckpoints', TableMap::TYPE_PHPNAME, $indexType)];
            $this->npcheckpoints = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : MapTableMap::translateFieldName('Maptype', TableMap::TYPE_PHPNAME, $indexType)];
            $this->maptype = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : MapTableMap::translateFieldName('Mapstyle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mapstyle = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : MapTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : MapTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 19; // 19 = MapTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\eXpansion\\Bundle\\Maps\\Model\\Map'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MapTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMapQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collMxmaps = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Map::setDeleted()
     * @see Map::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMapQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MapTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(MapTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(MapTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(MapTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                MapTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->mxmapsScheduledForDeletion !== null) {
                if (!$this->mxmapsScheduledForDeletion->isEmpty()) {
                    \eXpansion\Bundle\Maps\Model\MxmapQuery::create()
                        ->filterByPrimaryKeys($this->mxmapsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mxmapsScheduledForDeletion = null;
                }
            }

            if ($this->collMxmaps !== null) {
                foreach ($this->collMxmaps as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[MapTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MapTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MapTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(MapTableMap::COL_MAPUID)) {
            $modifiedColumns[':p' . $index++]  = 'mapUid';
        }
        if ($this->isColumnModified(MapTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(MapTableMap::COL_FILENAME)) {
            $modifiedColumns[':p' . $index++]  = 'fileName';
        }
        if ($this->isColumnModified(MapTableMap::COL_AUTHOR)) {
            $modifiedColumns[':p' . $index++]  = 'author';
        }
        if ($this->isColumnModified(MapTableMap::COL_ENVIRONMENT)) {
            $modifiedColumns[':p' . $index++]  = 'environment';
        }
        if ($this->isColumnModified(MapTableMap::COL_MOOD)) {
            $modifiedColumns[':p' . $index++]  = 'mood';
        }
        if ($this->isColumnModified(MapTableMap::COL_BRONZETIME)) {
            $modifiedColumns[':p' . $index++]  = 'bronzeTime';
        }
        if ($this->isColumnModified(MapTableMap::COL_SILVERTIME)) {
            $modifiedColumns[':p' . $index++]  = 'silverTime';
        }
        if ($this->isColumnModified(MapTableMap::COL_GOLDTIME)) {
            $modifiedColumns[':p' . $index++]  = 'goldTime';
        }
        if ($this->isColumnModified(MapTableMap::COL_AUTHORTIME)) {
            $modifiedColumns[':p' . $index++]  = 'authorTime';
        }
        if ($this->isColumnModified(MapTableMap::COL_COPPERPRICE)) {
            $modifiedColumns[':p' . $index++]  = 'copperPrice';
        }
        if ($this->isColumnModified(MapTableMap::COL_LAPRACE)) {
            $modifiedColumns[':p' . $index++]  = 'lapRace';
        }
        if ($this->isColumnModified(MapTableMap::COL_NBLAPS)) {
            $modifiedColumns[':p' . $index++]  = 'nbLaps';
        }
        if ($this->isColumnModified(MapTableMap::COL_NPCHECKPOINTS)) {
            $modifiedColumns[':p' . $index++]  = 'npCheckpoints';
        }
        if ($this->isColumnModified(MapTableMap::COL_MAPTYPE)) {
            $modifiedColumns[':p' . $index++]  = 'mapType';
        }
        if ($this->isColumnModified(MapTableMap::COL_MAPSTYLE)) {
            $modifiedColumns[':p' . $index++]  = 'mapStyle';
        }
        if ($this->isColumnModified(MapTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(MapTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO map (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'mapUid':
                        $stmt->bindValue($identifier, $this->mapuid, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'fileName':
                        $stmt->bindValue($identifier, $this->filename, PDO::PARAM_STR);
                        break;
                    case 'author':
                        $stmt->bindValue($identifier, $this->author, PDO::PARAM_STR);
                        break;
                    case 'environment':
                        $stmt->bindValue($identifier, $this->environment, PDO::PARAM_STR);
                        break;
                    case 'mood':
                        $stmt->bindValue($identifier, $this->mood, PDO::PARAM_STR);
                        break;
                    case 'bronzeTime':
                        $stmt->bindValue($identifier, $this->bronzetime, PDO::PARAM_INT);
                        break;
                    case 'silverTime':
                        $stmt->bindValue($identifier, $this->silvertime, PDO::PARAM_INT);
                        break;
                    case 'goldTime':
                        $stmt->bindValue($identifier, $this->goldtime, PDO::PARAM_INT);
                        break;
                    case 'authorTime':
                        $stmt->bindValue($identifier, $this->authortime, PDO::PARAM_INT);
                        break;
                    case 'copperPrice':
                        $stmt->bindValue($identifier, $this->copperprice, PDO::PARAM_INT);
                        break;
                    case 'lapRace':
                        $stmt->bindValue($identifier, (int) $this->laprace, PDO::PARAM_INT);
                        break;
                    case 'nbLaps':
                        $stmt->bindValue($identifier, $this->nblaps, PDO::PARAM_INT);
                        break;
                    case 'npCheckpoints':
                        $stmt->bindValue($identifier, $this->npcheckpoints, PDO::PARAM_INT);
                        break;
                    case 'mapType':
                        $stmt->bindValue($identifier, $this->maptype, PDO::PARAM_STR);
                        break;
                    case 'mapStyle':
                        $stmt->bindValue($identifier, $this->mapstyle, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MapTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getMapuid();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getFilename();
                break;
            case 4:
                return $this->getAuthor();
                break;
            case 5:
                return $this->getEnvironment();
                break;
            case 6:
                return $this->getMood();
                break;
            case 7:
                return $this->getBronzetime();
                break;
            case 8:
                return $this->getSilvertime();
                break;
            case 9:
                return $this->getGoldtime();
                break;
            case 10:
                return $this->getAuthortime();
                break;
            case 11:
                return $this->getCopperprice();
                break;
            case 12:
                return $this->getLaprace();
                break;
            case 13:
                return $this->getNblaps();
                break;
            case 14:
                return $this->getNpcheckpoints();
                break;
            case 15:
                return $this->getMaptype();
                break;
            case 16:
                return $this->getMapstyle();
                break;
            case 17:
                return $this->getCreatedAt();
                break;
            case 18:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Map'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Map'][$this->hashCode()] = true;
        $keys = MapTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getMapuid(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getFilename(),
            $keys[4] => $this->getAuthor(),
            $keys[5] => $this->getEnvironment(),
            $keys[6] => $this->getMood(),
            $keys[7] => $this->getBronzetime(),
            $keys[8] => $this->getSilvertime(),
            $keys[9] => $this->getGoldtime(),
            $keys[10] => $this->getAuthortime(),
            $keys[11] => $this->getCopperprice(),
            $keys[12] => $this->getLaprace(),
            $keys[13] => $this->getNblaps(),
            $keys[14] => $this->getNpcheckpoints(),
            $keys[15] => $this->getMaptype(),
            $keys[16] => $this->getMapstyle(),
            $keys[17] => $this->getCreatedAt(),
            $keys[18] => $this->getUpdatedAt(),
        );
        if ($result[$keys[17]] instanceof \DateTime) {
            $result[$keys[17]] = $result[$keys[17]]->format('c');
        }

        if ($result[$keys[18]] instanceof \DateTime) {
            $result[$keys[18]] = $result[$keys[18]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collMxmaps) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'mxmaps';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'mxmaps';
                        break;
                    default:
                        $key = 'Mxmaps';
                }

                $result[$key] = $this->collMxmaps->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\eXpansion\Bundle\Maps\Model\Map
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MapTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\eXpansion\Bundle\Maps\Model\Map
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setMapuid($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setFilename($value);
                break;
            case 4:
                $this->setAuthor($value);
                break;
            case 5:
                $this->setEnvironment($value);
                break;
            case 6:
                $this->setMood($value);
                break;
            case 7:
                $this->setBronzetime($value);
                break;
            case 8:
                $this->setSilvertime($value);
                break;
            case 9:
                $this->setGoldtime($value);
                break;
            case 10:
                $this->setAuthortime($value);
                break;
            case 11:
                $this->setCopperprice($value);
                break;
            case 12:
                $this->setLaprace($value);
                break;
            case 13:
                $this->setNblaps($value);
                break;
            case 14:
                $this->setNpcheckpoints($value);
                break;
            case 15:
                $this->setMaptype($value);
                break;
            case 16:
                $this->setMapstyle($value);
                break;
            case 17:
                $this->setCreatedAt($value);
                break;
            case 18:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = MapTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setMapuid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setFilename($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAuthor($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setEnvironment($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setMood($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setBronzetime($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setSilvertime($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setGoldtime($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setAuthortime($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCopperprice($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setLaprace($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setNblaps($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setNpcheckpoints($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setMaptype($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setMapstyle($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setCreatedAt($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setUpdatedAt($arr[$keys[18]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MapTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MapTableMap::COL_ID)) {
            $criteria->add(MapTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MapTableMap::COL_MAPUID)) {
            $criteria->add(MapTableMap::COL_MAPUID, $this->mapuid);
        }
        if ($this->isColumnModified(MapTableMap::COL_NAME)) {
            $criteria->add(MapTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(MapTableMap::COL_FILENAME)) {
            $criteria->add(MapTableMap::COL_FILENAME, $this->filename);
        }
        if ($this->isColumnModified(MapTableMap::COL_AUTHOR)) {
            $criteria->add(MapTableMap::COL_AUTHOR, $this->author);
        }
        if ($this->isColumnModified(MapTableMap::COL_ENVIRONMENT)) {
            $criteria->add(MapTableMap::COL_ENVIRONMENT, $this->environment);
        }
        if ($this->isColumnModified(MapTableMap::COL_MOOD)) {
            $criteria->add(MapTableMap::COL_MOOD, $this->mood);
        }
        if ($this->isColumnModified(MapTableMap::COL_BRONZETIME)) {
            $criteria->add(MapTableMap::COL_BRONZETIME, $this->bronzetime);
        }
        if ($this->isColumnModified(MapTableMap::COL_SILVERTIME)) {
            $criteria->add(MapTableMap::COL_SILVERTIME, $this->silvertime);
        }
        if ($this->isColumnModified(MapTableMap::COL_GOLDTIME)) {
            $criteria->add(MapTableMap::COL_GOLDTIME, $this->goldtime);
        }
        if ($this->isColumnModified(MapTableMap::COL_AUTHORTIME)) {
            $criteria->add(MapTableMap::COL_AUTHORTIME, $this->authortime);
        }
        if ($this->isColumnModified(MapTableMap::COL_COPPERPRICE)) {
            $criteria->add(MapTableMap::COL_COPPERPRICE, $this->copperprice);
        }
        if ($this->isColumnModified(MapTableMap::COL_LAPRACE)) {
            $criteria->add(MapTableMap::COL_LAPRACE, $this->laprace);
        }
        if ($this->isColumnModified(MapTableMap::COL_NBLAPS)) {
            $criteria->add(MapTableMap::COL_NBLAPS, $this->nblaps);
        }
        if ($this->isColumnModified(MapTableMap::COL_NPCHECKPOINTS)) {
            $criteria->add(MapTableMap::COL_NPCHECKPOINTS, $this->npcheckpoints);
        }
        if ($this->isColumnModified(MapTableMap::COL_MAPTYPE)) {
            $criteria->add(MapTableMap::COL_MAPTYPE, $this->maptype);
        }
        if ($this->isColumnModified(MapTableMap::COL_MAPSTYLE)) {
            $criteria->add(MapTableMap::COL_MAPSTYLE, $this->mapstyle);
        }
        if ($this->isColumnModified(MapTableMap::COL_CREATED_AT)) {
            $criteria->add(MapTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(MapTableMap::COL_UPDATED_AT)) {
            $criteria->add(MapTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildMapQuery::create();
        $criteria->add(MapTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \eXpansion\Bundle\Maps\Model\Map (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setMapuid($this->getMapuid());
        $copyObj->setName($this->getName());
        $copyObj->setFilename($this->getFilename());
        $copyObj->setAuthor($this->getAuthor());
        $copyObj->setEnvironment($this->getEnvironment());
        $copyObj->setMood($this->getMood());
        $copyObj->setBronzetime($this->getBronzetime());
        $copyObj->setSilvertime($this->getSilvertime());
        $copyObj->setGoldtime($this->getGoldtime());
        $copyObj->setAuthortime($this->getAuthortime());
        $copyObj->setCopperprice($this->getCopperprice());
        $copyObj->setLaprace($this->getLaprace());
        $copyObj->setNblaps($this->getNblaps());
        $copyObj->setNpcheckpoints($this->getNpcheckpoints());
        $copyObj->setMaptype($this->getMaptype());
        $copyObj->setMapstyle($this->getMapstyle());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMxmaps() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMxmap($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \eXpansion\Bundle\Maps\Model\Map Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Mxmap' == $relationName) {
            return $this->initMxmaps();
        }
    }

    /**
     * Clears out the collMxmaps collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMxmaps()
     */
    public function clearMxmaps()
    {
        $this->collMxmaps = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMxmaps collection loaded partially.
     */
    public function resetPartialMxmaps($v = true)
    {
        $this->collMxmapsPartial = $v;
    }

    /**
     * Initializes the collMxmaps collection.
     *
     * By default this just sets the collMxmaps collection to an empty array (like clearcollMxmaps());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMxmaps($overrideExisting = true)
    {
        if (null !== $this->collMxmaps && !$overrideExisting) {
            return;
        }

        $collectionClassName = MxmapTableMap::getTableMap()->getCollectionClassName();

        $this->collMxmaps = new $collectionClassName;
        $this->collMxmaps->setModel('\eXpansion\Bundle\Maps\Model\Mxmap');
    }

    /**
     * Gets an array of ChildMxmap objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMap is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMxmap[] List of ChildMxmap objects
     * @throws PropelException
     */
    public function getMxmaps(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMxmapsPartial && !$this->isNew();
        if (null === $this->collMxmaps || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMxmaps) {
                // return empty collection
                $this->initMxmaps();
            } else {
                $collMxmaps = ChildMxmapQuery::create(null, $criteria)
                    ->filterByMap($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMxmapsPartial && count($collMxmaps)) {
                        $this->initMxmaps(false);

                        foreach ($collMxmaps as $obj) {
                            if (false == $this->collMxmaps->contains($obj)) {
                                $this->collMxmaps->append($obj);
                            }
                        }

                        $this->collMxmapsPartial = true;
                    }

                    return $collMxmaps;
                }

                if ($partial && $this->collMxmaps) {
                    foreach ($this->collMxmaps as $obj) {
                        if ($obj->isNew()) {
                            $collMxmaps[] = $obj;
                        }
                    }
                }

                $this->collMxmaps = $collMxmaps;
                $this->collMxmapsPartial = false;
            }
        }

        return $this->collMxmaps;
    }

    /**
     * Sets a collection of ChildMxmap objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $mxmaps A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMap The current object (for fluent API support)
     */
    public function setMxmaps(Collection $mxmaps, ConnectionInterface $con = null)
    {
        /** @var ChildMxmap[] $mxmapsToDelete */
        $mxmapsToDelete = $this->getMxmaps(new Criteria(), $con)->diff($mxmaps);


        $this->mxmapsScheduledForDeletion = $mxmapsToDelete;

        foreach ($mxmapsToDelete as $mxmapRemoved) {
            $mxmapRemoved->setMap(null);
        }

        $this->collMxmaps = null;
        foreach ($mxmaps as $mxmap) {
            $this->addMxmap($mxmap);
        }

        $this->collMxmaps = $mxmaps;
        $this->collMxmapsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Mxmap objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Mxmap objects.
     * @throws PropelException
     */
    public function countMxmaps(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMxmapsPartial && !$this->isNew();
        if (null === $this->collMxmaps || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMxmaps) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMxmaps());
            }

            $query = ChildMxmapQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMap($this)
                ->count($con);
        }

        return count($this->collMxmaps);
    }

    /**
     * Method called to associate a ChildMxmap object to this object
     * through the ChildMxmap foreign key attribute.
     *
     * @param  ChildMxmap $l ChildMxmap
     * @return $this|\eXpansion\Bundle\Maps\Model\Map The current object (for fluent API support)
     */
    public function addMxmap(ChildMxmap $l)
    {
        if ($this->collMxmaps === null) {
            $this->initMxmaps();
            $this->collMxmapsPartial = true;
        }

        if (!$this->collMxmaps->contains($l)) {
            $this->doAddMxmap($l);

            if ($this->mxmapsScheduledForDeletion and $this->mxmapsScheduledForDeletion->contains($l)) {
                $this->mxmapsScheduledForDeletion->remove($this->mxmapsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMxmap $mxmap The ChildMxmap object to add.
     */
    protected function doAddMxmap(ChildMxmap $mxmap)
    {
        $this->collMxmaps[]= $mxmap;
        $mxmap->setMap($this);
    }

    /**
     * @param  ChildMxmap $mxmap The ChildMxmap object to remove.
     * @return $this|ChildMap The current object (for fluent API support)
     */
    public function removeMxmap(ChildMxmap $mxmap)
    {
        if ($this->getMxmaps()->contains($mxmap)) {
            $pos = $this->collMxmaps->search($mxmap);
            $this->collMxmaps->remove($pos);
            if (null === $this->mxmapsScheduledForDeletion) {
                $this->mxmapsScheduledForDeletion = clone $this->collMxmaps;
                $this->mxmapsScheduledForDeletion->clear();
            }
            $this->mxmapsScheduledForDeletion[]= clone $mxmap;
            $mxmap->setMap(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->mapuid = null;
        $this->name = null;
        $this->filename = null;
        $this->author = null;
        $this->environment = null;
        $this->mood = null;
        $this->bronzetime = null;
        $this->silvertime = null;
        $this->goldtime = null;
        $this->authortime = null;
        $this->copperprice = null;
        $this->laprace = null;
        $this->nblaps = null;
        $this->npcheckpoints = null;
        $this->maptype = null;
        $this->mapstyle = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collMxmaps) {
                foreach ($this->collMxmaps as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMxmaps = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MapTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildMap The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[MapTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
