<?php

namespace eXpansion\Bundle\Menu\Model\Menu;

use eXpansion\Framework\Core\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use FML\Controls\Quad;

/**
 * Class ChatCommandItem
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
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
     * @param $chatCommand
     * @param string $id
     * @param string $path
     * @param null|string $labelId
     * @param Quad $icon
     * @param null $permission
     */
    public function __construct(
        ChatCommandDataProvider $chatCommandDataProvider,
        $chatCommand,
        $id,
        $path,
        $labelId,
        Quad $icon,
        $permission = null
    ) {
        $this->chatCommand = $chatCommand;
        $this->chatCommandProvider = $chatCommandDataProvider;

        parent::__construct($id, $path, $labelId, $icon, $permission);
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
        $this->chatCommandProvider->onPlayerChat(
            $login,
            $login,
            $this->chatCommand,
            false
        );
        $manialinkFactory->destroy($login);
    }
}
