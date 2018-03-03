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
    private $all_players;

    public function __construct(
        $name,
        array $variables,
        float $maxUpdateFrequency = 0.25,
        WidgetFactoryContext $context,
        VoteService $voteService,
        Group $all_players
    ) {
        parent::__construct($name, $variables, $maxUpdateFrequency, $context);
        $this->all_players = $all_players;
    }

    /** @param Vote $vote */
    public function updateVote($vote)
    {

        $out = [
            "yes" => $vote->getYes(),
            "no" => $vote->getNo(),
        ];

        $this->updateValue($this->all_players, "VoteUpdater", Builder::getArray($out, true));
    }


}
