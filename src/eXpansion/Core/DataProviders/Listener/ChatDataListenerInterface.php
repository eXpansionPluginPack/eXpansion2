<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 13:06
 */

namespace eXpansion\Core\DataProviders\Listener;

use eXpansion\Core\DataProviders\ChatDataProvider;
use eXpansion\Core\Storage\Data\Player;

/**
 * Interface ChatDataListenerInterface for plugins using the ChatDataProvider data provider. *
 * @see ChatDataProvider
 *
 * @package eXpansion\Core\DataProviders\Listener
 */
interface ChatDataListenerInterface
{
    /**
     * Called when a player chats.
     *
     * @param Player $player
     * @param $text
     *
     * @return void
     */
    public function onPlayerChat(Player $player, $text);
}