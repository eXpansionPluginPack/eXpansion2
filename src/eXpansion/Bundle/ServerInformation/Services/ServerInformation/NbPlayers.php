<?php

namespace eXpansion\Bundle\ServerInformation\Services\ServerInformation;

use eXpansion\Bundle\ServerInformation\Ui\Factory\ServerInformationLineFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use FML\Controls\Control;

class NbPlayers extends AbstractServerInformation
{
    /** @var GameDataStorage */
    protected $dataStorage;

    /** @var PlayerStorage */
    protected $players;

    public function __construct(
        ServerInformationLineFactory $serverInformationLineFactory,
        AdminGroups $adminGroupHelper,
        GameDataStorage $dataStorage,
        PlayerStorage $players,
        $requiredPermission = null
    ) {
        parent::__construct($serverInformationLineFactory, $adminGroupHelper, $requiredPermission);

        $this->dataStorage = $dataStorage;
        $this->players = $players;
    }

    /**
     * @inheritdoc
     */
    public function getInformation(string $login): Control
    {
        $nbPlayer = count($this->players->getPlayers());
        $players = "$nbPlayer / {$this->dataStorage->getServerOptions()->currentMaxPlayers}";

        return $this->serverInformationLineFactory->create("expansion_server_information.nb_players", $players);
    }
}
