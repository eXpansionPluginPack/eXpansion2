<?php

namespace eXpansion\Framework\Core\Model\ChatCommand;

use eXpansion\Framework\Core\Helpers\ChatOutput;

/**
 * Interface ChatCommandInterface
 *
 * @package eXpansion\Framework\Core\Model\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
Interface ChatCommandInterface
{
    /**
     * @return string
     */
    public function getCommand();

    /**
     * @return string[]
     */
    public function getAliases();

    /**
     * @param $login
     * @param $parameter
     *
     * @return string Empty string if there are no validaton errors.
     */
    public function validate($login, $parameter);

    /**
     * @param string $login
     * @param ChatOutput $output
     * @param string $parameter
     *
     * @return mixed
     */
    public function run($login, ChatOutput $output, $parameter);
}