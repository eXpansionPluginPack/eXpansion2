<?php

namespace eXpansion\Bundle\ServerInformation\ChatCommand;

use eXpansion\Bundle\ServerInformation\Plugins\Gui\ServerInfoWindow;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputInterface;

class ServerInfo extends AbstractChatCommand
{
    /** @var ServerInfoWindow */
    protected $serverInformationWindow;

    /**
     * @inheritdoc
     */
    public function __construct(ServerInfoWindow $serverInformationWindow, $command, array $aliases = [])
    {
        parent::__construct($command, $aliases);

        $this->serverInformationWindow = $serverInformationWindow;
    }

    /**
     * Method called to execute the chat command.
     *
     * @param string $login
     * @param InputInterface $input
     *
     * @return mixed
     */
    public function execute($login, InputInterface $input)
    {
        $this->serverInformationWindow->create($login);
    }
}
