<?php


namespace eXpansion\Framework\Core\ChatCommand;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class StopExpansion
 *
 * @package eXpansion\Framework\Core\ChatCommand;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class StopExpansion extends AbstractAdminChatCommand
{
    /** @var ChatNotification */
    protected $chatNotificaiton;

    /** @var Application */
    protected $application;

    /** @var PlayerStorage */
    protected $playerStorage;

    /**
     * StopExpansion constructor.
     *
     * @param ChatNotification $chatNotification
     * @param Application      $application
     * @param PlayerStorage    $playerStorage
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        AdminGroups $adminGroups,
        ChatNotification $chatNotification,
        Application $application,
        PlayerStorage $playerStorage
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);

        $this->chatNotificaiton = $chatNotification;
        $this->application = $application;
        $this->playerStorage = $playerStorage;
    }

    public function getDescription()
    {
        return "expansion_core.chat_commands.stop.help";
    }

    public function getHelp()
    {
        return "expansion_core.chat_commands.stop.help";
    }


    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $player = $this->playerStorage->getPlayerInfo($login);
        $this->chatNotificaiton->sendMessage(
            'expansion_core.chat_commands.stop.message',
            null,
            ['%nickname%' => $player->getNickName()]
        );

        $this->application->stopApplication();
    }
}
