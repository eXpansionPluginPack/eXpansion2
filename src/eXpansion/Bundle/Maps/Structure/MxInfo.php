<?php

namespace eXpansion\Bundle\Maps\Structure;

class MxInfo
{
    /** @var integer */
    public $trackID;
    /** @var integer */
    public $userID;
    /** @var string */
    public $username;
    /** @var string */
    public $uploadedAt;
    /** @var string */
    public $updatedAt;
    /** @var string */
    public $name;
    /** @var string */
    public $typeName;
    /** @var string */
    public $mapType;
    /** @var  string */
    public $titlePack;
    /** @var string */
    public $styleName;
    /** @var string */
    public $mood;
    /** @var integer */
    public $displayCost;
    /** @var string */
    public $modName;
    /** @var integer */
    public $lightmap;
    /** @var string */
    public $exeVersion;
    /** @var string */
    public $exeBuild;
    /** @var string */
    public $environmentName;
    /** @var string */
    public $vehicleName;
    /** @var string */
    public $routeName;
    /** @var string */
    public $lengthName;
    /** @var integer */
    public $laps;
    /** @var string */
    public $difficultyName;
    /** @var string */
    public $replayTypeName;
    /** @var integer */
    public $replayWRID;
    /** @var integer */
    public $replayCount;
    /** @var integer */
    public $trackValue;
    /** @var string */
    public $comments;
    /** @var integer */
    public $awardCount;
    /** @var integer */
    public $commentCount;
    /** @var integer */
    public $replayWRTime;
    /** @var integer */
    public $replayWRUserID;
    /** @var string */
    public $replayWRUsername;
    /** @var boolean */
    public $unlimiterRequired;
    /** @var string */
    public $trackUID;
    /** @var boolean */
    public $unreleased;
    /** @var string */
    public $gbxMapName;
    /** @var integer */
    public $ratingVoteCount;
    /** @var integer */
    public $ratingVoteAverage;
    /** @var boolean */
    public $hasScreenshot;
    /** @var boolean */
    public $hasThumbnail;
    /** @var boolean */
    public $hasGhostBlocks;
    /** @var integer */
    public $embeddedObjectsCount;


    public function __construct($mxinfo)
    {
        if ($mxinfo !== null) {
            foreach ($mxinfo as $key => $value) {
                $this->{lcfirst($key)} = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = [];
        foreach ($this as $key => $value) {
            $arr[$key] = $value;
        }

        return $arr;
    }

}
