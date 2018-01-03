<?php

namespace eXpansion\Framework\Core\Model\ChatCommand;

use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Helpers\ChatOutput;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Helper\DescriptorHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;


/**
 * Class AbstractChatCommand
 *
 * @package eXpansion\Framework\Core\Model\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
abstract class AbstractChatCommand implements ChatCommandInterface
{
    /** @var string */
    protected $command;

    /** @var string[] */
    protected $aliases = [];

    /** @var InputDefinition  */
    protected $inputDefinition;

    /**
     * AbstractChatCommand constructor.
     *
     * @param $command
     * @param array $aliases
     */
    public function __construct($command, array $aliases = [])
    {
        $this->command = $command;
        $this->aliases = $aliases;

        $this->inputDefinition = new InputDefinition();

        $this->configure();
    }

    /**
     * Configure input definition.
     */
    protected function configure()
    {
        // Overwrite to add input definition.
    }

    /**
     * @inheritdoc
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @inheritdoc
     */
    public function validate($login, $parameter)
    {
        return "";
    }

    public function getDescription()
    {
        return 'expansion_core.chat_commands.no_description';
    }

    public function getHelp()
    {
        return 'expansion_core.chat_commands.no_help';
    }

    /**
     * @inheritdoc
     */
    public function run($login, ChatOutput $output, $parameter)
    {
        try {
            $parameter = str_getcsv($parameter, " ", '"');
            $parameter = array_merge([0 => 1], $parameter);

            $input = new ArgvInput($parameter, $this->inputDefinition);

            if (true === $input->hasParameterOption(array('--help', '-h'), true)) {
                $helper = new DescriptorHelper();
                $output->getChatNotification()->sendMessage($this->getDescription(), $login);
                $helper->describe($output, $this->inputDefinition);
                return '';
            }

            $input->validate();
        } catch (RuntimeException $runtimeException) {
            // These exceptions are thrown by symfony when arguments passed are not correct.
            throw new PlayerException($runtimeException->getMessage(), $runtimeException->getCode(), $runtimeException);
        }

        $this->execute($login, $input);
    }

    abstract public function execute($login, InputInterface $input);
}
