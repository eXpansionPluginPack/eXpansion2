<?php

namespace eXpansion\Bundle\VoteManager\Services\VoteFactories;
use eXpansion\Bundle\VoteManager\Structures\AbstractVote;
use eXpansion\Framework\Core\Storage\Data\Player;

/**
 * Class AbstractFactory
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\VoteManager\Services\VoteFactories
 */
abstract class AbstractFactory
{
    /** @var int  */
    protected $duration;

    /** @var float */
    protected $ration;

    /**
     * AbstractFactory constructor.
     *
     * @param int $duration
     * @param float $ration
     */
    public function __construct(int $duration, float $ration)
    {
        $this->duration = $duration;
        $this->ration = $ration;
    }

    /**
     * Create a new vote.
     *
     * @param Player $player Player that started the vote.
     *
     * @return AbstractVote
     */
    public abstract function create(Player $player) : AbstractVote;

    /**
     * Get the code of the vote type.
     *
     * @return string
     */
    public abstract function getVoteCode() : string;

    /**
     * Get types of MP votes it replaces.
     *
     * @return array
     */
    public abstract function getReplacementTypes();
}