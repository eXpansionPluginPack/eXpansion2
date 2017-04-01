<?php

namespace eXpansion\Core\Model\Helpers;

/**
 * interface ChatNotificationInterface
 *
 * @package eXpansion\Core\Model\Helpers;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
interface ChatNotificationInterface
{
    public function sendMessage($messageId, $to = null, $parameters = []);

}