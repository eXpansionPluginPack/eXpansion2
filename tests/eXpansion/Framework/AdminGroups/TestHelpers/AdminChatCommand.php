<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 12:11
 */

namespace Tests\eXpansion\Framework\AdminGroups\TestHelpers;

use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class AdminChatCommand
 *
 * @package Tests\eXpansion\Framework\AdminGroups\TestHelpers;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class AdminChatCommand extends AbstractAdminChatCommand
{
    public $executed = false;

    public function execute($login, InputInterface $input)
    {
        $this->executed = true;
    }
}