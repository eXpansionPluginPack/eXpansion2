<?php

namespace eXpansion\Bundle\ServerInformation\Plugins\Gui;

use eXpansion\Bundle\ServerInformation\Services\ServerInformationInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;

class ServerInfoWindow extends WindowFactory
{
    /** @var ServerInformationInterface[] */
    protected $serverInfos;

    /** @var Factory */
    protected $factory;

    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        $serverInfos = [],
        Factory $factory
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->serverInfos = $serverInfos;
        $this->factory = $factory;
    }

    /**
     * @param ManialinkInterface $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);

        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getContentFrame();
        $contentFrame->removeAllChildren();

        $login = $manialink->getUserGroup()->getLogins()[0];

        $elements = [];
        foreach ($this->serverInfos as $serverInfo) {
            if ($serverInfo->canShow($login)) {
                $elements[] = $serverInfo->getInformation($login);
            }
        }

        $contentFrame->addChild($this->factory->createLayoutRow(0, 0, $elements));
    }


}