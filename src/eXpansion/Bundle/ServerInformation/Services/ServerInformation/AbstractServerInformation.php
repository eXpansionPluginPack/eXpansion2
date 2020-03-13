<?php

namespace eXpansion\Bundle\ServerInformation\Services\ServerInformation;

use eXpansion\Bundle\ServerInformation\Services\ServerInformationInterface;
use eXpansion\Bundle\ServerInformation\Ui\Factory\ServerInformationLineFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;

abstract class AbstractServerInformation implements ServerInformationInterface
{
    /** @var ServerInformationLineFactory */
    protected $serverInformationLineFactory;

    /** @var AdminGroups */
    protected $adminGroupHelper;

    /** @var string|null */
    protected $requiredPermission;

    /**
     * AbstractServerInformation constructor.
     *
     * @param ServerInformationLineFactory\ $serverInformationLineFactory
     * @param AdminGroups $adminGroupHelper
     * @param string|null $requiredPermission
     */
    public function __construct(
        ServerInformationLineFactory $serverInformationLineFactory,
        AdminGroups $adminGroupHelper,
        $requiredPermission = null
    ) {
        $this->serverInformationLineFactory = $serverInformationLineFactory;
        $this->adminGroupHelper = $adminGroupHelper;
        $this->requiredPermission = $requiredPermission;
    }

    /**
     * @inheritdoc
     */
    public function canShow(string $login): bool
    {
        if (is_null($this->requiredPermission)) {
            return true;
        }

        return $this->adminGroupHelper->hasPermission($login, $this->requiredPermission);
    }
}
