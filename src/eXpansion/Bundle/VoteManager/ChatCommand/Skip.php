<?php

namespace eXpansion\Bundle\VoteManager\ChatCommand;

use eXpansion\Bundle\Maps\Plugins\Gui\MapsWindowFactory;
use eXpansion\Bundle\Maps\Plugins\Maps;
use eXpansion\Bundle\VoteManager\Plugins\VoteManager;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class Skip extends AbstractChatCommand
{
    /**
     * @var VoteManager
     */
    private $voteManager;


    /**
     * MapsList constructor.
     *
     * @param                      $command
     * @param array $aliases
     * @param VoteManager $voteManager
     */
    public function __construct(
        $command,
        array $aliases = [],
        VoteManager $voteManager
    )
    {
        parent::__construct($command, $aliases);

        $this->voteManager = $voteManager;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
       $this->voteManager->startVote("NextMap");
    }
}
