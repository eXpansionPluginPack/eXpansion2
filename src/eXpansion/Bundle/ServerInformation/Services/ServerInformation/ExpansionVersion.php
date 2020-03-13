<?php

namespace eXpansion\Bundle\ServerInformation\Services\ServerInformation;

use eXpansion\Bundle\ServerInformation\Ui\Factory\ServerInformationLineFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\Version;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use FML\Controls\Control;

class ExpansionVersion extends AbstractServerInformation
{
    /** @var Version */
    protected $version;

    public function __construct(
        ServerInformationLineFactory $serverInformationLineFactory,
        AdminGroups $adminGroupHelper,
        Version $version,
        $requiredPermission = null
    ) {
        parent::__construct($serverInformationLineFactory, $adminGroupHelper, $requiredPermission);

        $this->version = $version;
    }

    /**
     * @inheritdoc
     */
    public function getInformation(string $login): Control
    {
        return $this->serverInformationLineFactory->create("expansion_server_information.exp_version", $this->version->getExpansionVersion());
    }
}
