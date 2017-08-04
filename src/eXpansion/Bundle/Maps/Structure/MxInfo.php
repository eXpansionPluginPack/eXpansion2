<?php

namespace eXpansion\Bundle\Maps\Structure;

class MxInfo
{
    /** @var integer */
    public $TrackID;
    /** @var integer */
    public $UserID;
    /** @var string */
    public $Username;
    /** @var string */
    public $UploadedAt;
    /** @var string */
    public $UpdatedAt;
    /** @var string */
    public $Name;
    /** @var string */
    public $TypeName;
    /** @var string */
    public $MapType;
    /** @var  string */
    public $TitlePack;
    /** @var string */
    public $StyleName;
    /** @var string */
    public $Mood;
    /** @var integer */
    public $DisplayCost;
    /** @var string */
    public $ModName;
    /** @var integer */
    public $Lightmap;
    /** @var string */
    public $ExeVersion;
    /** @var string */
    public $ExeBuild;
    /** @var string */
    public $EnvironmentName;
    /** @var string */
    public $VehicleName;
    /** @var string */
    public $RouteName;
    /** @var string */
    public $LengthName;
    /** @var integer */
    public $Laps;
    /** @var string */
    public $DifficultyName;
    /** @var string */
    public $ReplayTypeName;
    /** @var integer */
    public $ReplayWRID;
    /** @var integer */
    public $ReplayCount;
    /** @var integer */
    public $TrackValue;
    /** @var string */
    public $Comments;
    /** @var integer */
    public $AwardCount;
    /** @var integer */
    public $CommentCount;
    /** @var integer */
    public $ReplayWRTime;
    /** @var integer */
    public $ReplayWRUserID;
    /** @var string */
    public $ReplayWRUsername;
    /** @var boolean */
    public $UnlimiterRequired;
    /** @var string */
    public $TrackUID;
    /** @var boolean */
    public $Unreleased;
    /** @var string */
    public $GbxMapName;
    /** @var integer */
    public $RatingVoteCount;
    /** @var integer */
    public $RatingVoteAverage;
    /** @var boolean */
    public $HasScreenshot;
    /** @var boolean */
    public $HasThumbnail;
    /** @var boolean */
    public $HasGhostBlocks;
    /** @var integer */
    public $EmbeddedObjectsCount;


    public function __construct($mxinfo)
    {
        if ($mxinfo !== null) {
            foreach ($mxinfo as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }
}
