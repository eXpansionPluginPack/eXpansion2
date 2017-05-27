<?php

namespace eXpansion\Framework\Core\ChatCommand;

use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Plugins\Gui\WindowHelpFactory;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Help
 *
 * @package eXpansion\Framework\Core\ChatCommand;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Help extends AbstractChatCommand
{
    protected $windowHelpFactory;

    public function __construct($command, array $aliases = [], WindowHelpFactory $windowHelpFactory)
    {
        parent::__construct($command, $aliases);

        $this->windowHelpFactory = $windowHelpFactory;
    }

    public function execute($login, InputInterface $input)
    {
        $this->windowHelpFactory->create($login);
    }
}