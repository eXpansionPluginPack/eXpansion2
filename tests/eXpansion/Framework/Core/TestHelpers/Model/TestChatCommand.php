<?php

namespace Tests\eXpansion\Framework\Core\TestHelpers\Model;

use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class TestChatCommand
 *
 * @package Tests\eXpansion\Framework\Core\TestHelpers\Model;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class TestChatCommand extends AbstractChatCommand
{
    public $executed = false;

    public function execute($login, InputInterface $input)
    {
        $this->executed = true;
    }
}