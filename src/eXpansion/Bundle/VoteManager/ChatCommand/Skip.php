<?php


namespace eXpansion\Bundle\VoteManager\ChatCommand;

use eXpansion\Bundle\VoteManager\Plugins\VoteManager;
use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class Skip extends AbstractChatCommand
{
    /**
     * @var VoteManager
     */
    private $voteService;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;


    /**
     * MapsList constructor.
     *
     * @param                      $command
     * @param array $aliases
     * @param VoteService $voteService
     * @param PlayerStorage $playerStorage
     */
    public function __construct(
        $command,
        array $aliases = [],
        VoteService $voteService,
        PlayerStorage $playerStorage
    ) {
        parent::__construct($command, $aliases);
        $this->voteService = $voteService;
        $this->playerStorage = $playerStorage;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $player = $this->playerStorage->getPlayerInfo($login);
        $this->voteService->startVote($player, "Exp_NextMap");
    }
}
