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
     * @return mixed
     */
    public function onMxKarmaVoteLoad(MxRating $mxRating);

    /**
     * @param MxVote[] $updatedVotes
     * @return mixed
     */
    public function onMxKarmaVoteSave($updatedVotes);

    public function onMxKarmaDisconnect();

}
