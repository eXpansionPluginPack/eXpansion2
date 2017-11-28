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
use eXpansion\Bundle\Maps\Model\Mxmap as ChildMxmap;
use eXpansion\Bundle\Maps\Model\MxmapQuery as ChildMxmapQuery;
use eXpansion\Bundle\Maps\Model\Map\MxmapTableMap;

/**
 * Base class that represents a query for the 'mxmap' table.
 *
 *
 *
 * @method     ChildMxmapQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMxmapQuery orderByTrackuid($order = Criteria::ASC) Order by the trackUID column
 * @method     ChildMxmapQuery orderByGbxmapname($order = Criteria::ASC) Order by the gbxMapName column
 * @method     ChildMxmapQuery orderByTrackid($order = Criteria::ASC) Order by the trackID column
 * @method     ChildMxmapQuery orderByUserid($order = Criteria::ASC) Order by the userID column
 * @method     ChildMxmapQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildMxmapQuery orderByUploadedat($order = Criteria::ASC) Order by the uploadedAt column
 * @method     ChildMxmapQuery orderByUpdatedat($order = Criteria::ASC) Order by the updatedAt column
 * @method     ChildMxmapQuery orderByMaptype($order = Criteria::ASC) Order by the mapType column
 * @method     ChildMxmapQuery orderByTitlepack($order = Criteria::ASC) Order by the titlePack column
 * @method     ChildMxmapQuery orderByStylename($order = Criteria::ASC) Order by the styleName column
 * @method     ChildMxmapQuery orderByDisplaycost($order = Criteria::ASC) Order by the displayCost column
 * @method     ChildMxmapQuery orderByModname($order = Criteria::ASC) Order by the modName column
 * @method     ChildMxmapQuery orderByLightmap($order = Criteria::ASC) Order by the lightMap column
 * @method     ChildMxmapQuery orderByExeversion($order = Criteria::ASC) Order by the exeVersion column
 * @method     ChildMxmapQuery orderByExebuild($order = Criteria::ASC) Order by the exeBuild column
 * @method     ChildMxmapQuery orderByEnvironmentname($order = Criteria::ASC) Order by the environmentName column
 * @method     ChildMxmapQuery orderByVehiclename($order = Criteria::ASC) Order by the vehicleName column
 * @method     ChildMxmapQuery orderByUnlimiterrequired($order = Criteria::ASC) Order by the unlimiterRequired column
 * @method     ChildMxmapQuery orderByRoutename($order = Criteria::ASC) Order by the routeName column
 * @method     ChildMxmapQuery orderByLengthname($order = Criteria::ASC) Order by the lengthName column
 * @method     ChildMxmapQuery orderByLaps($order = Criteria::ASC) Order by the laps column
 * @method     ChildMxmapQuery orderByDifficultyname($order = Criteria::ASC) Order by the difficultyName column
 * @method     ChildMxmapQuery orderByReplaytypename($order = Criteria::ASC) Order by the replayTypeName column
 * @method     ChildMxmapQuery orderByReplaywrid($order = Criteria::ASC) Order by the replayWRID column
 * @method     ChildMxmapQuery orderByReplaywrtime($order = Criteria::ASC) Order by the replayWRTime column
 * @method     ChildMxmapQuery orderByReplaywruserid($order = Criteria::ASC) Order by the replayWRUserID column
 * @method     ChildMxmapQuery orderByReplaywrusername($order = Criteria::ASC) Order by the replayWRUsername column
 * @method     ChildMxmapQuery orderByRatingvotecount($order = Criteria::ASC) Order by the ratingVoteCount column
 * @method     ChildMxmapQuery orderByRatingvoteaverage($order = Criteria::ASC) Order by the ratingVoteAverage column
 * @method     ChildMxmapQuery orderByReplaycount($order = Criteria::ASC) Order by the replayCount column
 * @method     ChildMxmapQuery orderByTrackvalue($order = Criteria::ASC) Order by the trackValue column
 * @method     ChildMxmapQuery orderByComments($order = Criteria::ASC) Order by the comments column
 * @method     ChildMxmapQuery orderByCommentscount($order = Criteria::ASC) Order by the commentsCount column
 * @method     ChildMxmapQuery orderByAwardcount($order = Criteria::ASC) Order by the awardCount column
 * @method     ChildMxmapQuery orderByHasscreenshot($order = Criteria::ASC) Order by the hasScreenshot column
 * @method     ChildMxmapQuery orderByHasthumbnail($order = Criteria::ASC) Order by the hasThumbnail column
 * @method     ChildMxmapQuery orderByHasghostblocks($order = Criteria::ASC) Order by the hasGhostblocks column
 * @method     ChildMxmapQuery orderByEmbeddedobjectscount($order = Criteria::ASC) Order by the embeddedObjectsCount column
 *
 * @method     ChildMxmapQuery groupById() Group by the id column
 * @method     ChildMxmapQuery groupByTrackuid() Group by the trackUID column
 * @method     ChildMxmapQuery groupByGbxmapname() Group by the gbxMapName column
 * @method     ChildMxmapQuery groupByTrackid() Group by the trackID column
 * @method     ChildMxmapQuery groupByUserid() Group by the userID column
 * @method     ChildMxmapQuery groupByUsername() Group by the username column
 * @method     ChildMxmapQuery groupByUploadedat() Group by the uploadedAt column
 * @method     ChildMxmapQuery groupByUpdatedat() Group by the updatedAt column
 * @method     ChildMxmapQuery groupByMaptype() Group by the mapType column
 * @method     ChildMxmapQuery groupByTitlepack() Group by the titlePack column
 * @method     ChildMxmapQuery groupByStylename() Group by the styleName column
 * @method     ChildMxmapQuery groupByDisplaycost() Group by the displayCost column
 * @method     ChildMxmapQuery groupByModname() Group by the modName column
 * @method     ChildMxmapQuery groupByLightmap() Group by the lightMap column
 * @method     ChildMxmapQuery groupByExeversion() Group by the exeVersion column
 * @method     ChildMxmapQuery groupByExebuild() Group by the exeBuild column
 * @method     ChildMxmapQuery groupByEnvironmentname() Group by the environmentName column
 * @method     ChildMxmapQuery groupByVehiclename() Group by the vehicleName column
 * @method     ChildMxmapQuery groupByUnlimiterrequired() Group by the unlimiterRequired column
 * @method     ChildMxmapQuery groupByRoutename() Group by the routeName column
 * @method     ChildMxmapQuery groupByLengthname() Group by the lengthName column
 * @method     ChildMxmapQuery groupByLaps() Group by the laps column
 * @method     ChildMxmapQuery groupByDifficultyname() Group by the difficultyName column
 * @method     ChildMxmapQuery groupByReplaytypename() Group by the replayTypeName column
 * @method     ChildMxmapQuery groupByReplaywrid() Group by the replayWRID column
 * @method     ChildMxmapQuery groupByReplaywrtime() Group by the replayWRTime column
 * @method     ChildMxmapQuery groupByReplaywruserid() Group by the replayWRUserID column
 * @method     ChildMxmapQuery groupByReplaywrusername() Group by the replayWRUsername column
 * @method     ChildMxmapQuery groupByRatingvotecount() Group by the ratingVoteCount column
 * @method     ChildMxmapQuery groupByRatingvoteaverage() Group by the ratingVoteAverage column
 * @method     ChildMxmapQuery groupByReplaycount() Group by the replayCount column
 * @method     ChildMxmapQuery groupByTrackvalue() Group by the trackValue column
 * @method     ChildMxmapQuery groupByComments() Group by the comments column
 * @method     ChildMxmapQuery groupByCommentscount() Group by the commentsCount column
 * @method     ChildMxmapQuery groupByAwardcount() Group by the awardCount column
 * @method     ChildMxmapQuery groupByHasscreenshot() Group by the hasScreenshot column
 * @method     ChildMxmapQuery groupByHasthumbnail() Group by the hasThumbnail column
 * @method     ChildMxmapQuery groupByHasghostblocks() Group by the hasGhostblocks column
 * @method     ChildMxmapQuery groupByEmbeddedobjectscount() Group by the embeddedObjectsCount column
 *
 * @method     ChildMxmapQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMxmapQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMxmapQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMxmapQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMxmapQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMxmapQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMxmapQuery leftJoinMap($relationAlias = null) Adds a LEFT JOIN clause to the query using the Map relation
 * @method     ChildMxmapQuery rightJoinMap($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Map relation
 * @method     ChildMxmapQuery innerJoinMap($relationAlias = null) Adds a INNER JOIN clause to the query using the Map relation
 *
 * @method     ChildMxmapQuery joinWithMap($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Map relation
 *
 * @method     ChildMxmapQuery leftJoinWithMap() Adds a LEFT JOIN clause and with to the query using the Map relation
 * @method     ChildMxmapQuery rightJoinWithMap() Adds a RIGHT JOIN clause and with to the query using the Map relation
 * @method     ChildMxmapQuery innerJoinWithMap() Adds a INNER JOIN clause and with to the query using the Map relation
 *
 * @method     \eXpansion\Bundle\Maps\Model\MapQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMxmap findOne(ConnectionInterface $con = null) Return the first ChildMxmap matching the query
 * @method     ChildMxmap findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMxmap matching the query, or a new ChildMxmap object populated from the query conditions when no match is found
 *
 * @method     ChildMxmap findOneById(int $id) Return the first ChildMxmap filtered by the id column
 * @method     ChildMxmap findOneByTrackuid(string $trackUID) Return the first ChildMxmap filtered by the trackUID column
 * @method     ChildMxmap findOneByGbxmapname(string $gbxMapName) Return the first ChildMxmap filtered by the gbxMapName column
 * @method     ChildMxmap findOneByTrackid(int $trackID) Return the first ChildMxmap filtered by the trackID column
 * @method     ChildMxmap findOneByUserid(int $userID) Return the first ChildMxmap filtered by the userID column
 * @method     ChildMxmap findOneByUsername(string $username) Return the first ChildMxmap filtered by the username column
 * @method     ChildMxmap findOneByUploadedat(string $uploadedAt) Return the first ChildMxmap filtered by the uploadedAt column
 * @method     ChildMxmap findOneByUpdatedat(string $updatedAt) Return the first ChildMxmap filtered by the updatedAt column
 * @method     ChildMxmap findOneByMaptype(string $mapType) Return the first ChildMxmap filtered by the mapType column
 * @method     ChildMxmap findOneByTitlepack(string $titlePack) Return the first ChildMxmap filtered by the titlePack column
 * @method     ChildMxmap findOneByStylename(string $styleName) Return the first ChildMxmap filtered by the styleName column
 * @method     ChildMxmap findOneByDisplaycost(int $displayCost) Return the first ChildMxmap filtered by the displayCost column
 * @method     ChildMxmap findOneByModname(string $modName) Return the first ChildMxmap filtered by the modName column
 * @method     ChildMxmap findOneByLightmap(int $lightMap) Return the first ChildMxmap filtered by the lightMap column
 * @method     ChildMxmap findOneByExeversion(string $exeVersion) Return the first ChildMxmap filtered by the exeVersion column
 * @method     ChildMxmap findOneByExebuild(string $exeBuild) Return the first ChildMxmap filtered by the exeBuild column
 * @method     ChildMxmap findOneByEnvironmentname(string $environmentName) Return the first ChildMxmap filtered by the environmentName column
 * @method     ChildMxmap findOneByVehiclename(string $vehicleName) Return the first ChildMxmap filtered by the vehicleName column
 * @method     ChildMxmap findOneByUnlimiterrequired(boolean $unlimiterRequired) Return the first ChildMxmap filtered by the unlimiterRequired column
 * @method     ChildMxmap findOneByRoutename(string $routeName) Return the first ChildMxmap filtered by the routeName column
 * @method     ChildMxmap findOneByLengthname(string $lengthName) Return the first ChildMxmap filtered by the lengthName column
 * @method     ChildMxmap findOneByLaps(int $laps) Return the first ChildMxmap filtered by the laps column
 * @method     ChildMxmap findOneByDifficultyname(string $difficultyName) Return the first ChildMxmap filtered by the difficultyName column
 * @method     ChildMxmap findOneByReplaytypename(string $replayTypeName) Return the first ChildMxmap filtered by the replayTypeName column
 * @method     ChildMxmap findOneByReplaywrid(int $replayWRID) Return the first ChildMxmap filtered by the replayWRID column
 * @method     ChildMxmap findOneByReplaywrtime(int $replayWRTime) Return the first ChildMxmap filtered by the replayWRTime column
 * @method     ChildMxmap findOneByReplaywruserid(int $replayWRUserID) Return the first ChildMxmap filtered by the replayWRUserID column
 * @method     ChildMxmap findOneByReplaywrusername(string $replayWRUsername) Return the first ChildMxmap filtered by the replayWRUsername column
 * @method     ChildMxmap findOneByRatingvotecount(int $ratingVoteCount) Return the first ChildMxmap filtered by the ratingVoteCount column
 * @method     ChildMxmap findOneByRatingvoteaverage(double $ratingVoteAverage) Return the first ChildMxmap filtered by the ratingVoteAverage column
 * @method     ChildMxmap findOneByReplaycount(int $replayCount) Return the first ChildMxmap filtered by the replayCount column
 * @method     ChildMxmap findOneByTrackvalue(int $trackValue) Return the first ChildMxmap filtered by the trackValue column
 * @method     ChildMxmap findOneByComments(string $comments) Return the first ChildMxmap filtered by the comments column
 * @method     ChildMxmap findOneByCommentscount(int $commentsCount) Return the first ChildMxmap filtered by the commentsCount column
 * @method     ChildMxmap findOneByAwardcount(int $awardCount) Return the first ChildMxmap filtered by the awardCount column
 * @method     ChildMxmap findOneByHasscreenshot(boolean $hasScreenshot) Return the first ChildMxmap filtered by the hasScreenshot column
 * @method     ChildMxmap findOneByHasthumbnail(boolean $hasThumbnail) Return the first ChildMxmap filtered by the hasThumbnail column
 * @method     ChildMxmap findOneByHasghostblocks(boolean $hasGhostblocks) Return the first ChildMxmap filtered by the hasGhostblocks column
 * @method     ChildMxmap findOneByEmbeddedobjectscount(int $embeddedObjectsCount) Return the first ChildMxmap filtered by the embeddedObjectsCount column *

 * @method     ChildMxmap requirePk($key, ConnectionInterface $con = null) Return the ChildMxmap by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOne(ConnectionInterface $con = null) Return the first ChildMxmap matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMxmap requireOneById(int $id) Return the first ChildMxmap filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByTrackuid(string $trackUID) Return the first ChildMxmap filtered by the trackUID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByGbxmapname(string $gbxMapName) Return the first ChildMxmap filtered by the gbxMapName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByTrackid(int $trackID) Return the first ChildMxmap filtered by the trackID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByUserid(int $userID) Return the first ChildMxmap filtered by the userID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByUsername(string $username) Return the first ChildMxmap filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByUploadedat(string $uploadedAt) Return the first ChildMxmap filtered by the uploadedAt column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByUpdatedat(string $updatedAt) Return the first ChildMxmap filtered by the updatedAt column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByMaptype(string $mapType) Return the first ChildMxmap filtered by the mapType column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByTitlepack(string $titlePack) Return the first ChildMxmap filtered by the titlePack column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByStylename(string $styleName) Return the first ChildMxmap filtered by the styleName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByDisplaycost(int $displayCost) Return the first ChildMxmap filtered by the displayCost column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByModname(string $modName) Return the first ChildMxmap filtered by the modName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByLightmap(int $lightMap) Return the first ChildMxmap filtered by the lightMap column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByExeversion(string $exeVersion) Return the first ChildMxmap filtered by the exeVersion column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByExebuild(string $exeBuild) Return the first ChildMxmap filtered by the exeBuild column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByEnvironmentname(string $environmentName) Return the first ChildMxmap filtered by the environmentName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByVehiclename(string $vehicleName) Return the first ChildMxmap filtered by the vehicleName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByUnlimiterrequired(boolean $unlimiterRequired) Return the first ChildMxmap filtered by the unlimiterRequired column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByRoutename(string $routeName) Return the first ChildMxmap filtered by the routeName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByLengthname(string $lengthName) Return the first ChildMxmap filtered by the lengthName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByLaps(int $laps) Return the first ChildMxmap filtered by the laps column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByDifficultyname(string $difficultyName) Return the first ChildMxmap filtered by the difficultyName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByReplaytypename(string $replayTypeName) Return the first ChildMxmap filtered by the replayTypeName column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByReplaywrid(int $replayWRID) Return the first ChildMxmap filtered by the replayWRID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByReplaywrtime(int $replayWRTime) Return the first ChildMxmap filtered by the replayWRTime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByReplaywruserid(int $replayWRUserID) Return the first ChildMxmap filtered by the replayWRUserID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByReplaywrusername(string $replayWRUsername) Return the first ChildMxmap filtered by the replayWRUsername column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByRatingvotecount(int $ratingVoteCount) Return the first ChildMxmap filtered by the ratingVoteCount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByRatingvoteaverage(double $ratingVoteAverage) Return the first ChildMxmap filtered by the ratingVoteAverage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByReplaycount(int $replayCount) Return the first ChildMxmap filtered by the replayCount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByTrackvalue(int $trackValue) Return the first ChildMxmap filtered by the trackValue column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByComments(string $comments) Return the first ChildMxmap filtered by the comments column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByCommentscount(int $commentsCount) Return the first ChildMxmap filtered by the commentsCount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByAwardcount(int $awardCount) Return the first ChildMxmap filtered by the awardCount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByHasscreenshot(boolean $hasScreenshot) Return the first ChildMxmap filtered by the hasScreenshot column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByHasthumbnail(boolean $hasThumbnail) Return the first ChildMxmap filtered by the hasThumbnail column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByHasghostblocks(boolean $hasGhostblocks) Return the first ChildMxmap filtered by the hasGhostblocks column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMxmap requireOneByEmbeddedobjectscount(int $embeddedObjectsCount) Return the first ChildMxmap filtered by the embeddedObjectsCount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMxmap[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMxmap objects based on current ModelCriteria
 * @method     ChildMxmap[]|ObjectCollection findById(int $id) Return ChildMxmap objects filtered by the id column
 * @method     ChildMxmap[]|ObjectCollection findByTrackuid(string $trackUID) Return ChildMxmap objects filtered by the trackUID column
 * @method     ChildMxmap[]|ObjectCollection findByGbxmapname(string $gbxMapName) Return ChildMxmap objects filtered by the gbxMapName column
 * @method     ChildMxmap[]|ObjectCollection findByTrackid(int $trackID) Return ChildMxmap objects filtered by the trackID column
 * @method     ChildMxmap[]|ObjectCollection findByUserid(int $userID) Return ChildMxmap objects filtered by the userID column
 * @method     ChildMxmap[]|ObjectCollection findByUsername(string $username) Return ChildMxmap objects filtered by the username column
 * @method     ChildMxmap[]|ObjectCollection findByUploadedat(string $uploadedAt) Return ChildMxmap objects filtered by the uploadedAt column
 * @method     ChildMxmap[]|ObjectCollection findByUpdatedat(string $updatedAt) Return ChildMxmap objects filtered by the updatedAt column
 * @method     ChildMxmap[]|ObjectCollection findByMaptype(string $mapType) Return ChildMxmap objects filtered by the mapType column
 * @method     ChildMxmap[]|ObjectCollection findByTitlepack(string $titlePack) Return ChildMxmap objects filtered by the titlePack column
 * @method     ChildMxmap[]|ObjectCollection findByStylename(string $styleName) Return ChildMxmap objects filtered by the styleName column
 * @method     ChildMxmap[]|ObjectCollection findByDisplaycost(int $displayCost) Return ChildMxmap objects filtered by the displayCost column
 * @method     ChildMxmap[]|ObjectCollection findByModname(string $modName) Return ChildMxmap objects filtered by the modName column
 * @method     ChildMxmap[]|ObjectCollection findByLightmap(int $lightMap) Return ChildMxmap objects filtered by the lightMap column
 * @method     ChildMxmap[]|ObjectCollection findByExeversion(string $exeVersion) Return ChildMxmap objects filtered by the exeVersion column
 * @method     ChildMxmap[]|ObjectCollection findByExebuild(string $exeBuild) Return ChildMxmap objects filtered by the exeBuild column
 * @method     ChildMxmap[]|ObjectCollection findByEnvironmentname(string $environmentName) Return ChildMxmap objects filtered by the environmentName column
 * @method     ChildMxmap[]|ObjectCollection findByVehiclename(string $vehicleName) Return ChildMxmap objects filtered by the vehicleName column
 * @method     ChildMxmap[]|ObjectCollection findByUnlimiterrequired(boolean $unlimiterRequired) Return ChildMxmap objects filtered by the unlimiterRequired column
 * @method     ChildMxmap[]|ObjectCollection findByRoutename(string $routeName) Return ChildMxmap objects filtered by the routeName column
 * @method     ChildMxmap[]|ObjectCollection findByLengthname(string $lengthName) Return ChildMxmap objects filtered by the lengthName column
 * @method     ChildMxmap[]|ObjectCollection findByLaps(int $laps) Return ChildMxmap objects filtered by the laps column
 * @method     ChildMxmap[]|ObjectCollection findByDifficultyname(string $difficultyName) Return ChildMxmap objects filtered by the difficultyName column
 * @method     ChildMxmap[]|ObjectCollection findByReplaytypename(string $replayTypeName) Return ChildMxmap objects filtered by the replayTypeName column
 * @method     ChildMxmap[]|ObjectCollection findByReplaywrid(int $replayWRID) Return ChildMxmap objects filtered by the replayWRID column
 * @method     ChildMxmap[]|ObjectCollection findByReplaywrtime(int $replayWRTime) Return ChildMxmap objects filtered by the replayWRTime column
 * @method     ChildMxmap[]|ObjectCollection findByReplaywruserid(int $replayWRUserID) Return ChildMxmap objects filtered by the replayWRUserID column
 * @method     ChildMxmap[]|ObjectCollection findByReplaywrusername(string $replayWRUsername) Return ChildMxmap objects filtered by the replayWRUsername column
 * @method     ChildMxmap[]|ObjectCollection findByRatingvotecount(int $ratingVoteCount) Return ChildMxmap objects filtered by the ratingVoteCount column
 * @method     ChildMxmap[]|ObjectCollection findByRatingvoteaverage(double $ratingVoteAverage) Return ChildMxmap objects filtered by the ratingVoteAverage column
 * @method     ChildMxmap[]|ObjectCollection findByReplaycount(int $replayCount) Return ChildMxmap objects filtered by the replayCount column
 * @method     ChildMxmap[]|ObjectCollection findByTrackvalue(int $trackValue) Return ChildMxmap objects filtered by the trackValue column
 * @method     ChildMxmap[]|ObjectCollection findByComments(string $comments) Return ChildMxmap objects filtered by the comments column
 * @method     ChildMxmap[]|ObjectCollection findByCommentscount(int $commentsCount) Return ChildMxmap objects filtered by the commentsCount column
 * @method     ChildMxmap[]|ObjectCollection findByAwardcount(int $awardCount) Return ChildMxmap objects filtered by the awardCount column
 * @method     ChildMxmap[]|ObjectCollection findByHasscreenshot(boolean $hasScreenshot) Return ChildMxmap objects filtered by the hasScreenshot column
 * @method     ChildMxmap[]|ObjectCollection findByHasthumbnail(boolean $hasThumbnail) Return ChildMxmap objects filtered by the hasThumbnail column
 * @method     ChildMxmap[]|ObjectCollection findByHasghostblocks(boolean $hasGhostblocks) Return ChildMxmap objects filtered by the hasGhostblocks column
 * @method     ChildMxmap[]|ObjectCollection findByEmbeddedobjectscount(int $embeddedObjectsCount) Return ChildMxmap objects filtered by the embeddedObjectsCount column
 * @method     ChildMxmap[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MxmapQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \eXpansion\Bundle\Maps\Model\Base\MxmapQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'expansion', $modelName = '\\eXpansion\\Bundle\\Maps\\Model\\Mxmap', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMxmapQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMxmapQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMxmapQuery) {
            return $criteria;
        }
        $query = new ChildMxmapQuery();
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
     * @return ChildMxmap|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MxmapTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MxmapTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMxmap A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, trackUID, gbxMapName, trackID, userID, username, uploadedAt, updatedAt, mapType, titlePack, styleName, displayCost, modName, lightMap, exeVersion, exeBuild, environmentName, vehicleName, unlimiterRequired, routeName, lengthName, laps, difficultyName, replayTypeName, replayWRID, replayWRTime, replayWRUserID, replayWRUsername, ratingVoteCount, ratingVoteAverage, replayCount, trackValue, comments, commentsCount, awardCount, hasScreenshot, hasThumbnail, hasGhostblocks, embeddedObjectsCount FROM mxmap WHERE id = :p0';
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
            /** @var ChildMxmap $obj */
            $obj = new ChildMxmap();
            $obj->hydrate($row);
            MxmapTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMxmap|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MxmapTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MxmapTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the trackUID column
     *
     * Example usage:
     * <code>
     * $query->filterByTrackuid('fooValue');   // WHERE trackUID = 'fooValue'
     * $query->filterByTrackuid('%fooValue%', Criteria::LIKE); // WHERE trackUID LIKE '%fooValue%'
     * </code>
     *
     * @param     string $trackuid The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByTrackuid($trackuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($trackuid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_TRACKUID, $trackuid, $comparison);
    }

    /**
     * Filter the query on the gbxMapName column
     *
     * Example usage:
     * <code>
     * $query->filterByGbxmapname('fooValue');   // WHERE gbxMapName = 'fooValue'
     * $query->filterByGbxmapname('%fooValue%', Criteria::LIKE); // WHERE gbxMapName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $gbxmapname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByGbxmapname($gbxmapname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($gbxmapname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_GBXMAPNAME, $gbxmapname, $comparison);
    }

    /**
     * Filter the query on the trackID column
     *
     * Example usage:
     * <code>
     * $query->filterByTrackid(1234); // WHERE trackID = 1234
     * $query->filterByTrackid(array(12, 34)); // WHERE trackID IN (12, 34)
     * $query->filterByTrackid(array('min' => 12)); // WHERE trackID > 12
     * </code>
     *
     * @param     mixed $trackid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByTrackid($trackid = null, $comparison = null)
    {
        if (is_array($trackid)) {
            $useMinMax = false;
            if (isset($trackid['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_TRACKID, $trackid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($trackid['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_TRACKID, $trackid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_TRACKID, $trackid, $comparison);
    }

    /**
     * Filter the query on the userID column
     *
     * Example usage:
     * <code>
     * $query->filterByUserid(1234); // WHERE userID = 1234
     * $query->filterByUserid(array(12, 34)); // WHERE userID IN (12, 34)
     * $query->filterByUserid(array('min' => 12)); // WHERE userID > 12
     * </code>
     *
     * @param     mixed $userid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByUserid($userid = null, $comparison = null)
    {
        if (is_array($userid)) {
            $useMinMax = false;
            if (isset($userid['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_USERID, $userid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userid['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_USERID, $userid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_USERID, $userid, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_USERNAME, $username, $comparison);
    }

    /**
     * Filter the query on the uploadedAt column
     *
     * Example usage:
     * <code>
     * $query->filterByUploadedat('2011-03-14'); // WHERE uploadedAt = '2011-03-14'
     * $query->filterByUploadedat('now'); // WHERE uploadedAt = '2011-03-14'
     * $query->filterByUploadedat(array('max' => 'yesterday')); // WHERE uploadedAt > '2011-03-13'
     * </code>
     *
     * @param     mixed $uploadedat The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByUploadedat($uploadedat = null, $comparison = null)
    {
        if (is_array($uploadedat)) {
            $useMinMax = false;
            if (isset($uploadedat['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_UPLOADEDAT, $uploadedat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($uploadedat['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_UPLOADEDAT, $uploadedat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_UPLOADEDAT, $uploadedat, $comparison);
    }

    /**
     * Filter the query on the updatedAt column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedat('2011-03-14'); // WHERE updatedAt = '2011-03-14'
     * $query->filterByUpdatedat('now'); // WHERE updatedAt = '2011-03-14'
     * $query->filterByUpdatedat(array('max' => 'yesterday')); // WHERE updatedAt > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedat The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByUpdatedat($updatedat = null, $comparison = null)
    {
        if (is_array($updatedat)) {
            $useMinMax = false;
            if (isset($updatedat['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_UPDATEDAT, $updatedat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedat['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_UPDATEDAT, $updatedat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_UPDATEDAT, $updatedat, $comparison);
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
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByMaptype($maptype = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($maptype)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_MAPTYPE, $maptype, $comparison);
    }

    /**
     * Filter the query on the titlePack column
     *
     * Example usage:
     * <code>
     * $query->filterByTitlepack('fooValue');   // WHERE titlePack = 'fooValue'
     * $query->filterByTitlepack('%fooValue%', Criteria::LIKE); // WHERE titlePack LIKE '%fooValue%'
     * </code>
     *
     * @param     string $titlepack The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByTitlepack($titlepack = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($titlepack)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_TITLEPACK, $titlepack, $comparison);
    }

    /**
     * Filter the query on the styleName column
     *
     * Example usage:
     * <code>
     * $query->filterByStylename('fooValue');   // WHERE styleName = 'fooValue'
     * $query->filterByStylename('%fooValue%', Criteria::LIKE); // WHERE styleName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $stylename The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByStylename($stylename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stylename)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_STYLENAME, $stylename, $comparison);
    }

    /**
     * Filter the query on the displayCost column
     *
     * Example usage:
     * <code>
     * $query->filterByDisplaycost(1234); // WHERE displayCost = 1234
     * $query->filterByDisplaycost(array(12, 34)); // WHERE displayCost IN (12, 34)
     * $query->filterByDisplaycost(array('min' => 12)); // WHERE displayCost > 12
     * </code>
     *
     * @param     mixed $displaycost The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByDisplaycost($displaycost = null, $comparison = null)
    {
        if (is_array($displaycost)) {
            $useMinMax = false;
            if (isset($displaycost['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_DISPLAYCOST, $displaycost['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($displaycost['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_DISPLAYCOST, $displaycost['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_DISPLAYCOST, $displaycost, $comparison);
    }

    /**
     * Filter the query on the modName column
     *
     * Example usage:
     * <code>
     * $query->filterByModname('fooValue');   // WHERE modName = 'fooValue'
     * $query->filterByModname('%fooValue%', Criteria::LIKE); // WHERE modName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $modname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByModname($modname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($modname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_MODNAME, $modname, $comparison);
    }

    /**
     * Filter the query on the lightMap column
     *
     * Example usage:
     * <code>
     * $query->filterByLightmap(1234); // WHERE lightMap = 1234
     * $query->filterByLightmap(array(12, 34)); // WHERE lightMap IN (12, 34)
     * $query->filterByLightmap(array('min' => 12)); // WHERE lightMap > 12
     * </code>
     *
     * @param     mixed $lightmap The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByLightmap($lightmap = null, $comparison = null)
    {
        if (is_array($lightmap)) {
            $useMinMax = false;
            if (isset($lightmap['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_LIGHTMAP, $lightmap['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lightmap['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_LIGHTMAP, $lightmap['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_LIGHTMAP, $lightmap, $comparison);
    }

    /**
     * Filter the query on the exeVersion column
     *
     * Example usage:
     * <code>
     * $query->filterByExeversion('fooValue');   // WHERE exeVersion = 'fooValue'
     * $query->filterByExeversion('%fooValue%', Criteria::LIKE); // WHERE exeVersion LIKE '%fooValue%'
     * </code>
     *
     * @param     string $exeversion The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByExeversion($exeversion = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($exeversion)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_EXEVERSION, $exeversion, $comparison);
    }

    /**
     * Filter the query on the exeBuild column
     *
     * Example usage:
     * <code>
     * $query->filterByExebuild('fooValue');   // WHERE exeBuild = 'fooValue'
     * $query->filterByExebuild('%fooValue%', Criteria::LIKE); // WHERE exeBuild LIKE '%fooValue%'
     * </code>
     *
     * @param     string $exebuild The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByExebuild($exebuild = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($exebuild)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_EXEBUILD, $exebuild, $comparison);
    }

    /**
     * Filter the query on the environmentName column
     *
     * Example usage:
     * <code>
     * $query->filterByEnvironmentname('fooValue');   // WHERE environmentName = 'fooValue'
     * $query->filterByEnvironmentname('%fooValue%', Criteria::LIKE); // WHERE environmentName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $environmentname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByEnvironmentname($environmentname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($environmentname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_ENVIRONMENTNAME, $environmentname, $comparison);
    }

    /**
     * Filter the query on the vehicleName column
     *
     * Example usage:
     * <code>
     * $query->filterByVehiclename('fooValue');   // WHERE vehicleName = 'fooValue'
     * $query->filterByVehiclename('%fooValue%', Criteria::LIKE); // WHERE vehicleName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $vehiclename The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByVehiclename($vehiclename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($vehiclename)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_VEHICLENAME, $vehiclename, $comparison);
    }

    /**
     * Filter the query on the unlimiterRequired column
     *
     * Example usage:
     * <code>
     * $query->filterByUnlimiterrequired(true); // WHERE unlimiterRequired = true
     * $query->filterByUnlimiterrequired('yes'); // WHERE unlimiterRequired = true
     * </code>
     *
     * @param     boolean|string $unlimiterrequired The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByUnlimiterrequired($unlimiterrequired = null, $comparison = null)
    {
        if (is_string($unlimiterrequired)) {
            $unlimiterrequired = in_array(strtolower($unlimiterrequired), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MxmapTableMap::COL_UNLIMITERREQUIRED, $unlimiterrequired, $comparison);
    }

    /**
     * Filter the query on the routeName column
     *
     * Example usage:
     * <code>
     * $query->filterByRoutename('fooValue');   // WHERE routeName = 'fooValue'
     * $query->filterByRoutename('%fooValue%', Criteria::LIKE); // WHERE routeName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $routename The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByRoutename($routename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($routename)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_ROUTENAME, $routename, $comparison);
    }

    /**
     * Filter the query on the lengthName column
     *
     * Example usage:
     * <code>
     * $query->filterByLengthname('fooValue');   // WHERE lengthName = 'fooValue'
     * $query->filterByLengthname('%fooValue%', Criteria::LIKE); // WHERE lengthName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lengthname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByLengthname($lengthname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lengthname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_LENGTHNAME, $lengthname, $comparison);
    }

    /**
     * Filter the query on the laps column
     *
     * Example usage:
     * <code>
     * $query->filterByLaps(1234); // WHERE laps = 1234
     * $query->filterByLaps(array(12, 34)); // WHERE laps IN (12, 34)
     * $query->filterByLaps(array('min' => 12)); // WHERE laps > 12
     * </code>
     *
     * @param     mixed $laps The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByLaps($laps = null, $comparison = null)
    {
        if (is_array($laps)) {
            $useMinMax = false;
            if (isset($laps['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_LAPS, $laps['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($laps['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_LAPS, $laps['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_LAPS, $laps, $comparison);
    }

    /**
     * Filter the query on the difficultyName column
     *
     * Example usage:
     * <code>
     * $query->filterByDifficultyname('fooValue');   // WHERE difficultyName = 'fooValue'
     * $query->filterByDifficultyname('%fooValue%', Criteria::LIKE); // WHERE difficultyName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $difficultyname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByDifficultyname($difficultyname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($difficultyname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_DIFFICULTYNAME, $difficultyname, $comparison);
    }

    /**
     * Filter the query on the replayTypeName column
     *
     * Example usage:
     * <code>
     * $query->filterByReplaytypename('fooValue');   // WHERE replayTypeName = 'fooValue'
     * $query->filterByReplaytypename('%fooValue%', Criteria::LIKE); // WHERE replayTypeName LIKE '%fooValue%'
     * </code>
     *
     * @param     string $replaytypename The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByReplaytypename($replaytypename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($replaytypename)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_REPLAYTYPENAME, $replaytypename, $comparison);
    }

    /**
     * Filter the query on the replayWRID column
     *
     * Example usage:
     * <code>
     * $query->filterByReplaywrid(1234); // WHERE replayWRID = 1234
     * $query->filterByReplaywrid(array(12, 34)); // WHERE replayWRID IN (12, 34)
     * $query->filterByReplaywrid(array('min' => 12)); // WHERE replayWRID > 12
     * </code>
     *
     * @param     mixed $replaywrid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByReplaywrid($replaywrid = null, $comparison = null)
    {
        if (is_array($replaywrid)) {
            $useMinMax = false;
            if (isset($replaywrid['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRID, $replaywrid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($replaywrid['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRID, $replaywrid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRID, $replaywrid, $comparison);
    }

    /**
     * Filter the query on the replayWRTime column
     *
     * Example usage:
     * <code>
     * $query->filterByReplaywrtime(1234); // WHERE replayWRTime = 1234
     * $query->filterByReplaywrtime(array(12, 34)); // WHERE replayWRTime IN (12, 34)
     * $query->filterByReplaywrtime(array('min' => 12)); // WHERE replayWRTime > 12
     * </code>
     *
     * @param     mixed $replaywrtime The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByReplaywrtime($replaywrtime = null, $comparison = null)
    {
        if (is_array($replaywrtime)) {
            $useMinMax = false;
            if (isset($replaywrtime['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRTIME, $replaywrtime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($replaywrtime['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRTIME, $replaywrtime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRTIME, $replaywrtime, $comparison);
    }

    /**
     * Filter the query on the replayWRUserID column
     *
     * Example usage:
     * <code>
     * $query->filterByReplaywruserid(1234); // WHERE replayWRUserID = 1234
     * $query->filterByReplaywruserid(array(12, 34)); // WHERE replayWRUserID IN (12, 34)
     * $query->filterByReplaywruserid(array('min' => 12)); // WHERE replayWRUserID > 12
     * </code>
     *
     * @param     mixed $replaywruserid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByReplaywruserid($replaywruserid = null, $comparison = null)
    {
        if (is_array($replaywruserid)) {
            $useMinMax = false;
            if (isset($replaywruserid['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRUSERID, $replaywruserid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($replaywruserid['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRUSERID, $replaywruserid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRUSERID, $replaywruserid, $comparison);
    }

    /**
     * Filter the query on the replayWRUsername column
     *
     * Example usage:
     * <code>
     * $query->filterByReplaywrusername('fooValue');   // WHERE replayWRUsername = 'fooValue'
     * $query->filterByReplaywrusername('%fooValue%', Criteria::LIKE); // WHERE replayWRUsername LIKE '%fooValue%'
     * </code>
     *
     * @param     string $replaywrusername The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByReplaywrusername($replaywrusername = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($replaywrusername)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_REPLAYWRUSERNAME, $replaywrusername, $comparison);
    }

    /**
     * Filter the query on the ratingVoteCount column
     *
     * Example usage:
     * <code>
     * $query->filterByRatingvotecount(1234); // WHERE ratingVoteCount = 1234
     * $query->filterByRatingvotecount(array(12, 34)); // WHERE ratingVoteCount IN (12, 34)
     * $query->filterByRatingvotecount(array('min' => 12)); // WHERE ratingVoteCount > 12
     * </code>
     *
     * @param     mixed $ratingvotecount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByRatingvotecount($ratingvotecount = null, $comparison = null)
    {
        if (is_array($ratingvotecount)) {
            $useMinMax = false;
            if (isset($ratingvotecount['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_RATINGVOTECOUNT, $ratingvotecount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ratingvotecount['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_RATINGVOTECOUNT, $ratingvotecount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_RATINGVOTECOUNT, $ratingvotecount, $comparison);
    }

    /**
     * Filter the query on the ratingVoteAverage column
     *
     * Example usage:
     * <code>
     * $query->filterByRatingvoteaverage(1234); // WHERE ratingVoteAverage = 1234
     * $query->filterByRatingvoteaverage(array(12, 34)); // WHERE ratingVoteAverage IN (12, 34)
     * $query->filterByRatingvoteaverage(array('min' => 12)); // WHERE ratingVoteAverage > 12
     * </code>
     *
     * @param     mixed $ratingvoteaverage The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByRatingvoteaverage($ratingvoteaverage = null, $comparison = null)
    {
        if (is_array($ratingvoteaverage)) {
            $useMinMax = false;
            if (isset($ratingvoteaverage['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_RATINGVOTEAVERAGE, $ratingvoteaverage['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ratingvoteaverage['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_RATINGVOTEAVERAGE, $ratingvoteaverage['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_RATINGVOTEAVERAGE, $ratingvoteaverage, $comparison);
    }

    /**
     * Filter the query on the replayCount column
     *
     * Example usage:
     * <code>
     * $query->filterByReplaycount(1234); // WHERE replayCount = 1234
     * $query->filterByReplaycount(array(12, 34)); // WHERE replayCount IN (12, 34)
     * $query->filterByReplaycount(array('min' => 12)); // WHERE replayCount > 12
     * </code>
     *
     * @param     mixed $replaycount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByReplaycount($replaycount = null, $comparison = null)
    {
        if (is_array($replaycount)) {
            $useMinMax = false;
            if (isset($replaycount['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYCOUNT, $replaycount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($replaycount['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_REPLAYCOUNT, $replaycount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_REPLAYCOUNT, $replaycount, $comparison);
    }

    /**
     * Filter the query on the trackValue column
     *
     * Example usage:
     * <code>
     * $query->filterByTrackvalue(1234); // WHERE trackValue = 1234
     * $query->filterByTrackvalue(array(12, 34)); // WHERE trackValue IN (12, 34)
     * $query->filterByTrackvalue(array('min' => 12)); // WHERE trackValue > 12
     * </code>
     *
     * @param     mixed $trackvalue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByTrackvalue($trackvalue = null, $comparison = null)
    {
        if (is_array($trackvalue)) {
            $useMinMax = false;
            if (isset($trackvalue['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_TRACKVALUE, $trackvalue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($trackvalue['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_TRACKVALUE, $trackvalue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_TRACKVALUE, $trackvalue, $comparison);
    }

    /**
     * Filter the query on the comments column
     *
     * Example usage:
     * <code>
     * $query->filterByComments('fooValue');   // WHERE comments = 'fooValue'
     * $query->filterByComments('%fooValue%', Criteria::LIKE); // WHERE comments LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comments The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByComments($comments = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comments)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_COMMENTS, $comments, $comparison);
    }

    /**
     * Filter the query on the commentsCount column
     *
     * Example usage:
     * <code>
     * $query->filterByCommentscount(1234); // WHERE commentsCount = 1234
     * $query->filterByCommentscount(array(12, 34)); // WHERE commentsCount IN (12, 34)
     * $query->filterByCommentscount(array('min' => 12)); // WHERE commentsCount > 12
     * </code>
     *
     * @param     mixed $commentscount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByCommentscount($commentscount = null, $comparison = null)
    {
        if (is_array($commentscount)) {
            $useMinMax = false;
            if (isset($commentscount['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_COMMENTSCOUNT, $commentscount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($commentscount['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_COMMENTSCOUNT, $commentscount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_COMMENTSCOUNT, $commentscount, $comparison);
    }

    /**
     * Filter the query on the awardCount column
     *
     * Example usage:
     * <code>
     * $query->filterByAwardcount(1234); // WHERE awardCount = 1234
     * $query->filterByAwardcount(array(12, 34)); // WHERE awardCount IN (12, 34)
     * $query->filterByAwardcount(array('min' => 12)); // WHERE awardCount > 12
     * </code>
     *
     * @param     mixed $awardcount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByAwardcount($awardcount = null, $comparison = null)
    {
        if (is_array($awardcount)) {
            $useMinMax = false;
            if (isset($awardcount['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_AWARDCOUNT, $awardcount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($awardcount['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_AWARDCOUNT, $awardcount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_AWARDCOUNT, $awardcount, $comparison);
    }

    /**
     * Filter the query on the hasScreenshot column
     *
     * Example usage:
     * <code>
     * $query->filterByHasscreenshot(true); // WHERE hasScreenshot = true
     * $query->filterByHasscreenshot('yes'); // WHERE hasScreenshot = true
     * </code>
     *
     * @param     boolean|string $hasscreenshot The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByHasscreenshot($hasscreenshot = null, $comparison = null)
    {
        if (is_string($hasscreenshot)) {
            $hasscreenshot = in_array(strtolower($hasscreenshot), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MxmapTableMap::COL_HASSCREENSHOT, $hasscreenshot, $comparison);
    }

    /**
     * Filter the query on the hasThumbnail column
     *
     * Example usage:
     * <code>
     * $query->filterByHasthumbnail(true); // WHERE hasThumbnail = true
     * $query->filterByHasthumbnail('yes'); // WHERE hasThumbnail = true
     * </code>
     *
     * @param     boolean|string $hasthumbnail The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByHasthumbnail($hasthumbnail = null, $comparison = null)
    {
        if (is_string($hasthumbnail)) {
            $hasthumbnail = in_array(strtolower($hasthumbnail), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MxmapTableMap::COL_HASTHUMBNAIL, $hasthumbnail, $comparison);
    }

    /**
     * Filter the query on the hasGhostblocks column
     *
     * Example usage:
     * <code>
     * $query->filterByHasghostblocks(true); // WHERE hasGhostblocks = true
     * $query->filterByHasghostblocks('yes'); // WHERE hasGhostblocks = true
     * </code>
     *
     * @param     boolean|string $hasghostblocks The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByHasghostblocks($hasghostblocks = null, $comparison = null)
    {
        if (is_string($hasghostblocks)) {
            $hasghostblocks = in_array(strtolower($hasghostblocks), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MxmapTableMap::COL_HASGHOSTBLOCKS, $hasghostblocks, $comparison);
    }

    /**
     * Filter the query on the embeddedObjectsCount column
     *
     * Example usage:
     * <code>
     * $query->filterByEmbeddedobjectscount(1234); // WHERE embeddedObjectsCount = 1234
     * $query->filterByEmbeddedobjectscount(array(12, 34)); // WHERE embeddedObjectsCount IN (12, 34)
     * $query->filterByEmbeddedobjectscount(array('min' => 12)); // WHERE embeddedObjectsCount > 12
     * </code>
     *
     * @param     mixed $embeddedobjectscount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByEmbeddedobjectscount($embeddedobjectscount = null, $comparison = null)
    {
        if (is_array($embeddedobjectscount)) {
            $useMinMax = false;
            if (isset($embeddedobjectscount['min'])) {
                $this->addUsingAlias(MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT, $embeddedobjectscount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($embeddedobjectscount['max'])) {
                $this->addUsingAlias(MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT, $embeddedobjectscount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MxmapTableMap::COL_EMBEDDEDOBJECTSCOUNT, $embeddedobjectscount, $comparison);
    }

    /**
     * Filter the query by a related \eXpansion\Bundle\Maps\Model\Map object
     *
     * @param \eXpansion\Bundle\Maps\Model\Map|ObjectCollection $map The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMxmapQuery The current query, for fluid interface
     */
    public function filterByMap($map, $comparison = null)
    {
        if ($map instanceof \eXpansion\Bundle\Maps\Model\Map) {
            return $this
                ->addUsingAlias(MxmapTableMap::COL_TRACKUID, $map->getMapuid(), $comparison);
        } elseif ($map instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MxmapTableMap::COL_TRACKUID, $map->toKeyValue('PrimaryKey', 'Mapuid'), $comparison);
        } else {
            throw new PropelException('filterByMap() only accepts arguments of type \eXpansion\Bundle\Maps\Model\Map or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Map relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function joinMap($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Map');

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
            $this->addJoinObject($join, 'Map');
        }

        return $this;
    }

    /**
     * Use the Map relation Map object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \eXpansion\Bundle\Maps\Model\MapQuery A secondary query class using the current class as primary query
     */
    public function useMapQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMap($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Map', '\eXpansion\Bundle\Maps\Model\MapQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMxmap $mxmap Object to remove from the list of results
     *
     * @return $this|ChildMxmapQuery The current query, for fluid interface
     */
    public function prune($mxmap = null)
    {
        if ($mxmap) {
            $this->addUsingAlias(MxmapTableMap::COL_ID, $mxmap->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the mxmap table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MxmapTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MxmapTableMap::clearInstancePool();
            MxmapTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MxmapTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MxmapTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MxmapTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MxmapTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MxmapQuery
