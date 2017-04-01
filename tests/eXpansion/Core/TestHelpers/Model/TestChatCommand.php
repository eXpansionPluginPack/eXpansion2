<?php

namespace Tests\eXpansion\Core\TestHelpers\Model;

use eXpansion\Core\Model\ChatCommand\AbstractChatCommand;


/**
 * Class TestChatCommand
 *
 * @package Tests\eXpansion\Core\TestHelpers\Model;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class TestChatCommand extends AbstractChatCommand
{

    public function execute($login, $parameter)
    {
         // nothing to do.
    }
}