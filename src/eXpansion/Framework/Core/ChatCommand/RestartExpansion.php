<?php

namespace eXpansion\Framework\Core\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;


/**
 * Class RestartExpansion
 *
 * @package eXpansion\Framework\Core\ChatCommand;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RestartExpansion extends AbstractAdminChatCommand
{
    /** @var ChatNotification */
    protected $chatNotification;

    /** @var PlayerStorage */
    protected $playerStorage;

    /** @var GameDataStorage */
    protected $gameData;

    /** @var Application */
    protected $application;

    public function __construct(
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        GameDataStorage $gameData,
        Application $application,
        $command,
        string $permission,
        $aliases = [],
        AdminGroups $adminGroups
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
        $this->gameData = $gameData;
        $this->application = $application;
    }


    /**
     * Method called to execute the chat command.
     *
     * @param string         $login
     * @param InputInterface $input
     *
     * @return mixed
     */
    public function execute($login, InputInterface $input)
    {
        $scriptToExecute = 'run.sh';
        if ($this->gameData->getServerOs() == GameDataStorage::OS_WINDOWS) {
            $scriptToExecute = 'run.bat';
        }

        $player = $this->playerStorage->getPlayerInfo($login);
        $this->chatNotification->sendMessage(
            'expansion_core.chat_commands.restart.message',
            null,
            ['%nickname%' => $player->getNickName()]
        );
        $this->application->stopApplication();

        $process = new Process("bin/" . $scriptToExecute . " &");
        $process->start();
    }
}