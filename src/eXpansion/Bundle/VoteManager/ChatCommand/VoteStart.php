<?php

namespace eXpansion\Bundle\VoteManager\ChatCommand;

use eXpansion\Bundle\VoteManager\Plugins\VoteManager;
use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class VoteStart extends AbstractChatCommand
{
    /** @var VoteService */
    private $voteService;

    /** @var PlayerStorage */
    private $playerStorage;

    /** @var string */
    protected $voteTypeCode;

    /**
     * VoteStart constructor.
     *
     * @param $command
     * @param array $aliases
     * @param VoteService $voteService
     * @param PlayerStorage $playerStorage
     * @param $voteTypeCode
     */
    public function __construct(
        $command,
        array $aliases = [],
        VoteService $voteService,
        PlayerStorage $playerStorage,
        $voteTypeCode
    ) {
        parent::__construct($command, $aliases);
        $this->voteService = $voteService;
        $this->playerStorage = $playerStorage;
        $this->voteTypeCode = $voteTypeCode;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $player = $this->playerStorage->getPlayerInfo($login);
        $this->voteService->startVote($player, $this->voteTypeCode, []);
    }
}
