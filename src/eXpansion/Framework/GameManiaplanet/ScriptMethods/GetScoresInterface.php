<?php

namespace eXpansion\Framework\GameManiaplanet\ScriptMethods;

/**
 * Interface GetScoresInterface
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\GameManiaplanet\ScriptMethods
 */
interface GetScoresInterface
{
    /**
     * Get TM.Scores or SM.Scores.
     *
     * @param callback $function
     *
     * @return void
     */
    public function getScores($function);
}
