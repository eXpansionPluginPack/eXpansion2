<?php

namespace eXpansion\Bundle\ServerInformation\Services\ServerInformation;

use eXpansion\Bundle\ServerInformation\Ui\Factory\ServerInformationLineFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use FML\Controls\Control;

class NbSpectators extends NbPlayers
{
    /**
     * @inheritdoc
     */
    public function getInformation(string $login): Control
    {
        $nbPlayer = count($this->players->getSpectators());
        $players = "$nbPlayer / {$this->dataStorage->getServerOptions()->currentMaxSpectators}";

        return $this->serverInformationLineFactory->create("expansion_server_information.nb_spectators", $players);
    }
}
