<?php


namespace eXpansion\Framework\Core\Helpers;
use eXpansion\Bundle\Maps\Model\Map;
use oliverde8\AssociativeArraySimplified\AssociativeArray;


/**
 * Class CompatibleFetcher
 *
 * @package eXpansion\Framework\Core\Helpers;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class CompatibleFetcher
{
    /** For compatibility with every title/mode/script */
    const COMPATIBLE_ALL = "ALL";

    /**
     * Constant used for unknown titles.
     */
    const GAME_UNKNOWN = 'unknown';

    /** @var AssociativeArray  */
    protected $titles;

    /**
     * TitleGameConversion constructor.
     *
     * @param $titles
     */
    public function __construct($titles)
    {
        $this->titles = new AssociativeArray($titles);
        // TODO call expansion api to complete the mapping.
    }

    /**
     * Get a compatible data.
     *
     * @param $haystack
     * @param $title
     * @param $mode
     * @param $script
     *
     * @return mixed|null
     */
    public function getCompatibleData($haystack, $title, $mode, $script)
    {
        // List of choices order by importance.
        $choices = $this->getChoicesByPriority($title, $mode, $script);

        foreach ($choices as $choice) {
            $data = AssociativeArray::getFromKey($haystack, $choice);

            if (!is_null($data)) {
                return $data;
            }
        }

        return null;
    }

    /**
     * Get list of choices to test by priority.
     *
     * @param string $titleId
     * @param string $mode
     * @param string $script
     *
     * @return array
     */
    public function getChoicesByPriority($titleId, $mode, $script)
    {
        $game = $this->getTitleGame($titleId);
        return [
            [$titleId, $mode, $script],
            [$titleId, $mode, self::COMPATIBLE_ALL],
            [$titleId, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],

            // If perfect title is not found then fallback on game.
            [$game, $mode, $script],
            [$game, $mode, self::COMPATIBLE_ALL],
            [$game, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],

            // For modes that are common to all titles.
            [self::COMPATIBLE_ALL, $mode, $script],
            [self::COMPATIBLE_ALL, $mode, self::COMPATIBLE_ALL],

            // For data providers compatible with every title/gamemode/script.
            [self::COMPATIBLE_ALL, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
        ];
    }

    /**
     * Get the game of the title.
     *
     * @param string $titleId
     *
     * @return string
     */
    public function getTitleGame($titleId)
    {
        $game = $this->titles->get($titleId, self::GAME_UNKNOWN);
        return $game;
    }
}