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
use eXpansion\Bundle\Maps\Model\Mxmap;
use eXpansion\Bundle\Maps\Model\MxmapQuery;


/**
 * This class defines the structure of the 'mxmap' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MxmapTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src\eXpansion\Bundle\Maps.Model.Map.MxmapTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'expansion';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'mxmap';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\eXpansion\\Bundle\\Maps\\Model\\Mxmap';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'src\eXpansion\Bundle\Maps.Model.Mxmap';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 39;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 39;

    /**
     * the column name for the id field
     */
    const COL_ID = 'mxmap.id';

    /**
     * the column name for the trackUID field
     */
    const COL_TRACKUID = 'mxmap.trackUID';

    /**
     * the column name for the gbxMapName field
     */
    const COL_GBXMAPNAME = 'mxmap.gbxMapName';

    /**
     * the column name for the trackID field
     */
    const COL_TRACKID = 'mxmap.trackID';

    /**
     * the column name for the userID field
     */
    const COL_USERID = 'mxmap.userID';

    /**
     * the column name for the username field
     */
    const COL_USERNAME = 'mxmap.username';

    /**
     * the column name for the uploadedAt field
     */
    const COL_UPLOADEDAT = 'mxmap.uploadedAt';

    /**
     * the column name for the updatedAt field
     */
    const COL_UPDATEDAT = 'mxmap.updatedAt';

    /**
     * the column name for the mapType field
     */
    const COL_MAPTYPE = 'mxmap.mapType';

    /**
     * the column name for the titlePack field
     */
    const COL_TITLEPACK = 'mxmap.titlePack';

    /**
     * the column name for the styleName field
     */
    const COL_STYLENAME = 'mxmap.styleName';

    /**
     * the column name for the displayCost field
     */
    const COL_DISPLAYCOST = 'mxmap.displayCost';

    /**
     * the column name for the modName field
     */
    const COL_MODNAME = 'mxmap.modName';

    /**
     * the column name for the lightMap field
     */
    const COL_LIGHTMAP = 'mxmap.lightMap';

    /**
     * the column name for the exeVersion field
     */
    const COL_EXEVERSION = 'mxmap.exeVersion';

    /**
     * the column name for the exeBuild field
     */
    const COL_EXEBUILD = 'mxmap.exeBuild';

    /**
     * the column name for the environmentName field
     */
    const COL_ENVIRONMENTNAME = 'mxmap.environmentName';

    /**
     * the column name for the vehicleName field
     */
    const COL_VEHICLENAME = 'mxmap.vehicleName';

    /**
     * the column name for the unlimiterRequired field
     */
    const COL_UNLIMITERREQUIRED = 'mxmap.unlimiterRequired';

    /**
     * the column name for the routeName field
     */
    const COL_ROUTENAME = 'mxmap.routeName';

    /**
     * the column name for the lengthName field
     */
    const COL_LENGTHNAME = 'mxmap.lengthName';

    /**
     * the column name for the laps field
     */
    const COL_LAPS = 'mxmap.laps';

    /**
     * the column name for the difficultyName field
     */
    const COL_DIFFICULTYNAME = 'mxmap.difficultyName';

    /**
     * the column name for the replayTypeName field
     */
    const COL_REPLAYTYPENAME = 'mxmap.replayTypeName';

    /**
     * the column name for the replayWRID field
     */
    const COL_REPLAYWRID = 'mxmap.replayWRID';

    /**
     * the column name for the replayWRTime field
     */
    const COL_REPLAYWRTIME = 'mxmap.replayWRTime';

    /**
     * the column name for the replayWRUserID field
     */
    const COL_REPLAYWRUSERID = 'mxmap.replayWRUserID';

    /**
     * the column name for the replayWRUsername field
     */
    const COL_REPLAYWRUSERNAME = 'mxmap.replayWRUsername';

    /**
     * the column name for the ratingVoteCount field
     */
    const COL_RATINGVOTECOUNT = 'mxmap.ratingVoteCount';

    /**
     * the column name for the ratingVoteAverage field
     */
    const COL_RATINGVOTEAVERAGE = 'mxmap.ratingVoteAverage';

    /**
     * the column name for the replayCount field
     */
    const COL_REPLAYCOUNT = 'mxmap.replayCount';

    /**
     * the column name for the trackValue field
     */
    const COL_TRACKVALUE = 'mxmap.trackValue';

    /**
     * the column name for the comments field
     */
    const COL_COMMENTS = 'mxmap.comments';

    /**
     * the column name for the commentsCount field
     */
    const COL_COMMENTSCOUNT = 'mxmap.commentsCount';

    /**
     * the column name for the awardCount field
     */
    const COL_AWARDCOUNT = 'mxmap.awardCount';

    /**
     * the column name for the hasScreenshot field
     */
    const COL_HASSCREENSHOT = 'mxmap.hasScreenshot';

    /**
     * the column name for the hasThumbnail field
     */
    const COL_HASTHUMBNAIL = 'mxmap.hasThumbnail';

    /**
     * the column name for the hasGhostblocks field
     */
    const COL_HASGHOSTBLOCKS = 'mxmap.hasGhostblocks';

    /**
     * the column name for the embeddedObjectsCount field
     */
    const COL_EMBEDDEDOBJECTSCOUNT = 'mxmap.embeddedObjectsCount';

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
        self::TYPE_PHPNAME       => array('Id', 'Trackuid', 'Gbxmapname', 'Trackid', 'Userid', 'Username', 'Uploadedat', 'Updatedat', 'Maptype', 'Titlepack', 'Stylename', 'Displaycost', 'Modname', 'Lightmap', 'Exeversion', 'Exebuild', 'Environmentname', 'Vehiclename', 'Unlimiterrequired', 'Routename', 'Lengthname', 'Laps', 'Difficultyname', 'Replaytypename', 'Replaywrid', 'Replaywrtime', 'Replaywruserid', 'Replaywrusername', 'Ratingvotecount', 'Ratingvoteaverage', 'Replaycount', 'Trackvalue', 'Comments', 'Commentscount', 'Awardcount', 'Hasscreenshot', 'Hasthumbnail', 'Hasghostblocks', 'Embeddedobjectscount', ),
        self::TYPE_CAMELNAME     => array('id', 'trackuid', 'gbxmapname', 'trackid', 'userid', 'username', 'uploadedat', 'updatedat', 'maptype', 'titlepack', 'stylename', 'displaycost', 'modname', 'lightmap', 'exeversion', 'exebuild', 'environmentname', 'vehiclename', 'unlimiterrequired', 'routename', 'lengthname', 'laps', 'difficultyname', 'replaytypename', 'replaywrid', 'replaywrtime', 'replaywruserid', 'replaywrusername', 'ratingvotecount', 'ratingvoteaverage', 'replaycount', 'trackvalue', 'comments', 'commentscount', 'awardcount', 'hasscreenshot', 'hasthumbnail', 'hasghostblocks', 'embeddedobjectscount', ),
        self::TYPE_COLNAME       => array(MxmapTableMap::COL_ID, MxmapTableMap::COL_TRACKUID, MxmapTableMap::COL_GBXMAPNAME, MxmapTableMap::COL_TRACKID, MxmapTableMap::COL_USERID, MxmapTableMap::COL_USERNAME, MxmapTableMap::COL_UPLOADEDAT, MxmapTableMap::COL_UPDATEDAT, MxmapTableMap::COL_MAPTYPE, MxmapTableMap::COL_TITLEPACK, MxmapTableMap::COL_STYLENAME, MxmapTableMap::COL_DISPLAYCOST, MxmapTableMap::COL_MODNAME, MxmapTableMap::COL_LIGHTMAP, MxmapTableMap::COL_EXEVERSION, MxmapTableMap::COL_EXEBUILD, MxmapTableMap::COL_ENVIRONMENTNAME, MxmapTableMap::COL_VEHICLENAME, MxmapTableMap::COL_UNLIMITERREQUIRED, MxmapTableMap::COL_ROUTENAME, MxmapTableMap::COL_LENGTHNAME, MxmapTableMap::COL_LAPS, MxmapTableMap::COL_DIFFICULTYNAME, MxmapTableMap::COL_REPLAYTYPENAME, MxmapTableMap::COL_REPLAYWRID, MxmapTableMap::COL_REPLAYWRTIME, MxmapTableMap::COL_REPLAYWRUSERID, MxmapTableMap::COL_REPLAYWRUSERNAME, MxmapTableMap::COL_RATINGVOTECOUNT, MxmapTableMap::COL_RATINGVOTEAVERAGE, MxmapTableMap::COL_REPLAYCOUNT, MxmapTableMap::COL_TRACKVALUE, MxmapTableMap::COL_COMMENTS, MxmapTableMap::COL_COMMENTSCOUNT, MxmapTableMap::COL_AWARDCOUNT, MxmapTableMap::COL_HASSCREENSHOT, MxmapTableMap::COL_HASTHUMBNAIL, MxmapTableMap::COL_HASGHOSTBLOCKS, MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT, ),
        self::TYPE_FIELDNAME     => array('id', 'trackUID', 'gbxMapName', 'trackID', 'userID', 'username', 'uploadedAt', 'updatedAt', 'mapType', 'titlePack', 'styleName', 'displayCost', 'modName', 'lightMap', 'exeVersion', 'exeBuild', 'environmentName', 'vehicleName', 'unlimiterRequired', 'routeName', 'lengthName', 'laps', 'difficultyName', 'replayTypeName', 'replayWRID', 'replayWRTime', 'replayWRUserID', 'replayWRUsername', 'ratingVoteCount', 'ratingVoteAverage', 'replayCount', 'trackValue', 'comments', 'commentsCount', 'awardCount', 'hasScreenshot', 'hasThumbnail', 'hasGhostblocks', 'embeddedObjectsCount', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Trackuid' => 1, 'Gbxmapname' => 2, 'Trackid' => 3, 'Userid' => 4, 'Username' => 5, 'Uploadedat' => 6, 'Updatedat' => 7, 'Maptype' => 8, 'Titlepack' => 9, 'Stylename' => 10, 'Displaycost' => 11, 'Modname' => 12, 'Lightmap' => 13, 'Exeversion' => 14, 'Exebuild' => 15, 'Environmentname' => 16, 'Vehiclename' => 17, 'Unlimiterrequired' => 18, 'Routename' => 19, 'Lengthname' => 20, 'Laps' => 21, 'Difficultyname' => 22, 'Replaytypename' => 23, 'Replaywrid' => 24, 'Replaywrtime' => 25, 'Replaywruserid' => 26, 'Replaywrusername' => 27, 'Ratingvotecount' => 28, 'Ratingvoteaverage' => 29, 'Replaycount' => 30, 'Trackvalue' => 31, 'Comments' => 32, 'Commentscount' => 33, 'Awardcount' => 34, 'Hasscreenshot' => 35, 'Hasthumbnail' => 36, 'Hasghostblocks' => 37, 'Embeddedobjectscount' => 38, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'trackuid' => 1, 'gbxmapname' => 2, 'trackid' => 3, 'userid' => 4, 'username' => 5, 'uploadedat' => 6, 'updatedat' => 7, 'maptype' => 8, 'titlepack' => 9, 'stylename' => 10, 'displaycost' => 11, 'modname' => 12, 'lightmap' => 13, 'exeversion' => 14, 'exebuild' => 15, 'environmentname' => 16, 'vehiclename' => 17, 'unlimiterrequired' => 18, 'routename' => 19, 'lengthname' => 20, 'laps' => 21, 'difficultyname' => 22, 'replaytypename' => 23, 'replaywrid' => 24, 'replaywrtime' => 25, 'replaywruserid' => 26, 'replaywrusername' => 27, 'ratingvotecount' => 28, 'ratingvoteaverage' => 29, 'replaycount' => 30, 'trackvalue' => 31, 'comments' => 32, 'commentscount' => 33, 'awardcount' => 34, 'hasscreenshot' => 35, 'hasthumbnail' => 36, 'hasghostblocks' => 37, 'embeddedobjectscount' => 38, ),
        self::TYPE_COLNAME       => array(MxmapTableMap::COL_ID => 0, MxmapTableMap::COL_TRACKUID => 1, MxmapTableMap::COL_GBXMAPNAME => 2, MxmapTableMap::COL_TRACKID => 3, MxmapTableMap::COL_USERID => 4, MxmapTableMap::COL_USERNAME => 5, MxmapTableMap::COL_UPLOADEDAT => 6, MxmapTableMap::COL_UPDATEDAT => 7, MxmapTableMap::COL_MAPTYPE => 8, MxmapTableMap::COL_TITLEPACK => 9, MxmapTableMap::COL_STYLENAME => 10, MxmapTableMap::COL_DISPLAYCOST => 11, MxmapTableMap::COL_MODNAME => 12, MxmapTableMap::COL_LIGHTMAP => 13, MxmapTableMap::COL_EXEVERSION => 14, MxmapTableMap::COL_EXEBUILD => 15, MxmapTableMap::COL_ENVIRONMENTNAME => 16, MxmapTableMap::COL_VEHICLENAME => 17, MxmapTableMap::COL_UNLIMITERREQUIRED => 18, MxmapTableMap::COL_ROUTENAME => 19, MxmapTableMap::COL_LENGTHNAME => 20, MxmapTableMap::COL_LAPS => 21, MxmapTableMap::COL_DIFFICULTYNAME => 22, MxmapTableMap::COL_REPLAYTYPENAME => 23, MxmapTableMap::COL_REPLAYWRID => 24, MxmapTableMap::COL_REPLAYWRTIME => 25, MxmapTableMap::COL_REPLAYWRUSERID => 26, MxmapTableMap::COL_REPLAYWRUSERNAME => 27, MxmapTableMap::COL_RATINGVOTECOUNT => 28, MxmapTableMap::COL_RATINGVOTEAVERAGE => 29, MxmapTableMap::COL_REPLAYCOUNT => 30, MxmapTableMap::COL_TRACKVALUE => 31, MxmapTableMap::COL_COMMENTS => 32, MxmapTableMap::COL_COMMENTSCOUNT => 33, MxmapTableMap::COL_AWARDCOUNT => 34, MxmapTableMap::COL_HASSCREENSHOT => 35, MxmapTableMap::COL_HASTHUMBNAIL => 36, MxmapTableMap::COL_HASGHOSTBLOCKS => 37, MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT => 38, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'trackUID' => 1, 'gbxMapName' => 2, 'trackID' => 3, 'userID' => 4, 'username' => 5, 'uploadedAt' => 6, 'updatedAt' => 7, 'mapType' => 8, 'titlePack' => 9, 'styleName' => 10, 'displayCost' => 11, 'modName' => 12, 'lightMap' => 13, 'exeVersion' => 14, 'exeBuild' => 15, 'environmentName' => 16, 'vehicleName' => 17, 'unlimiterRequired' => 18, 'routeName' => 19, 'lengthName' => 20, 'laps' => 21, 'difficultyName' => 22, 'replayTypeName' => 23, 'replayWRID' => 24, 'replayWRTime' => 25, 'replayWRUserID' => 26, 'replayWRUsername' => 27, 'ratingVoteCount' => 28, 'ratingVoteAverage' => 29, 'replayCount' => 30, 'trackValue' => 31, 'comments' => 32, 'commentsCount' => 33, 'awardCount' => 34, 'hasScreenshot' => 35, 'hasThumbnail' => 36, 'hasGhostblocks' => 37, 'embeddedObjectsCount' => 38, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, )
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
        $this->setName('mxmap');
        $this->setPhpName('Mxmap');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\eXpansion\\Bundle\\Maps\\Model\\Mxmap');
        $this->setPackage('src\eXpansion\Bundle\Maps.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('trackUID', 'Trackuid', 'VARCHAR', 'map', 'mapUid', true, 50, null);
        $this->addColumn('gbxMapName', 'Gbxmapname', 'VARCHAR', false, 150, null);
        $this->addColumn('trackID', 'Trackid', 'INTEGER', false, null, null);
        $this->addColumn('userID', 'Userid', 'INTEGER', false, null, null);
        $this->addColumn('username', 'Username', 'VARCHAR', false, 100, null);
        $this->addColumn('uploadedAt', 'Uploadedat', 'TIMESTAMP', false, null, null);
        $this->addColumn('updatedAt', 'Updatedat', 'TIMESTAMP', false, null, null);
        $this->addColumn('mapType', 'Maptype', 'VARCHAR', false, 100, null);
        $this->addColumn('titlePack', 'Titlepack', 'VARCHAR', false, 50, null);
        $this->addColumn('styleName', 'Stylename', 'VARCHAR', false, 50, null);
        $this->addColumn('displayCost', 'Displaycost', 'INTEGER', false, null, null);
        $this->addColumn('modName', 'Modname', 'VARCHAR', false, 60, null);
        $this->addColumn('lightMap', 'Lightmap', 'INTEGER', false, null, null);
        $this->addColumn('exeVersion', 'Exeversion', 'VARCHAR', false, 25, null);
        $this->addColumn('exeBuild', 'Exebuild', 'VARCHAR', false, 25, null);
        $this->addColumn('environmentName', 'Environmentname', 'VARCHAR', false, 50, null);
        $this->addColumn('vehicleName', 'Vehiclename', 'VARCHAR', false, 50, null);
        $this->addColumn('unlimiterRequired', 'Unlimiterrequired', 'BOOLEAN', false, 1, null);
        $this->addColumn('routeName', 'Routename', 'VARCHAR', false, 50, null);
        $this->addColumn('lengthName', 'Lengthname', 'VARCHAR', false, 50, null);
        $this->addColumn('laps', 'Laps', 'INTEGER', false, null, null);
        $this->addColumn('difficultyName', 'Difficultyname', 'VARCHAR', false, 50, null);
        $this->addColumn('replayTypeName', 'Replaytypename', 'VARCHAR', false, 50, null);
        $this->addColumn('replayWRID', 'Replaywrid', 'INTEGER', false, null, null);
        $this->addColumn('replayWRTime', 'Replaywrtime', 'INTEGER', false, null, null);
        $this->addColumn('replayWRUserID', 'Replaywruserid', 'INTEGER', false, null, null);
        $this->addColumn('replayWRUsername', 'Replaywrusername', 'VARCHAR', false, 100, null);
        $this->addColumn('ratingVoteCount', 'Ratingvotecount', 'INTEGER', false, null, null);
        $this->addColumn('ratingVoteAverage', 'Ratingvoteaverage', 'FLOAT', false, null, null);
        $this->addColumn('replayCount', 'Replaycount', 'INTEGER', false, null, null);
        $this->addColumn('trackValue', 'Trackvalue', 'INTEGER', false, null, null);
        $this->addColumn('comments', 'Comments', 'LONGVARCHAR', false, null, null);
        $this->addColumn('commentsCount', 'Commentscount', 'INTEGER', false, null, null);
        $this->addColumn('awardCount', 'Awardcount', 'INTEGER', false, null, null);
        $this->addColumn('hasScreenshot', 'Hasscreenshot', 'BOOLEAN', false, 1, null);
        $this->addColumn('hasThumbnail', 'Hasthumbnail', 'BOOLEAN', false, 1, null);
        $this->addColumn('hasGhostblocks', 'Hasghostblocks', 'BOOLEAN', false, 1, null);
        $this->addColumn('embeddedObjectsCount', 'Embeddedobjectscount', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Map', '\\eXpansion\\Bundle\\Maps\\Model\\Map', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':trackUID',
    1 => ':mapUid',
  ),
), null, null, null, false);
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
        return $withPrefix ? MxmapTableMap::CLASS_DEFAULT : MxmapTableMap::OM_CLASS;
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
     * @return array           (Mxmap object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MxmapTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MxmapTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MxmapTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MxmapTableMap::OM_CLASS;
            /** @var Mxmap $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MxmapTableMap::addInstanceToPool($obj, $key);
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
            $key = MxmapTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MxmapTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Mxmap $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MxmapTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MxmapTableMap::COL_ID);
            $criteria->addSelectColumn(MxmapTableMap::COL_TRACKUID);
            $criteria->addSelectColumn(MxmapTableMap::COL_GBXMAPNAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_TRACKID);
            $criteria->addSelectColumn(MxmapTableMap::COL_USERID);
            $criteria->addSelectColumn(MxmapTableMap::COL_USERNAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_UPLOADEDAT);
            $criteria->addSelectColumn(MxmapTableMap::COL_UPDATEDAT);
            $criteria->addSelectColumn(MxmapTableMap::COL_MAPTYPE);
            $criteria->addSelectColumn(MxmapTableMap::COL_TITLEPACK);
            $criteria->addSelectColumn(MxmapTableMap::COL_STYLENAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_DISPLAYCOST);
            $criteria->addSelectColumn(MxmapTableMap::COL_MODNAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_LIGHTMAP);
            $criteria->addSelectColumn(MxmapTableMap::COL_EXEVERSION);
            $criteria->addSelectColumn(MxmapTableMap::COL_EXEBUILD);
            $criteria->addSelectColumn(MxmapTableMap::COL_ENVIRONMENTNAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_VEHICLENAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_UNLIMITERREQUIRED);
            $criteria->addSelectColumn(MxmapTableMap::COL_ROUTENAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_LENGTHNAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_LAPS);
            $criteria->addSelectColumn(MxmapTableMap::COL_DIFFICULTYNAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_REPLAYTYPENAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_REPLAYWRID);
            $criteria->addSelectColumn(MxmapTableMap::COL_REPLAYWRTIME);
            $criteria->addSelectColumn(MxmapTableMap::COL_REPLAYWRUSERID);
            $criteria->addSelectColumn(MxmapTableMap::COL_REPLAYWRUSERNAME);
            $criteria->addSelectColumn(MxmapTableMap::COL_RATINGVOTECOUNT);
            $criteria->addSelectColumn(MxmapTableMap::COL_RATINGVOTEAVERAGE);
            $criteria->addSelectColumn(MxmapTableMap::COL_REPLAYCOUNT);
            $criteria->addSelectColumn(MxmapTableMap::COL_TRACKVALUE);
            $criteria->addSelectColumn(MxmapTableMap::COL_COMMENTS);
            $criteria->addSelectColumn(MxmapTableMap::COL_COMMENTSCOUNT);
            $criteria->addSelectColumn(MxmapTableMap::COL_AWARDCOUNT);
            $criteria->addSelectColumn(MxmapTableMap::COL_HASSCREENSHOT);
            $criteria->addSelectColumn(MxmapTableMap::COL_HASTHUMBNAIL);
            $criteria->addSelectColumn(MxmapTableMap::COL_HASGHOSTBLOCKS);
            $criteria->addSelectColumn(MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.trackUID');
            $criteria->addSelectColumn($alias . '.gbxMapName');
            $criteria->addSelectColumn($alias . '.trackID');
            $criteria->addSelectColumn($alias . '.userID');
            $criteria->addSelectColumn($alias . '.username');
            $criteria->addSelectColumn($alias . '.uploadedAt');
            $criteria->addSelectColumn($alias . '.updatedAt');
            $criteria->addSelectColumn($alias . '.mapType');
            $criteria->addSelectColumn($alias . '.titlePack');
            $criteria->addSelectColumn($alias . '.styleName');
            $criteria->addSelectColumn($alias . '.displayCost');
            $criteria->addSelectColumn($alias . '.modName');
            $criteria->addSelectColumn($alias . '.lightMap');
            $criteria->addSelectColumn($alias . '.exeVersion');
            $criteria->addSelectColumn($alias . '.exeBuild');
            $criteria->addSelectColumn($alias . '.environmentName');
            $criteria->addSelectColumn($alias . '.vehicleName');
            $criteria->addSelectColumn($alias . '.unlimiterRequired');
            $criteria->addSelectColumn($alias . '.routeName');
            $criteria->addSelectColumn($alias . '.lengthName');
            $criteria->addSelectColumn($alias . '.laps');
            $criteria->addSelectColumn($alias . '.difficultyName');
            $criteria->addSelectColumn($alias . '.replayTypeName');
            $criteria->addSelectColumn($alias . '.replayWRID');
            $criteria->addSelectColumn($alias . '.replayWRTime');
            $criteria->addSelectColumn($alias . '.replayWRUserID');
            $criteria->addSelectColumn($alias . '.replayWRUsername');
            $criteria->addSelectColumn($alias . '.ratingVoteCount');
            $criteria->addSelectColumn($alias . '.ratingVoteAverage');
            $criteria->addSelectColumn($alias . '.replayCount');
            $criteria->addSelectColumn($alias . '.trackValue');
            $criteria->addSelectColumn($alias . '.comments');
            $criteria->addSelectColumn($alias . '.commentsCount');
            $criteria->addSelectColumn($alias . '.awardCount');
            $criteria->addSelectColumn($alias . '.hasScreenshot');
            $criteria->addSelectColumn($alias . '.hasThumbnail');
            $criteria->addSelectColumn($alias . '.hasGhostblocks');
            $criteria->addSelectColumn($alias . '.embeddedObjectsCount');
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
        return Propel::getServiceContainer()->getDatabaseMap(MxmapTableMap::DATABASE_NAME)->getTable(MxmapTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MxmapTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MxmapTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MxmapTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Mxmap or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Mxmap object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MxmapTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \eXpansion\Bundle\Maps\Model\Mxmap) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MxmapTableMap::DATABASE_NAME);
            $criteria->add(MxmapTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MxmapQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MxmapTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MxmapTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the mxmap table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MxmapQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Mxmap or Criteria object.
     *
     * @param mixed               $criteria Criteria or Mxmap object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MxmapTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Mxmap object
        }

        if ($criteria->containsKey(MxmapTableMap::COL_ID) && $criteria->keyContainsValue(MxmapTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MxmapTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MxmapQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MxmapTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MxmapTableMap::buildTableMap();
