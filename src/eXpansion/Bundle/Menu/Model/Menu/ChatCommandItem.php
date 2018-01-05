<?php

namespace eXpansion\Bundle\Menu\Model\Menu;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;

/**
 * Class ChatCommandItem
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\Menu\Model\Menu
 */
class ChatCommandItem extends AbstractItem
{
    /** @var string */
    protected $chatCommand;

    /** @var ChatCommandDataProvider */
    protected $chatCommandProvider;

    /**
     * ChatCommandItem constructor.
     *
     * @param ChatCommandDataProvider $chatCommandDataProvider
     * @param                         $chatCommand
     * @param string                  $id
     * @param string                  $path
     * @param string                  $labelId
     * @param AdminGroups             $adminGroups
     * @param null                    $permission
     */
    public function __construct(
        ChatCommandDataProvider $chatCommandDataProvider,
        $chatCommand,
        $id,
        $path,
        $labelId,
        AdminGroups $adminGroups,
        $permission = null
    ) {
        parent::__construct($id, $path, $labelId, $adminGroups, $permission);
        $this->chatCommand = $chatCommand;
        $this->chatCommandProvider = $chatCommandDataProvider;
    }


    /**
     * @param ManialinkFactory $manialinkFactory
     * @param ManialinkInterface $manialink
     * @param $login
     * @param $answerValues
     * @param $args
     *
     * @return mixed
     */
    public function execute(ManialinkFactory $manialinkFactory, ManialinkInterface $manialink, $login, $answerValues, $args)
    {
        $manialinkFactory->destroy($manialink->getUserGroup());
        $this->chatCommandProvider->onPlayerChat(
            $login,
            $login,
            $this->chatCommand,
            true
        );
    }
}
