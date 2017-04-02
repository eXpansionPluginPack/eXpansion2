<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 10:44
 */

namespace eXpansion\Framework\Core\Model\ChatCommand;


/**
 * Class AbstractChatCommand
 *
 * @package eXpansion\Framework\Core\Model\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
abstract class AbstractChatCommand implements ChatCommandInterface
{
    protected $command;

    protected $aliases = [];

    protected $parametersAsArray = true;

    /**
     * AbstractChatCommand constructor.
     *
     * @param $command
     * @param array $aliases
     * @param bool $parametersAsArray
     */
    public function __construct($command, array $aliases = [], $parametersAsArray = true)
    {
        $this->command = $command;
        $this->aliases = $aliases;
        $this->parametersAsArray = $parametersAsArray;
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
        return true;
    }

    /**
     * @inheritdoc
     */
    public function parseParameters($parameter) {
        if ($this->parametersAsArray) {
            return explode(' ', $parameter);
        } else {
            return $parameter;
        }
    }
}