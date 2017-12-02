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
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use eXpansion\Bundle\Maps\Model\Map as ChildMap;
use eXpansion\Bundle\Maps\Model\MapQuery as ChildMapQuery;
use eXpansion\Bundle\Maps\Model\MxmapQuery as ChildMxmapQuery;
use eXpansion\Bundle\Maps\Model\Map\MxmapTableMap;

/**
 * Base class that represents a row from the 'mxmap' table.
 *
 *
 *
 * @package    propel.generator.src\eXpansion\Bundle\Maps.Model.Base
 */
abstract class Mxmap implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\eXpansion\\Bundle\\Maps\\Model\\Map\\MxmapTableMap';


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
     * The value for the trackuid field.
     *
     * @var        string
     */
    protected $trackuid;

    /**
     * The value for the gbxmapname field.
     *
     * @var        string
     */
    protected $gbxmapname;

    /**
     * The value for the trackid field.
     *
     * @var        int
     */
    protected $trackid;

    /**
     * The value for the userid field.
     *
     * @var        int
     */
    protected $userid;

    /**
     * The value for the username field.
     *
     * @var        string
     */
    protected $username;

    /**
     * The value for the uploadedat field.
     *
     * @var        DateTime
     */
    protected $uploadedat;

    /**
     * The value for the updatedat field.
     *
     * @var        DateTime
     */
    protected $updatedat;

    /**
     * The value for the maptype field.
     *
     * @var        string
     */
    protected $maptype;

    /**
     * The value for the titlepack field.
     *
     * @var        string
     */
    protected $titlepack;

    /**
     * The value for the stylename field.
     *
     * @var        string
     */
    protected $stylename;

    /**
     * The value for the displaycost field.
     *
     * @var        int
     */
    protected $displaycost;

    /**
     * The value for the modname field.
     *
     * @var        string
     */
    protected $modname;

    /**
     * The value for the lightmap field.
     *
     * @var        int
     */
    protected $lightmap;

    /**
     * The value for the exeversion field.
     *
     * @var        string
     */
    protected $exeversion;

    /**
     * The value for the exebuild field.
     *
     * @var        string
     */
    protected $exebuild;

    /**
     * The value for the environmentname field.
     *
     * @var        string
     */
    protected $environmentname;

    /**
     * The value for the vehiclename field.
     *
     * @var        string
     */
    protected $vehiclename;

    /**
     * The value for the unlimiterrequired field.
     *
     * @var        boolean
     */
    protected $unlimiterrequired;

    /**
     * The value for the routename field.
     *
     * @var        string
     */
    protected $routename;

    /**
     * The value for the lengthname field.
     *
     * @var        string
     */
    protected $lengthname;

    /**
     * The value for the laps field.
     *
     * @var        int
     */
    protected $laps;

    /**
     * The value for the difficultyname field.
     *
     * @var        string
     */
    protected $difficultyname;

    /**
     * The value for the replaytypename field.
     *
     * @var        string
     */
    protected $replaytypename;

    /**
     * The value for the replaywrid field.
     *
     * @var        int
     */
    protected $replaywrid;

    /**
     * The value for the replaywrtime field.
     *
     * @var        int
     */
    protected $replaywrtime;

    /**
     * The value for the replaywruserid field.
     *
     * @var        int
     */
    protected $replaywruserid;

    /**
     * The value for the replaywrusername field.
     *
     * @var        string
     */
    protected $replaywrusername;

    /**
     * The value for the ratingvotecount field.
     *
     * @var        int
     */
    protected $ratingvotecount;

    /**
     * The value for the ratingvoteaverage field.
     *
     * @var        double
     */
    protected $ratingvoteaverage;

    /**
     * The value for the replaycount field.
     *
     * @var        int
     */
    protected $replaycount;

    /**
     * The value for the trackvalue field.
     *
     * @var        int
     */
    protected $trackvalue;

    /**
     * The value for the comments field.
     *
     * @var        string
     */
    protected $comments;

    /**
     * The value for the commentscount field.
     *
     * @var        int
     */
    protected $commentscount;

    /**
     * The value for the awardcount field.
     *
     * @var        int
     */
    protected $awardcount;

    /**
     * The value for the hasscreenshot field.
     *
     * @var        boolean
     */
    protected $hasscreenshot;

    /**
     * The value for the hasthumbnail field.
     *
     * @var        boolean
     */
    protected $hasthumbnail;

    /**
     * The value for the hasghostblocks field.
     *
     * @var        boolean
     */
    protected $hasghostblocks;

    /**
     * The value for the embeddedobjectscount field.
     *
     * @var        int
     */
    protected $embeddedobjectscount;

    /**
     * @var        ChildMap
     */
    protected $aMap;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of eXpansion\Bundle\Maps\Model\Base\Mxmap object.
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
     * Compares this with another <code>Mxmap</code> instance.  If
     * <code>obj</code> is an instance of <code>Mxmap</code>, delegates to
     * <code>equals(Mxmap)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Mxmap The current object, for fluid interface
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
     * Get the [trackuid] column value.
     *
     * @return string
     */
    public function getTrackuid()
    {
        return $this->trackuid;
    }

    /**
     * Get the [gbxmapname] column value.
     *
     * @return string
     */
    public function getGbxmapname()
    {
        return $this->gbxmapname;
    }

    /**
     * Get the [trackid] column value.
     *
     * @return int
     */
    public function getTrackid()
    {
        return $this->trackid;
    }

    /**
     * Get the [userid] column value.
     *
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [optionally formatted] temporal [uploadedat] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUploadedat($format = NULL)
    {
        if ($format === null) {
            return $this->uploadedat;
        } else {
            return $this->uploadedat instanceof \DateTimeInterface ? $this->uploadedat->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updatedat] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedat($format = NULL)
    {
        if ($format === null) {
            return $this->updatedat;
        } else {
            return $this->updatedat instanceof \DateTimeInterface ? $this->updatedat->format($format) : null;
        }
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
     * Get the [titlepack] column value.
     *
     * @return string
     */
    public function getTitlepack()
    {
        return $this->titlepack;
    }

    /**
     * Get the [stylename] column value.
     *
     * @return string
     */
    public function getStylename()
    {
        return $this->stylename;
    }

    /**
     * Get the [displaycost] column value.
     *
     * @return int
     */
    public function getDisplaycost()
    {
        return $this->displaycost;
    }

    /**
     * Get the [modname] column value.
     *
     * @return string
     */
    public function getModname()
    {
        return $this->modname;
    }

    /**
     * Get the [lightmap] column value.
     *
     * @return int
     */
    public function getLightmap()
    {
        return $this->lightmap;
    }

    /**
     * Get the [exeversion] column value.
     *
     * @return string
     */
    public function getExeversion()
    {
        return $this->exeversion;
    }

    /**
     * Get the [exebuild] column value.
     *
     * @return string
     */
    public function getExebuild()
    {
        return $this->exebuild;
    }

    /**
     * Get the [environmentname] column value.
     *
     * @return string
     */
    public function getEnvironmentname()
    {
        return $this->environmentname;
    }

    /**
     * Get the [vehiclename] column value.
     *
     * @return string
     */
    public function getVehiclename()
    {
        return $this->vehiclename;
    }

    /**
     * Get the [unlimiterrequired] column value.
     *
     * @return boolean
     */
    public function getUnlimiterrequired()
    {
        return $this->unlimiterrequired;
    }

    /**
     * Get the [unlimiterrequired] column value.
     *
     * @return boolean
     */
    public function isUnlimiterrequired()
    {
        return $this->getUnlimiterrequired();
    }

    /**
     * Get the [routename] column value.
     *
     * @return string
     */
    public function getRoutename()
    {
        return $this->routename;
    }

    /**
     * Get the [lengthname] column value.
     *
     * @return string
     */
    public function getLengthname()
    {
        return $this->lengthname;
    }

    /**
     * Get the [laps] column value.
     *
     * @return int
     */
    public function getLaps()
    {
        return $this->laps;
    }

    /**
     * Get the [difficultyname] column value.
     *
     * @return string
     */
    public function getDifficultyname()
    {
        return $this->difficultyname;
    }

    /**
     * Get the [replaytypename] column value.
     *
     * @return string
     */
    public function getReplaytypename()
    {
        return $this->replaytypename;
    }

    /**
     * Get the [replaywrid] column value.
     *
     * @return int
     */
    public function getReplaywrid()
    {
        return $this->replaywrid;
    }

    /**
     * Get the [replaywrtime] column value.
     *
     * @return int
     */
    public function getReplaywrtime()
    {
        return $this->replaywrtime;
    }

    /**
     * Get the [replaywruserid] column value.
     *
     * @return int
     */
    public function getReplaywruserid()
    {
        return $this->replaywruserid;
    }

    /**
     * Get the [replaywrusername] column value.
     *
     * @return string
     */
    public function getReplaywrusername()
    {
        return $this->replaywrusername;
    }

    /**
     * Get the [ratingvotecount] column value.
     *
     * @return int
     */
    public function getRatingvotecount()
    {
        return $this->ratingvotecount;
    }

    /**
     * Get the [ratingvoteaverage] column value.
     *
     * @return double
     */
    public function getRatingvoteaverage()
    {
        return $this->ratingvoteaverage;
    }

    /**
     * Get the [replaycount] column value.
     *
     * @return int
     */
    public function getReplaycount()
    {
        return $this->replaycount;
    }

    /**
     * Get the [trackvalue] column value.
     *
     * @return int
     */
    public function getTrackvalue()
    {
        return $this->trackvalue;
    }

    /**
     * Get the [comments] column value.
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get the [commentscount] column value.
     *
     * @return int
     */
    public function getCommentscount()
    {
        return $this->commentscount;
    }

    /**
     * Get the [awardcount] column value.
     *
     * @return int
     */
    public function getAwardcount()
    {
        return $this->awardcount;
    }

    /**
     * Get the [hasscreenshot] column value.
     *
     * @return boolean
     */
    public function getHasscreenshot()
    {
        return $this->hasscreenshot;
    }

    /**
     * Get the [hasscreenshot] column value.
     *
     * @return boolean
     */
    public function isHasscreenshot()
    {
        return $this->getHasscreenshot();
    }

    /**
     * Get the [hasthumbnail] column value.
     *
     * @return boolean
     */
    public function getHasthumbnail()
    {
        return $this->hasthumbnail;
    }

    /**
     * Get the [hasthumbnail] column value.
     *
     * @return boolean
     */
    public function isHasthumbnail()
    {
        return $this->getHasthumbnail();
    }

    /**
     * Get the [hasghostblocks] column value.
     *
     * @return boolean
     */
    public function getHasghostblocks()
    {
        return $this->hasghostblocks;
    }

    /**
     * Get the [hasghostblocks] column value.
     *
     * @return boolean
     */
    public function isHasghostblocks()
    {
        return $this->getHasghostblocks();
    }

    /**
     * Get the [embeddedobjectscount] column value.
     *
     * @return int
     */
    public function getEmbeddedobjectscount()
    {
        return $this->embeddedobjectscount;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MxmapTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [trackuid] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setTrackuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->trackuid !== $v) {
            $this->trackuid = $v;
            $this->modifiedColumns[MxmapTableMap::COL_TRACKUID] = true;
        }

        if ($this->aMap !== null && $this->aMap->getMapuid() !== $v) {
            $this->aMap = null;
        }

        return $this;
    } // setTrackuid()

    /**
     * Set the value of [gbxmapname] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setGbxmapname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->gbxmapname !== $v) {
            $this->gbxmapname = $v;
            $this->modifiedColumns[MxmapTableMap::COL_GBXMAPNAME] = true;
        }

        return $this;
    } // setGbxmapname()

    /**
     * Set the value of [trackid] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setTrackid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->trackid !== $v) {
            $this->trackid = $v;
            $this->modifiedColumns[MxmapTableMap::COL_TRACKID] = true;
        }

        return $this;
    } // setTrackid()

    /**
     * Set the value of [userid] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setUserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->userid !== $v) {
            $this->userid = $v;
            $this->modifiedColumns[MxmapTableMap::COL_USERID] = true;
        }

        return $this;
    } // setUserid()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[MxmapTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Sets the value of [uploadedat] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setUploadedat($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->uploadedat !== null || $dt !== null) {
            if ($this->uploadedat === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->uploadedat->format("Y-m-d H:i:s.u")) {
                $this->uploadedat = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MxmapTableMap::COL_UPLOADEDAT] = true;
            }
        } // if either are not null

        return $this;
    } // setUploadedat()

    /**
     * Sets the value of [updatedat] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setUpdatedat($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updatedat !== null || $dt !== null) {
            if ($this->updatedat === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updatedat->format("Y-m-d H:i:s.u")) {
                $this->updatedat = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MxmapTableMap::COL_UPDATEDAT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedat()

    /**
     * Set the value of [maptype] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setMaptype($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->maptype !== $v) {
            $this->maptype = $v;
            $this->modifiedColumns[MxmapTableMap::COL_MAPTYPE] = true;
        }

        return $this;
    } // setMaptype()

    /**
     * Set the value of [titlepack] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setTitlepack($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->titlepack !== $v) {
            $this->titlepack = $v;
            $this->modifiedColumns[MxmapTableMap::COL_TITLEPACK] = true;
        }

        return $this;
    } // setTitlepack()

    /**
     * Set the value of [stylename] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setStylename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->stylename !== $v) {
            $this->stylename = $v;
            $this->modifiedColumns[MxmapTableMap::COL_STYLENAME] = true;
        }

        return $this;
    } // setStylename()

    /**
     * Set the value of [displaycost] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setDisplaycost($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->displaycost !== $v) {
            $this->displaycost = $v;
            $this->modifiedColumns[MxmapTableMap::COL_DISPLAYCOST] = true;
        }

        return $this;
    } // setDisplaycost()

    /**
     * Set the value of [modname] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setModname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->modname !== $v) {
            $this->modname = $v;
            $this->modifiedColumns[MxmapTableMap::COL_MODNAME] = true;
        }

        return $this;
    } // setModname()

    /**
     * Set the value of [lightmap] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setLightmap($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->lightmap !== $v) {
            $this->lightmap = $v;
            $this->modifiedColumns[MxmapTableMap::COL_LIGHTMAP] = true;
        }

        return $this;
    } // setLightmap()

    /**
     * Set the value of [exeversion] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setExeversion($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->exeversion !== $v) {
            $this->exeversion = $v;
            $this->modifiedColumns[MxmapTableMap::COL_EXEVERSION] = true;
        }

        return $this;
    } // setExeversion()

    /**
     * Set the value of [exebuild] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setExebuild($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->exebuild !== $v) {
            $this->exebuild = $v;
            $this->modifiedColumns[MxmapTableMap::COL_EXEBUILD] = true;
        }

        return $this;
    } // setExebuild()

    /**
     * Set the value of [environmentname] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setEnvironmentname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->environmentname !== $v) {
            $this->environmentname = $v;
            $this->modifiedColumns[MxmapTableMap::COL_ENVIRONMENTNAME] = true;
        }

        return $this;
    } // setEnvironmentname()

    /**
     * Set the value of [vehiclename] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setVehiclename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->vehiclename !== $v) {
            $this->vehiclename = $v;
            $this->modifiedColumns[MxmapTableMap::COL_VEHICLENAME] = true;
        }

        return $this;
    } // setVehiclename()

    /**
     * Sets the value of the [unlimiterrequired] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setUnlimiterrequired($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->unlimiterrequired !== $v) {
            $this->unlimiterrequired = $v;
            $this->modifiedColumns[MxmapTableMap::COL_UNLIMITERREQUIRED] = true;
        }

        return $this;
    } // setUnlimiterrequired()

    /**
     * Set the value of [routename] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setRoutename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->routename !== $v) {
            $this->routename = $v;
            $this->modifiedColumns[MxmapTableMap::COL_ROUTENAME] = true;
        }

        return $this;
    } // setRoutename()

    /**
     * Set the value of [lengthname] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setLengthname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lengthname !== $v) {
            $this->lengthname = $v;
            $this->modifiedColumns[MxmapTableMap::COL_LENGTHNAME] = true;
        }

        return $this;
    } // setLengthname()

    /**
     * Set the value of [laps] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setLaps($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->laps !== $v) {
            $this->laps = $v;
            $this->modifiedColumns[MxmapTableMap::COL_LAPS] = true;
        }

        return $this;
    } // setLaps()

    /**
     * Set the value of [difficultyname] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setDifficultyname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->difficultyname !== $v) {
            $this->difficultyname = $v;
            $this->modifiedColumns[MxmapTableMap::COL_DIFFICULTYNAME] = true;
        }

        return $this;
    } // setDifficultyname()

    /**
     * Set the value of [replaytypename] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setReplaytypename($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->replaytypename !== $v) {
            $this->replaytypename = $v;
            $this->modifiedColumns[MxmapTableMap::COL_REPLAYTYPENAME] = true;
        }

        return $this;
    } // setReplaytypename()

    /**
     * Set the value of [replaywrid] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setReplaywrid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->replaywrid !== $v) {
            $this->replaywrid = $v;
            $this->modifiedColumns[MxmapTableMap::COL_REPLAYWRID] = true;
        }

        return $this;
    } // setReplaywrid()

    /**
     * Set the value of [replaywrtime] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setReplaywrtime($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->replaywrtime !== $v) {
            $this->replaywrtime = $v;
            $this->modifiedColumns[MxmapTableMap::COL_REPLAYWRTIME] = true;
        }

        return $this;
    } // setReplaywrtime()

    /**
     * Set the value of [replaywruserid] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setReplaywruserid($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->replaywruserid !== $v) {
            $this->replaywruserid = $v;
            $this->modifiedColumns[MxmapTableMap::COL_REPLAYWRUSERID] = true;
        }

        return $this;
    } // setReplaywruserid()

    /**
     * Set the value of [replaywrusername] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setReplaywrusername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->replaywrusername !== $v) {
            $this->replaywrusername = $v;
            $this->modifiedColumns[MxmapTableMap::COL_REPLAYWRUSERNAME] = true;
        }

        return $this;
    } // setReplaywrusername()

    /**
     * Set the value of [ratingvotecount] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setRatingvotecount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->ratingvotecount !== $v) {
            $this->ratingvotecount = $v;
            $this->modifiedColumns[MxmapTableMap::COL_RATINGVOTECOUNT] = true;
        }

        return $this;
    } // setRatingvotecount()

    /**
     * Set the value of [ratingvoteaverage] column.
     *
     * @param double $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setRatingvoteaverage($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->ratingvoteaverage !== $v) {
            $this->ratingvoteaverage = $v;
            $this->modifiedColumns[MxmapTableMap::COL_RATINGVOTEAVERAGE] = true;
        }

        return $this;
    } // setRatingvoteaverage()

    /**
     * Set the value of [replaycount] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setReplaycount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->replaycount !== $v) {
            $this->replaycount = $v;
            $this->modifiedColumns[MxmapTableMap::COL_REPLAYCOUNT] = true;
        }

        return $this;
    } // setReplaycount()

    /**
     * Set the value of [trackvalue] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setTrackvalue($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->trackvalue !== $v) {
            $this->trackvalue = $v;
            $this->modifiedColumns[MxmapTableMap::COL_TRACKVALUE] = true;
        }

        return $this;
    } // setTrackvalue()

    /**
     * Set the value of [comments] column.
     *
     * @param string $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setComments($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comments !== $v) {
            $this->comments = $v;
            $this->modifiedColumns[MxmapTableMap::COL_COMMENTS] = true;
        }

        return $this;
    } // setComments()

    /**
     * Set the value of [commentscount] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setCommentscount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->commentscount !== $v) {
            $this->commentscount = $v;
            $this->modifiedColumns[MxmapTableMap::COL_COMMENTSCOUNT] = true;
        }

        return $this;
    } // setCommentscount()

    /**
     * Set the value of [awardcount] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setAwardcount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->awardcount !== $v) {
            $this->awardcount = $v;
            $this->modifiedColumns[MxmapTableMap::COL_AWARDCOUNT] = true;
        }

        return $this;
    } // setAwardcount()

    /**
     * Sets the value of the [hasscreenshot] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setHasscreenshot($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->hasscreenshot !== $v) {
            $this->hasscreenshot = $v;
            $this->modifiedColumns[MxmapTableMap::COL_HASSCREENSHOT] = true;
        }

        return $this;
    } // setHasscreenshot()

    /**
     * Sets the value of the [hasthumbnail] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setHasthumbnail($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->hasthumbnail !== $v) {
            $this->hasthumbnail = $v;
            $this->modifiedColumns[MxmapTableMap::COL_HASTHUMBNAIL] = true;
        }

        return $this;
    } // setHasthumbnail()

    /**
     * Sets the value of the [hasghostblocks] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setHasghostblocks($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->hasghostblocks !== $v) {
            $this->hasghostblocks = $v;
            $this->modifiedColumns[MxmapTableMap::COL_HASGHOSTBLOCKS] = true;
        }

        return $this;
    } // setHasghostblocks()

    /**
     * Set the value of [embeddedobjectscount] column.
     *
     * @param int $v new value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     */
    public function setEmbeddedobjectscount($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->embeddedobjectscount !== $v) {
            $this->embeddedobjectscount = $v;
            $this->modifiedColumns[MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT] = true;
        }

        return $this;
    } // setEmbeddedobjectscount()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MxmapTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MxmapTableMap::translateFieldName('Trackuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->trackuid = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MxmapTableMap::translateFieldName('Gbxmapname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->gbxmapname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MxmapTableMap::translateFieldName('Trackid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->trackid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MxmapTableMap::translateFieldName('Userid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->userid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MxmapTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MxmapTableMap::translateFieldName('Uploadedat', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->uploadedat = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MxmapTableMap::translateFieldName('Updatedat', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updatedat = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MxmapTableMap::translateFieldName('Maptype', TableMap::TYPE_PHPNAME, $indexType)];
            $this->maptype = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : MxmapTableMap::translateFieldName('Titlepack', TableMap::TYPE_PHPNAME, $indexType)];
            $this->titlepack = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : MxmapTableMap::translateFieldName('Stylename', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stylename = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : MxmapTableMap::translateFieldName('Displaycost', TableMap::TYPE_PHPNAME, $indexType)];
            $this->displaycost = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : MxmapTableMap::translateFieldName('Modname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->modname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : MxmapTableMap::translateFieldName('Lightmap', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lightmap = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : MxmapTableMap::translateFieldName('Exeversion', TableMap::TYPE_PHPNAME, $indexType)];
            $this->exeversion = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : MxmapTableMap::translateFieldName('Exebuild', TableMap::TYPE_PHPNAME, $indexType)];
            $this->exebuild = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : MxmapTableMap::translateFieldName('Environmentname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->environmentname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : MxmapTableMap::translateFieldName('Vehiclename', TableMap::TYPE_PHPNAME, $indexType)];
            $this->vehiclename = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : MxmapTableMap::translateFieldName('Unlimiterrequired', TableMap::TYPE_PHPNAME, $indexType)];
            $this->unlimiterrequired = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : MxmapTableMap::translateFieldName('Routename', TableMap::TYPE_PHPNAME, $indexType)];
            $this->routename = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : MxmapTableMap::translateFieldName('Lengthname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lengthname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : MxmapTableMap::translateFieldName('Laps', TableMap::TYPE_PHPNAME, $indexType)];
            $this->laps = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : MxmapTableMap::translateFieldName('Difficultyname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->difficultyname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : MxmapTableMap::translateFieldName('Replaytypename', TableMap::TYPE_PHPNAME, $indexType)];
            $this->replaytypename = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : MxmapTableMap::translateFieldName('Replaywrid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->replaywrid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : MxmapTableMap::translateFieldName('Replaywrtime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->replaywrtime = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 26 + $startcol : MxmapTableMap::translateFieldName('Replaywruserid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->replaywruserid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 27 + $startcol : MxmapTableMap::translateFieldName('Replaywrusername', TableMap::TYPE_PHPNAME, $indexType)];
            $this->replaywrusername = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 28 + $startcol : MxmapTableMap::translateFieldName('Ratingvotecount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ratingvotecount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 29 + $startcol : MxmapTableMap::translateFieldName('Ratingvoteaverage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ratingvoteaverage = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 30 + $startcol : MxmapTableMap::translateFieldName('Replaycount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->replaycount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 31 + $startcol : MxmapTableMap::translateFieldName('Trackvalue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->trackvalue = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 32 + $startcol : MxmapTableMap::translateFieldName('Comments', TableMap::TYPE_PHPNAME, $indexType)];
            $this->comments = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 33 + $startcol : MxmapTableMap::translateFieldName('Commentscount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->commentscount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 34 + $startcol : MxmapTableMap::translateFieldName('Awardcount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->awardcount = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 35 + $startcol : MxmapTableMap::translateFieldName('Hasscreenshot', TableMap::TYPE_PHPNAME, $indexType)];
            $this->hasscreenshot = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 36 + $startcol : MxmapTableMap::translateFieldName('Hasthumbnail', TableMap::TYPE_PHPNAME, $indexType)];
            $this->hasthumbnail = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 37 + $startcol : MxmapTableMap::translateFieldName('Hasghostblocks', TableMap::TYPE_PHPNAME, $indexType)];
            $this->hasghostblocks = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 38 + $startcol : MxmapTableMap::translateFieldName('Embeddedobjectscount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->embeddedobjectscount = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 39; // 39 = MxmapTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\eXpansion\\Bundle\\Maps\\Model\\Mxmap'), 0, $e);
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
        if ($this->aMap !== null && $this->trackuid !== $this->aMap->getMapuid()) {
            $this->aMap = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(MxmapTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMxmapQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMap = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Mxmap::setDeleted()
     * @see Mxmap::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MxmapTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMxmapQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MxmapTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                MxmapTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aMap !== null) {
                if ($this->aMap->isModified() || $this->aMap->isNew()) {
                    $affectedRows += $this->aMap->save($con);
                }
                $this->setMap($this->aMap);
            }

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

        $this->modifiedColumns[MxmapTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MxmapTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MxmapTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TRACKUID)) {
            $modifiedColumns[':p' . $index++]  = 'trackUID';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_GBXMAPNAME)) {
            $modifiedColumns[':p' . $index++]  = 'gbxMapName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TRACKID)) {
            $modifiedColumns[':p' . $index++]  = 'trackID';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_USERID)) {
            $modifiedColumns[':p' . $index++]  = 'userID';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_UPLOADEDAT)) {
            $modifiedColumns[':p' . $index++]  = 'uploadedAt';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_UPDATEDAT)) {
            $modifiedColumns[':p' . $index++]  = 'updatedAt';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_MAPTYPE)) {
            $modifiedColumns[':p' . $index++]  = 'mapType';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TITLEPACK)) {
            $modifiedColumns[':p' . $index++]  = 'titlePack';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_STYLENAME)) {
            $modifiedColumns[':p' . $index++]  = 'styleName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_DISPLAYCOST)) {
            $modifiedColumns[':p' . $index++]  = 'displayCost';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_MODNAME)) {
            $modifiedColumns[':p' . $index++]  = 'modName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_LIGHTMAP)) {
            $modifiedColumns[':p' . $index++]  = 'lightMap';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_EXEVERSION)) {
            $modifiedColumns[':p' . $index++]  = 'exeVersion';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_EXEBUILD)) {
            $modifiedColumns[':p' . $index++]  = 'exeBuild';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_ENVIRONMENTNAME)) {
            $modifiedColumns[':p' . $index++]  = 'environmentName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_VEHICLENAME)) {
            $modifiedColumns[':p' . $index++]  = 'vehicleName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_UNLIMITERREQUIRED)) {
            $modifiedColumns[':p' . $index++]  = 'unlimiterRequired';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_ROUTENAME)) {
            $modifiedColumns[':p' . $index++]  = 'routeName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_LENGTHNAME)) {
            $modifiedColumns[':p' . $index++]  = 'lengthName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_LAPS)) {
            $modifiedColumns[':p' . $index++]  = 'laps';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_DIFFICULTYNAME)) {
            $modifiedColumns[':p' . $index++]  = 'difficultyName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYTYPENAME)) {
            $modifiedColumns[':p' . $index++]  = 'replayTypeName';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRID)) {
            $modifiedColumns[':p' . $index++]  = 'replayWRID';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRTIME)) {
            $modifiedColumns[':p' . $index++]  = 'replayWRTime';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRUSERID)) {
            $modifiedColumns[':p' . $index++]  = 'replayWRUserID';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRUSERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'replayWRUsername';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_RATINGVOTECOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'ratingVoteCount';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_RATINGVOTEAVERAGE)) {
            $modifiedColumns[':p' . $index++]  = 'ratingVoteAverage';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYCOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'replayCount';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TRACKVALUE)) {
            $modifiedColumns[':p' . $index++]  = 'trackValue';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_COMMENTS)) {
            $modifiedColumns[':p' . $index++]  = 'comments';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_COMMENTSCOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'commentsCount';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_AWARDCOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'awardCount';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_HASSCREENSHOT)) {
            $modifiedColumns[':p' . $index++]  = 'hasScreenshot';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_HASTHUMBNAIL)) {
            $modifiedColumns[':p' . $index++]  = 'hasThumbnail';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_HASGHOSTBLOCKS)) {
            $modifiedColumns[':p' . $index++]  = 'hasGhostblocks';
        }
        if ($this->isColumnModified(MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'embeddedObjectsCount';
        }

        $sql = sprintf(
            'INSERT INTO mxmap (%s) VALUES (%s)',
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
                    case 'trackUID':
                        $stmt->bindValue($identifier, $this->trackuid, PDO::PARAM_STR);
                        break;
                    case 'gbxMapName':
                        $stmt->bindValue($identifier, $this->gbxmapname, PDO::PARAM_STR);
                        break;
                    case 'trackID':
                        $stmt->bindValue($identifier, $this->trackid, PDO::PARAM_INT);
                        break;
                    case 'userID':
                        $stmt->bindValue($identifier, $this->userid, PDO::PARAM_INT);
                        break;
                    case 'username':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'uploadedAt':
                        $stmt->bindValue($identifier, $this->uploadedat ? $this->uploadedat->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updatedAt':
                        $stmt->bindValue($identifier, $this->updatedat ? $this->updatedat->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'mapType':
                        $stmt->bindValue($identifier, $this->maptype, PDO::PARAM_STR);
                        break;
                    case 'titlePack':
                        $stmt->bindValue($identifier, $this->titlepack, PDO::PARAM_STR);
                        break;
                    case 'styleName':
                        $stmt->bindValue($identifier, $this->stylename, PDO::PARAM_STR);
                        break;
                    case 'displayCost':
                        $stmt->bindValue($identifier, $this->displaycost, PDO::PARAM_INT);
                        break;
                    case 'modName':
                        $stmt->bindValue($identifier, $this->modname, PDO::PARAM_STR);
                        break;
                    case 'lightMap':
                        $stmt->bindValue($identifier, $this->lightmap, PDO::PARAM_INT);
                        break;
                    case 'exeVersion':
                        $stmt->bindValue($identifier, $this->exeversion, PDO::PARAM_STR);
                        break;
                    case 'exeBuild':
                        $stmt->bindValue($identifier, $this->exebuild, PDO::PARAM_STR);
                        break;
                    case 'environmentName':
                        $stmt->bindValue($identifier, $this->environmentname, PDO::PARAM_STR);
                        break;
                    case 'vehicleName':
                        $stmt->bindValue($identifier, $this->vehiclename, PDO::PARAM_STR);
                        break;
                    case 'unlimiterRequired':
                        $stmt->bindValue($identifier, (int) $this->unlimiterrequired, PDO::PARAM_INT);
                        break;
                    case 'routeName':
                        $stmt->bindValue($identifier, $this->routename, PDO::PARAM_STR);
                        break;
                    case 'lengthName':
                        $stmt->bindValue($identifier, $this->lengthname, PDO::PARAM_STR);
                        break;
                    case 'laps':
                        $stmt->bindValue($identifier, $this->laps, PDO::PARAM_INT);
                        break;
                    case 'difficultyName':
                        $stmt->bindValue($identifier, $this->difficultyname, PDO::PARAM_STR);
                        break;
                    case 'replayTypeName':
                        $stmt->bindValue($identifier, $this->replaytypename, PDO::PARAM_STR);
                        break;
                    case 'replayWRID':
                        $stmt->bindValue($identifier, $this->replaywrid, PDO::PARAM_INT);
                        break;
                    case 'replayWRTime':
                        $stmt->bindValue($identifier, $this->replaywrtime, PDO::PARAM_INT);
                        break;
                    case 'replayWRUserID':
                        $stmt->bindValue($identifier, $this->replaywruserid, PDO::PARAM_INT);
                        break;
                    case 'replayWRUsername':
                        $stmt->bindValue($identifier, $this->replaywrusername, PDO::PARAM_STR);
                        break;
                    case 'ratingVoteCount':
                        $stmt->bindValue($identifier, $this->ratingvotecount, PDO::PARAM_INT);
                        break;
                    case 'ratingVoteAverage':
                        $stmt->bindValue($identifier, $this->ratingvoteaverage, PDO::PARAM_STR);
                        break;
                    case 'replayCount':
                        $stmt->bindValue($identifier, $this->replaycount, PDO::PARAM_INT);
                        break;
                    case 'trackValue':
                        $stmt->bindValue($identifier, $this->trackvalue, PDO::PARAM_INT);
                        break;
                    case 'comments':
                        $stmt->bindValue($identifier, $this->comments, PDO::PARAM_STR);
                        break;
                    case 'commentsCount':
                        $stmt->bindValue($identifier, $this->commentscount, PDO::PARAM_INT);
                        break;
                    case 'awardCount':
                        $stmt->bindValue($identifier, $this->awardcount, PDO::PARAM_INT);
                        break;
                    case 'hasScreenshot':
                        $stmt->bindValue($identifier, (int) $this->hasscreenshot, PDO::PARAM_INT);
                        break;
                    case 'hasThumbnail':
                        $stmt->bindValue($identifier, (int) $this->hasthumbnail, PDO::PARAM_INT);
                        break;
                    case 'hasGhostblocks':
                        $stmt->bindValue($identifier, (int) $this->hasghostblocks, PDO::PARAM_INT);
                        break;
                    case 'embeddedObjectsCount':
                        $stmt->bindValue($identifier, $this->embeddedobjectscount, PDO::PARAM_INT);
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
        $pos = MxmapTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getTrackuid();
                break;
            case 2:
                return $this->getGbxmapname();
                break;
            case 3:
                return $this->getTrackid();
                break;
            case 4:
                return $this->getUserid();
                break;
            case 5:
                return $this->getUsername();
                break;
            case 6:
                return $this->getUploadedat();
                break;
            case 7:
                return $this->getUpdatedat();
                break;
            case 8:
                return $this->getMaptype();
                break;
            case 9:
                return $this->getTitlepack();
                break;
            case 10:
                return $this->getStylename();
                break;
            case 11:
                return $this->getDisplaycost();
                break;
            case 12:
                return $this->getModname();
                break;
            case 13:
                return $this->getLightmap();
                break;
            case 14:
                return $this->getExeversion();
                break;
            case 15:
                return $this->getExebuild();
                break;
            case 16:
                return $this->getEnvironmentname();
                break;
            case 17:
                return $this->getVehiclename();
                break;
            case 18:
                return $this->getUnlimiterrequired();
                break;
            case 19:
                return $this->getRoutename();
                break;
            case 20:
                return $this->getLengthname();
                break;
            case 21:
                return $this->getLaps();
                break;
            case 22:
                return $this->getDifficultyname();
                break;
            case 23:
                return $this->getReplaytypename();
                break;
            case 24:
                return $this->getReplaywrid();
                break;
            case 25:
                return $this->getReplaywrtime();
                break;
            case 26:
                return $this->getReplaywruserid();
                break;
            case 27:
                return $this->getReplaywrusername();
                break;
            case 28:
                return $this->getRatingvotecount();
                break;
            case 29:
                return $this->getRatingvoteaverage();
                break;
            case 30:
                return $this->getReplaycount();
                break;
            case 31:
                return $this->getTrackvalue();
                break;
            case 32:
                return $this->getComments();
                break;
            case 33:
                return $this->getCommentscount();
                break;
            case 34:
                return $this->getAwardcount();
                break;
            case 35:
                return $this->getHasscreenshot();
                break;
            case 36:
                return $this->getHasthumbnail();
                break;
            case 37:
                return $this->getHasghostblocks();
                break;
            case 38:
                return $this->getEmbeddedobjectscount();
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

        if (isset($alreadyDumpedObjects['Mxmap'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Mxmap'][$this->hashCode()] = true;
        $keys = MxmapTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTrackuid(),
            $keys[2] => $this->getGbxmapname(),
            $keys[3] => $this->getTrackid(),
            $keys[4] => $this->getUserid(),
            $keys[5] => $this->getUsername(),
            $keys[6] => $this->getUploadedat(),
            $keys[7] => $this->getUpdatedat(),
            $keys[8] => $this->getMaptype(),
            $keys[9] => $this->getTitlepack(),
            $keys[10] => $this->getStylename(),
            $keys[11] => $this->getDisplaycost(),
            $keys[12] => $this->getModname(),
            $keys[13] => $this->getLightmap(),
            $keys[14] => $this->getExeversion(),
            $keys[15] => $this->getExebuild(),
            $keys[16] => $this->getEnvironmentname(),
            $keys[17] => $this->getVehiclename(),
            $keys[18] => $this->getUnlimiterrequired(),
            $keys[19] => $this->getRoutename(),
            $keys[20] => $this->getLengthname(),
            $keys[21] => $this->getLaps(),
            $keys[22] => $this->getDifficultyname(),
            $keys[23] => $this->getReplaytypename(),
            $keys[24] => $this->getReplaywrid(),
            $keys[25] => $this->getReplaywrtime(),
            $keys[26] => $this->getReplaywruserid(),
            $keys[27] => $this->getReplaywrusername(),
            $keys[28] => $this->getRatingvotecount(),
            $keys[29] => $this->getRatingvoteaverage(),
            $keys[30] => $this->getReplaycount(),
            $keys[31] => $this->getTrackvalue(),
            $keys[32] => $this->getComments(),
            $keys[33] => $this->getCommentscount(),
            $keys[34] => $this->getAwardcount(),
            $keys[35] => $this->getHasscreenshot(),
            $keys[36] => $this->getHasthumbnail(),
            $keys[37] => $this->getHasghostblocks(),
            $keys[38] => $this->getEmbeddedobjectscount(),
        );
        if ($result[$keys[6]] instanceof \DateTime) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aMap) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'map';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'map';
                        break;
                    default:
                        $key = 'Map';
                }

                $result[$key] = $this->aMap->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MxmapTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTrackuid($value);
                break;
            case 2:
                $this->setGbxmapname($value);
                break;
            case 3:
                $this->setTrackid($value);
                break;
            case 4:
                $this->setUserid($value);
                break;
            case 5:
                $this->setUsername($value);
                break;
            case 6:
                $this->setUploadedat($value);
                break;
            case 7:
                $this->setUpdatedat($value);
                break;
            case 8:
                $this->setMaptype($value);
                break;
            case 9:
                $this->setTitlepack($value);
                break;
            case 10:
                $this->setStylename($value);
                break;
            case 11:
                $this->setDisplaycost($value);
                break;
            case 12:
                $this->setModname($value);
                break;
            case 13:
                $this->setLightmap($value);
                break;
            case 14:
                $this->setExeversion($value);
                break;
            case 15:
                $this->setExebuild($value);
                break;
            case 16:
                $this->setEnvironmentname($value);
                break;
            case 17:
                $this->setVehiclename($value);
                break;
            case 18:
                $this->setUnlimiterrequired($value);
                break;
            case 19:
                $this->setRoutename($value);
                break;
            case 20:
                $this->setLengthname($value);
                break;
            case 21:
                $this->setLaps($value);
                break;
            case 22:
                $this->setDifficultyname($value);
                break;
            case 23:
                $this->setReplaytypename($value);
                break;
            case 24:
                $this->setReplaywrid($value);
                break;
            case 25:
                $this->setReplaywrtime($value);
                break;
            case 26:
                $this->setReplaywruserid($value);
                break;
            case 27:
                $this->setReplaywrusername($value);
                break;
            case 28:
                $this->setRatingvotecount($value);
                break;
            case 29:
                $this->setRatingvoteaverage($value);
                break;
            case 30:
                $this->setReplaycount($value);
                break;
            case 31:
                $this->setTrackvalue($value);
                break;
            case 32:
                $this->setComments($value);
                break;
            case 33:
                $this->setCommentscount($value);
                break;
            case 34:
                $this->setAwardcount($value);
                break;
            case 35:
                $this->setHasscreenshot($value);
                break;
            case 36:
                $this->setHasthumbnail($value);
                break;
            case 37:
                $this->setHasghostblocks($value);
                break;
            case 38:
                $this->setEmbeddedobjectscount($value);
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
        $keys = MxmapTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setTrackuid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setGbxmapname($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setTrackid($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setUserid($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setUsername($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setUploadedat($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setUpdatedat($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setMaptype($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setTitlepack($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setStylename($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setDisplaycost($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setModname($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setLightmap($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setExeversion($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setExebuild($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setEnvironmentname($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setVehiclename($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setUnlimiterrequired($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setRoutename($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setLengthname($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setLaps($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setDifficultyname($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setReplaytypename($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setReplaywrid($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setReplaywrtime($arr[$keys[25]]);
        }
        if (array_key_exists($keys[26], $arr)) {
            $this->setReplaywruserid($arr[$keys[26]]);
        }
        if (array_key_exists($keys[27], $arr)) {
            $this->setReplaywrusername($arr[$keys[27]]);
        }
        if (array_key_exists($keys[28], $arr)) {
            $this->setRatingvotecount($arr[$keys[28]]);
        }
        if (array_key_exists($keys[29], $arr)) {
            $this->setRatingvoteaverage($arr[$keys[29]]);
        }
        if (array_key_exists($keys[30], $arr)) {
            $this->setReplaycount($arr[$keys[30]]);
        }
        if (array_key_exists($keys[31], $arr)) {
            $this->setTrackvalue($arr[$keys[31]]);
        }
        if (array_key_exists($keys[32], $arr)) {
            $this->setComments($arr[$keys[32]]);
        }
        if (array_key_exists($keys[33], $arr)) {
            $this->setCommentscount($arr[$keys[33]]);
        }
        if (array_key_exists($keys[34], $arr)) {
            $this->setAwardcount($arr[$keys[34]]);
        }
        if (array_key_exists($keys[35], $arr)) {
            $this->setHasscreenshot($arr[$keys[35]]);
        }
        if (array_key_exists($keys[36], $arr)) {
            $this->setHasthumbnail($arr[$keys[36]]);
        }
        if (array_key_exists($keys[37], $arr)) {
            $this->setHasghostblocks($arr[$keys[37]]);
        }
        if (array_key_exists($keys[38], $arr)) {
            $this->setEmbeddedobjectscount($arr[$keys[38]]);
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
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object, for fluid interface
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
        $criteria = new Criteria(MxmapTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MxmapTableMap::COL_ID)) {
            $criteria->add(MxmapTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TRACKUID)) {
            $criteria->add(MxmapTableMap::COL_TRACKUID, $this->trackuid);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_GBXMAPNAME)) {
            $criteria->add(MxmapTableMap::COL_GBXMAPNAME, $this->gbxmapname);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TRACKID)) {
            $criteria->add(MxmapTableMap::COL_TRACKID, $this->trackid);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_USERID)) {
            $criteria->add(MxmapTableMap::COL_USERID, $this->userid);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_USERNAME)) {
            $criteria->add(MxmapTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_UPLOADEDAT)) {
            $criteria->add(MxmapTableMap::COL_UPLOADEDAT, $this->uploadedat);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_UPDATEDAT)) {
            $criteria->add(MxmapTableMap::COL_UPDATEDAT, $this->updatedat);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_MAPTYPE)) {
            $criteria->add(MxmapTableMap::COL_MAPTYPE, $this->maptype);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TITLEPACK)) {
            $criteria->add(MxmapTableMap::COL_TITLEPACK, $this->titlepack);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_STYLENAME)) {
            $criteria->add(MxmapTableMap::COL_STYLENAME, $this->stylename);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_DISPLAYCOST)) {
            $criteria->add(MxmapTableMap::COL_DISPLAYCOST, $this->displaycost);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_MODNAME)) {
            $criteria->add(MxmapTableMap::COL_MODNAME, $this->modname);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_LIGHTMAP)) {
            $criteria->add(MxmapTableMap::COL_LIGHTMAP, $this->lightmap);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_EXEVERSION)) {
            $criteria->add(MxmapTableMap::COL_EXEVERSION, $this->exeversion);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_EXEBUILD)) {
            $criteria->add(MxmapTableMap::COL_EXEBUILD, $this->exebuild);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_ENVIRONMENTNAME)) {
            $criteria->add(MxmapTableMap::COL_ENVIRONMENTNAME, $this->environmentname);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_VEHICLENAME)) {
            $criteria->add(MxmapTableMap::COL_VEHICLENAME, $this->vehiclename);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_UNLIMITERREQUIRED)) {
            $criteria->add(MxmapTableMap::COL_UNLIMITERREQUIRED, $this->unlimiterrequired);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_ROUTENAME)) {
            $criteria->add(MxmapTableMap::COL_ROUTENAME, $this->routename);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_LENGTHNAME)) {
            $criteria->add(MxmapTableMap::COL_LENGTHNAME, $this->lengthname);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_LAPS)) {
            $criteria->add(MxmapTableMap::COL_LAPS, $this->laps);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_DIFFICULTYNAME)) {
            $criteria->add(MxmapTableMap::COL_DIFFICULTYNAME, $this->difficultyname);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYTYPENAME)) {
            $criteria->add(MxmapTableMap::COL_REPLAYTYPENAME, $this->replaytypename);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRID)) {
            $criteria->add(MxmapTableMap::COL_REPLAYWRID, $this->replaywrid);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRTIME)) {
            $criteria->add(MxmapTableMap::COL_REPLAYWRTIME, $this->replaywrtime);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRUSERID)) {
            $criteria->add(MxmapTableMap::COL_REPLAYWRUSERID, $this->replaywruserid);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYWRUSERNAME)) {
            $criteria->add(MxmapTableMap::COL_REPLAYWRUSERNAME, $this->replaywrusername);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_RATINGVOTECOUNT)) {
            $criteria->add(MxmapTableMap::COL_RATINGVOTECOUNT, $this->ratingvotecount);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_RATINGVOTEAVERAGE)) {
            $criteria->add(MxmapTableMap::COL_RATINGVOTEAVERAGE, $this->ratingvoteaverage);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_REPLAYCOUNT)) {
            $criteria->add(MxmapTableMap::COL_REPLAYCOUNT, $this->replaycount);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_TRACKVALUE)) {
            $criteria->add(MxmapTableMap::COL_TRACKVALUE, $this->trackvalue);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_COMMENTS)) {
            $criteria->add(MxmapTableMap::COL_COMMENTS, $this->comments);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_COMMENTSCOUNT)) {
            $criteria->add(MxmapTableMap::COL_COMMENTSCOUNT, $this->commentscount);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_AWARDCOUNT)) {
            $criteria->add(MxmapTableMap::COL_AWARDCOUNT, $this->awardcount);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_HASSCREENSHOT)) {
            $criteria->add(MxmapTableMap::COL_HASSCREENSHOT, $this->hasscreenshot);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_HASTHUMBNAIL)) {
            $criteria->add(MxmapTableMap::COL_HASTHUMBNAIL, $this->hasthumbnail);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_HASGHOSTBLOCKS)) {
            $criteria->add(MxmapTableMap::COL_HASGHOSTBLOCKS, $this->hasghostblocks);
        }
        if ($this->isColumnModified(MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT)) {
            $criteria->add(MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT, $this->embeddedobjectscount);
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
        $criteria = ChildMxmapQuery::create();
        $criteria->add(MxmapTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \eXpansion\Bundle\Maps\Model\Mxmap (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTrackuid($this->getTrackuid());
        $copyObj->setGbxmapname($this->getGbxmapname());
        $copyObj->setTrackid($this->getTrackid());
        $copyObj->setUserid($this->getUserid());
        $copyObj->setUsername($this->getUsername());
        $copyObj->setUploadedat($this->getUploadedat());
        $copyObj->setUpdatedat($this->getUpdatedat());
        $copyObj->setMaptype($this->getMaptype());
        $copyObj->setTitlepack($this->getTitlepack());
        $copyObj->setStylename($this->getStylename());
        $copyObj->setDisplaycost($this->getDisplaycost());
        $copyObj->setModname($this->getModname());
        $copyObj->setLightmap($this->getLightmap());
        $copyObj->setExeversion($this->getExeversion());
        $copyObj->setExebuild($this->getExebuild());
        $copyObj->setEnvironmentname($this->getEnvironmentname());
        $copyObj->setVehiclename($this->getVehiclename());
        $copyObj->setUnlimiterrequired($this->getUnlimiterrequired());
        $copyObj->setRoutename($this->getRoutename());
        $copyObj->setLengthname($this->getLengthname());
        $copyObj->setLaps($this->getLaps());
        $copyObj->setDifficultyname($this->getDifficultyname());
        $copyObj->setReplaytypename($this->getReplaytypename());
        $copyObj->setReplaywrid($this->getReplaywrid());
        $copyObj->setReplaywrtime($this->getReplaywrtime());
        $copyObj->setReplaywruserid($this->getReplaywruserid());
        $copyObj->setReplaywrusername($this->getReplaywrusername());
        $copyObj->setRatingvotecount($this->getRatingvotecount());
        $copyObj->setRatingvoteaverage($this->getRatingvoteaverage());
        $copyObj->setReplaycount($this->getReplaycount());
        $copyObj->setTrackvalue($this->getTrackvalue());
        $copyObj->setComments($this->getComments());
        $copyObj->setCommentscount($this->getCommentscount());
        $copyObj->setAwardcount($this->getAwardcount());
        $copyObj->setHasscreenshot($this->getHasscreenshot());
        $copyObj->setHasthumbnail($this->getHasthumbnail());
        $copyObj->setHasghostblocks($this->getHasghostblocks());
        $copyObj->setEmbeddedobjectscount($this->getEmbeddedobjectscount());
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
     * @return \eXpansion\Bundle\Maps\Model\Mxmap Clone of current object.
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
     * Declares an association between this object and a ChildMap object.
     *
     * @param  ChildMap $v
     * @return $this|\eXpansion\Bundle\Maps\Model\Mxmap The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMap(ChildMap $v = null)
    {
        if ($v === null) {
            $this->setTrackuid(NULL);
        } else {
            $this->setTrackuid($v->getMapuid());
        }

        $this->aMap = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMap object, it will not be re-added.
        if ($v !== null) {
            $v->addMxmap($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMap object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMap The associated ChildMap object.
     * @throws PropelException
     */
    public function getMap(ConnectionInterface $con = null)
    {
        if ($this->aMap === null && (($this->trackuid !== "" && $this->trackuid !== null))) {
            $this->aMap = ChildMapQuery::create()
                ->filterByMxmap($this) // here
                ->findOne($con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMap->addMxmaps($this);
             */
        }

        return $this->aMap;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aMap) {
            $this->aMap->removeMxmap($this);
        }
        $this->id = null;
        $this->trackuid = null;
        $this->gbxmapname = null;
        $this->trackid = null;
        $this->userid = null;
        $this->username = null;
        $this->uploadedat = null;
        $this->updatedat = null;
        $this->maptype = null;
        $this->titlepack = null;
        $this->stylename = null;
        $this->displaycost = null;
        $this->modname = null;
        $this->lightmap = null;
        $this->exeversion = null;
        $this->exebuild = null;
        $this->environmentname = null;
        $this->vehiclename = null;
        $this->unlimiterrequired = null;
        $this->routename = null;
        $this->lengthname = null;
        $this->laps = null;
        $this->difficultyname = null;
        $this->replaytypename = null;
        $this->replaywrid = null;
        $this->replaywrtime = null;
        $this->replaywruserid = null;
        $this->replaywrusername = null;
        $this->ratingvotecount = null;
        $this->ratingvoteaverage = null;
        $this->replaycount = null;
        $this->trackvalue = null;
        $this->comments = null;
        $this->commentscount = null;
        $this->awardcount = null;
        $this->hasscreenshot = null;
        $this->hasthumbnail = null;
        $this->hasghostblocks = null;
        $this->embeddedobjectscount = null;
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
        } // if ($deep)

        $this->aMap = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MxmapTableMap::DEFAULT_STRING_FORMAT);
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
