<?php


namespace eXpansion\Bundle\Maps\ChatCommand;

use eXpansion\Bundle\Maps\Plugins\Gui\MapsWindowFactory;
use eXpansion\Bundle\Maps\Plugins\Jukebox as JukeboxPlugin;
use eXpansion\Bundle\Maps\Plugins\Maps;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Records
 *
 * @package eXpansion\Bundle\LocalRecords\ChatCommand;
 * @author  reaby
 */
class Jukebox extends AbstractChatCommand
{
    /** @var JukeboxPlugin */
    protected $jukeboxPlugin;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('action', InputArgument::REQUIRED, 'expansion_jukebox.chat.command.jukebox.description')
        );
    }

    public function getHelp()
    {
        return 'expansion_jukebox.chat.command.jukebox.help';
    }

    /**
     * MapsList constructor.
     *
     * @param $command
     * @param array $aliases
     * @param JukeboxPlugin $jukeboxPlugin
     */
    public function __construct(
        $command,
        array $aliases = [],
        JukeboxPlugin $jukeboxPlugin
    ) {
        parent::__construct($command, $aliases);

        $this->jukeboxPlugin = $jukeboxPlugin;
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $action = $input->getArgument('action');
        $this->jukeboxPlugin->jukeboxCommand($login, $action);
    }
}
