<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ScriptVariableUpdateFactory;
use FML\Script\Builder;


class UpdateVoteWidgetFactory extends ScriptVariableUpdateFactory
{

    /**
     * @var Group
     */
    private $allPlayers;

    public function __construct(
        $name,
        array $variables,
        float $maxUpdateFrequency = 0.25,
        WidgetFactoryContext $context,
        VoteService $voteService,
        Group $allPlayers
    ) {
        parent::__construct($name, $variables, $maxUpdateFrequency, $context);
        $this->allPlayers = $allPlayers;
    }

    /**
     * Update votes
     *
     * @param Vote $vote
     */
    public function updateVote($vote)
    {

        $out = [
            "yes" => $vote->getYes(),
            "no" => $vote->getNo(),
        ];

        $this->updateValue($this->allPlayers, "VoteUpdater", Builder::getArray($out, true));
    }


}