<?php

namespace eXpansion\Bundle\ServerInformation\Services\ServerInformation;

use eXpansion\Bundle\ServerInformation\Ui\Factory\ServerInformationLineFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use FML\Controls\Control;

class ServerDescription extends ServerName
{
    /**
     * @inheritdoc
     */
    public function getInformation(string $login): Control
    {
        return $this->serverInformationLineFactory->create(
            "expansion_server_information.server_description",
            explode("\n", $this->dataStorage->getServerOptions()->comment)
        );
    }
}
