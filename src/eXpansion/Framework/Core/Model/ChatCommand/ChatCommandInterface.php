<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 10:12
 */

namespace eXpansion\Framework\Core\Model\ChatCommand;


/**
 * Interface ChatCommandInterface
 *
 * @package eXpansion\Framework\Core\Model\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
Interface ChatCommandInterface
{
    public function getCommand();

    public function getAliases();

    public function validate($login, $parameter);

    public function parseParameters($parameter);

    public function execute($login, $parameter);
}