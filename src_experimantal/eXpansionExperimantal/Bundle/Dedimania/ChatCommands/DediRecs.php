<?php


namespace eXpansionExperimantal\Bundle\Dedimania\ChatCommands;

use eXpansionExperimantal\Bundle\Dedimania\Plugins\Gui\DedirecsWindow;
use eXpansionExperimantal\Bundle\Dedimania\Services\DedimaniaService;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Notifications\Services\Notifications;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class DediRecs extends AbstractChatCommand
{
    /**
     * @var DedirecsWindow
     */
    private $dedirecsWindow;
    /**
     * @var DedimaniaService
     */
    private $dedimaniaService;
    /**
     * @var Notifications
     */
    private $notifications;

    /**
     * MapsList constructor.
     *
     * @param                      $command
     * @param array                $aliases
     * @param DedirecsWindow       $dedirecsWindow
     * @param DedimaniaService     $dedimaniaService
     * @param Notifications        $notifications
     */
    public function __construct(
        $command,
        array $aliases = [],
        DedirecsWindow $dedirecsWindow,
        DedimaniaService $dedimaniaService,
        Notifications $notifications
    ) {
        parent::__construct($command, $aliases);

        $this->dedirecsWindow = $dedirecsWindow;
        $this->dedimaniaService = $dedimaniaService;
        $this->notifications = $notifications;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $recs = $this->dedimaniaService->getDedimaniaRecords();

        if (empty($recs)) {
            $this->notifications->error("No dedimania records for this map", [], "Error", 5500, $login);
            return;
        }

        $this->dedirecsWindow->setRecords($recs);
        $this->dedirecsWindow->create($login);
    }
}
