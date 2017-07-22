<?php


namespace eXpansion\Bundle\Maps\ChatCommand;

use eXpansion\Bundle\Maps\Plugins\Gui\MapsWindowFactory;
use eXpansion\Bundle\Maps\Plugins\Maps;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class MapsList extends AbstractChatCommand
{
    /** @var MapsWindowFactory */
    protected $mapsListWindowFactory;

    /** @var Maps */
    protected $mapsPlugin;

    /**
     * MapsList constructor.
     *
     * @param                      $command
     * @param array                $aliases
     */
    public function __construct(
        $command,
        array $aliases = [],
        MapsWindowFactory $mapsListWindowFactory,
        Maps $mapsPlugin
    )
    {
        parent::__construct($command, $aliases);

        $this->mapsListWindowFactory = $mapsListWindowFactory;
        $this->mapsPlugin = $mapsPlugin;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->mapsListWindowFactory->setMaps($this->mapsPlugin->getMaps());
        $this->mapsListWindowFactory->create($login);
    }
}
