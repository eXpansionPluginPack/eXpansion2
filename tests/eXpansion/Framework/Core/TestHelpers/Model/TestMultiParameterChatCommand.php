<?php

namespace Tests\eXpansion\Framework\Core\TestHelpers\Model;

use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class TestChatCommand
 *
 * @package Tests\eXpansion\Framework\Core\TestHelpers\Model;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class TestMultiParameterChatCommand extends AbstractChatCommand
{
    /** @var null|InputInterface  */
    public $input = null;

    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('login', InputArgument::REQUIRED)
        );
        $this->inputDefinition->addArgument(
            new InputArgument('reason', InputArgument::REQUIRED)
        );
    }

    public function execute($login, InputInterface $input)
    {
        $this->input = $input;
    }
}