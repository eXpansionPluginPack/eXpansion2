<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 10:44
 */

namespace eXpansion\Framework\Core\Model\ChatCommand;
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

    /** @var InputDefinition  */
    private $baseDefinition;

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
        $this->baseDefinition = new InputDefinition();

        // Allow help command.
        $this->baseDefinition->addOption(new InputOption('help', 'h', InputOption::VALUE_NONE,'get help for this command.'));

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

    /**
     * @inheritdoc
     */
    public function run($login, $parameter)
    {
        $parameter = str_getcsv($parameter, " ", '"');
        $parameter = array_merge([0 => 1], $parameter);

        $input = new ArgvInput($parameter, $this->baseDefinition);

        if ($input->getOption('help')) {
            // TODO show help
            return "Help message should be here";
        }

        $input->bind($this->inputDefinition);
        $input->validate();

        $this->execute($login, $input);
    }

    abstract public function execute($login, InputInterface $input);
}