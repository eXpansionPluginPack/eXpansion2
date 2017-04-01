<?php

namespace eXpansion\Core\Services;

use eXpansion\Core\DataProviders\Listener\ChatCommandInterface as ChatCommandPluginInterface;
use eXpansion\Core\Exceptions\ChatCommand\CommandExistException;
use eXpansion\Core\Model\ChatCommand\ChatCommandInterface;

/**
 * Class ChatCommands
 *
 * @package eXpansion\Core\Services;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class ChatCommands
{
    /** @var ChatCommandInterface[] */
    protected $commands;

    /** @var ChatCommandInterface[] */
    protected $commandPlugin;

    /**
     * Register chat commands of a plugin.
     *
     * @param string $pluginId
     * @param ChatCommandPluginInterface $pluginService
     *
     * @throws CommandExistException
     */
    public function registerPlugin($pluginId, ChatCommandPluginInterface $pluginService)
    {
        $commands = $pluginService->getChatCommands();

        foreach($commands as $command)
        {
            $this->addCommand($pluginId, $command->getCommand(), $command);

            foreach ($command->getAliases() as $alias) {
                $this->addCommand($pluginId, $alias, $command);
            }
        }
    }

    /**
     * Remove all chat commands registered for a plugin.
     *
     * @param $pluginId
     */
    public function deletePlugin($pluginId)
    {
        if(!isset($this->commandPlugin[$pluginId])) {
            return;
        }

        foreach ($this->commandPlugin[$pluginId] as $cmdTxt => $command) {
            unset($this->commands[$cmdTxt]);
        }
        unset($this->commandPlugin[$pluginId]);
    }

    /**
     * Get a chat command.
     *
     * @param string $command
     * @return ChatCommandInterface|null
     */
    public function getChatCommand($command)
    {
        return isset($this->commands[$command]) ? $this->commands[$command] : null;
    }

    /**
     *
     * @param string $pluginId
     * @param $cmdTxt
     * @param ChatCommandInterface $command
     *
     * @throws CommandExistException
     */
    protected function addCommand($pluginId, $cmdTxt, ChatCommandInterface $command)
    {
        if (isset($this->commands[$cmdTxt])) {
            throw new CommandExistException(
                "$pluginId tries to register command '$cmdTxt' already registered"
            );
        }

        $this->commands[$cmdTxt] = $command;
        $this->commandPlugin[$pluginId][$cmdTxt]  = $command;
    }
}