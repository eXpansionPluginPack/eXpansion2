<?php

namespace eXpansion\Framework\Core\Storage;

use eXpansion\Framework\Core\Helpers\Countries;
use Maniaplanet\DedicatedServer\Structures\GameInfos;
use Maniaplanet\DedicatedServer\Structures\ServerOptions;
use Maniaplanet\DedicatedServer\Structures\SystemInfos;
use Maniaplanet\DedicatedServer\Structures\Version;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class GameDataStorage
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package   eXpansion\Framework\Core\Storage
 */
class GameDataStorage
{
    /**
     * Constant used for unknown game modes.
     */
    const GAME_MODE_CODE_UNKNOWN = 'unknown';

    /**
     * Constant used for unknown titles.
     */
    const TITLE_UNKNOWN = 'unknown';

    /**
     * Constants for the operating system.
     */
    const OS_LINUX = 'Linux';
    const OS_WINDOWS = 'Windows';
    const OS_MAC = 'Mac';


    /** @var Countries */
    protected $countriesHelper;

    /** @var  SystemInfos */
    protected $systemInfo;

    /** @var  ServerOptions */
    protected $serverOptions;

    /** @var array */
    protected $scriptOptions;

    /** @var  GameInfos */
    protected $gameInfos;

    /** @var Version */
    protected $version;

    /**
     * @var AssociativeArray
     */
    protected $gameModeCodes;

    /** @var string */
    protected $mapFolder;

    /**
     * @var AssociativeArray
     */
    protected $titles;

    /** @var string */
    protected $serverCleanPhpVersion;

    /** @var string */
    protected $serverMajorPhpVersion;

    /**
     * GameDataStorage constructor.
     *
     * @param Countries $countries
     * @param array $gameModeCodes
     * @param array $titles
     */
    public function __construct(Countries $countries, array $gameModeCodes, array $titles)
    {
        $this->gameModeCodes = new AssociativeArray($gameModeCodes);
        $this->titles = new AssociativeArray($titles);

        $version = explode('-', phpversion());
        $this->serverCleanPhpVersion = $version[0];
        $this->serverMajorPhpVersion = implode(
            '.',
            array_slice(explode('.', $this->serverCleanPhpVersion),0,2)
        );
    }


    /**
     * @return GameInfos
     */
    public function getGameInfos()
    {
        return $this->gameInfos;
    }

    /**
     * @param GameInfos $gameInfos
     */
    public function setGameInfos($gameInfos)
    {
        $this->gameInfos = $gameInfos;
    }

    /**
     * @return Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param Version $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Get code of the game mode.
     *
     * @return mixed
     */
    public function getGameModeCode()
    {
        return $this->gameModeCodes->get($this->getGameInfos()->gameMode, self::GAME_MODE_CODE_UNKNOWN);
    }

    /**
     * Get the title name, this returns a simplified title name such as TM
     *
     * @return mixed
     */
    public function getTitle()
    {

        $title = $this->titles->get($this->getVersion()->titleId, self::TITLE_UNKNOWN);
        if ($title == self::TITLE_UNKNOWN) {
            if (substr($this->getVersion()->titleId, 0, 2) == "TM") {
                return "TM";
            }
            if (substr($this->getVersion()->titleId, 0, 2) == "SM") {
                return "SM";
            }
        }

        return $title;
    }

    /**
     * @return ServerOptions
     */
    public function getServerOptions()
    {
        return $this->serverOptions;
    }

    /**
     * @param ServerOptions $serverOptions
     */
    public function setServerOptions($serverOptions)
    {
        $this->serverOptions = $serverOptions;
    }

    /**
     * @return array
     */
    public function getScriptOptions(): array
    {
        return $this->scriptOptions;
    }

    /**
     * @param array $scriptOptions
     */
    public function setScriptOptions(array $scriptOptions)
    {
        $this->scriptOptions = $scriptOptions;
    }

    /**
     * @param Systeminfos $systemInfo
     */
    public function setSystemInfo(Systeminfos $systemInfo)
    {
        $this->systemInfo = $systemInfo;
    }

    /**
     * @return SystemInfos
     */
    public function getSystemInfo()
    {
        return $this->systemInfo;
    }

    /**
     * @return string
     */
    public function getMapFolder(): string
    {
        return $this->mapFolder;
    }

    /**
     * @param string $mapFolder
     */
    public function setMapFolder(string $mapFolder)
    {
        $this->mapFolder = $mapFolder;
    }

    /**
     * Get the country the server is on.
     *
     * @return string
     */
    public function getServerCountry()
    {
        return 'Other';
    }

    /**
     * Get Operating system.
     *
     * @return string
     */
    public function getServerOs()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return self::OS_WINDOWS;
        } else {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'MAC') {
                return self::OS_MAC;
            } else {
                return self::OS_LINUX;
            }
        }
    }

    /**
     * Get clean php version without build information.
     */
    public function getServerCleanPhpVersion()
    {
        return $this->serverCleanPhpVersion;
    }

    /**
     * Get the major php version numbers. 7.0 for exemple.
     */
    public function getServerMajorPhpVersion()
    {
        return $this->serverMajorPhpVersion;
    }
}
