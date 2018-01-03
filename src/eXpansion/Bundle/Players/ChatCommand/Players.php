<?php


namespace eXpansion\Bundle\Players\ChatCommand;

use eXpansion\Bundle\Players\Plugins\Gui\PlayersWindow;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Players
 *
 * @package eXpansion\Bundle\Players\ChatCommand;
 * @author  reaby
 */
class Players extends AbstractChatCommand
{
    private $playersWindowFactory;

    /**
     * Records constructor.
     *
     * @param               $command
     * @param array         $aliases
     * @param PlayersWindow $recordsWindowFactory
     */
    public function __construct(
        $command,
        array $aliases = [],
        PlayersWindow $recordsWindowFactory
    ) {
        parent::__construct($command, $aliases);

        $this->playersWindowFactory = $recordsWindowFactory;

    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $this->playersWindowFactory->create($login);
    }
}
