<?php

namespace eXpansion\Bundle\ServerInformation\Services\ServerInformation;

use eXpansion\Bundle\ServerInformation\Ui\Factory\ServerInformationLineFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use FML\Controls\Control;

class ServerName extends AbstractServerInformation
{
    /** @var GameDataStorage */
    protected $dataStorage;

    public function __construct(
        ServerInformationLineFactory $serverInformationLineFactory,
        AdminGroups $adminGroupHelper,
        GameDataStorage $dataStorage,
        string $requiredPermission = null)
    {
        parent::__construct($serverInformationLineFactory, $adminGroupHelper, $requiredPermission);

        $this->dataStorage = $dataStorage;
    }


    /**
     * @inheritdoc
     */
    public function getInformation(string $login): Control
    {
        return $this->serverInformationLineFactory->create("expansion_server_information.server_name", $this->dataStorage->getServerOptions()->name);
    }
}
