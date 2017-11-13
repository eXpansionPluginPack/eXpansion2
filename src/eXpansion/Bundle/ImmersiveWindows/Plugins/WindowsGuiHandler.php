<?php

namespace eXpansion\Bundle\ImmersiveWindows\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\GuiHandlerInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;
use Psr\Log\LoggerInterface;

/**
 * Class WindowsGuiHandler, replaces the native GuiHandler only for windows type manialinks in order to :
 *  - Prevent more then 1 window to open.
 *  - Change the display
 *
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\Plugins
 */
class WindowsGuiHandler implements GuiHandlerInterface, ListenerInterfaceMpLegacyPlayer
{

    /** @var Window[] */
    protected $userWindows = [];

    /**
     * @var GuiHandler
     */
    protected $guiHandler;

    public function __construct(GuiHandler $guiHandler)
    {
        $this->guiHandler = $guiHandler;
    }

    /**
     * @inheritdoc
     */
    public function addToDisplay(ManialinkInterface $manialink)
    {
        $logins = $manialink->getUserGroup()->getLogins();
        if (count($logins) == 1 && !$manialink->getUserGroup()->isPersistent()) {
            $login = $logins[0];

            // If a window is already displayed hide it. We wish to have 1 window max.
            if (isset($this->userWindows[$login]) && $this->userWindows[$login]->getId() != $manialink->getId()) {
                $this->guiHandler->addToHide($this->userWindows[$login]);
            }

            $this->userWindows[$login] = $manialink;
        }

        $this->guiHandler->addToDisplay($manialink);
    }

    /**
     * @inheritdoc
     */
    public function addToHide(ManialinkInterface $manialink)
    {
        $logins = $manialink->getUserGroup()->getLogins();
        if (count($logins) == 1 && !$manialink->getUserGroup()->isPersistent()) {
            $login = $logins[0];

            if (isset($this->userWindows[$login])) {
                unset($this->userWindows[$login]);
            }
        }

        $this->guiHandler->addToHide($manialink);
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        // Nothing
    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        if (isset($this->userWindows[$player->getLogin()])) {
            unset($this->userWindows[$player->getLogin()]);
        }
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // Nothing
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // Nothing
    }
}
