<?php

namespace eXpansion\Framework\Core\Model\ChatCommand;


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
     * @param $login
     * @param $parameter
     *
     * @return mixed
     */
    public function run($login, $parameter);
}