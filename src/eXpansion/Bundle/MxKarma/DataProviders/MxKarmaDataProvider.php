<?php

namespace eXpansion\Bundle\MxKarma\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

class MxKarmaDataProvider extends AbstractDataProvider
{

    public function onMxKarmaConnect()
    {
        $this->dispatch('onMxKarmaConnect', []);
    }

    public function onMxKarmaVoteLoad($params)
    {
        $this->dispatch('onMxKarmaVoteLoad', [$params]);
    }

    public function onMxKarmaVoteSave($params)
    {
        $this->dispatch('onMxKarmaVoteSave', [$params]);
    }

    public function onMxKarmaDisconnect()
    {
        $this->dispatch('onMxKarmaDisconnect', []);
    }

}
