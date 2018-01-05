<?php

namespace eXpansion\Bundle\MxKarma\DataProviders\Listeners;

use eXpansion\Bundle\MxKarma\Entity\MxRating;
use eXpansion\Bundle\MxKarma\Entity\MxVote;

interface ListenerInterfaceMxKarma
{

    /**
     *
     */
    public function onMxKarmaConnect();

    /**
     * @param MxRating $mxRating
     * @return void
     */
    public function onMxKarmaVoteLoad(MxRating $mxRating);

    /**
     * @param MxVote[] $updatedVotes
     * @return void
     */
    public function onMxKarmaVoteSave($updatedVotes);

    public function onMxKarmaDisconnect();

}
