<?php

namespace eXpansion\Core\Plugins\Gui;
use eXpansion\Core\Model\Gui\Manialink;
use eXpansion\Core\Model\Gui\Window;
use eXpansion\Core\Model\UserGroups\Group;

/**
 * Class ManialiveFactory allow the creation of manialinks.
 *
 * @package eXpansion\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class WindowFactory extends ManialinkFactory {

    protected function createManialink(Group $group)
    {
        /** @var Window $manialink */
        $manialink = parent::createManialink($group);
        $actionId = $this->actionFactory->createManialinkAction(
            $manialink,
            [$this, 'closeManialink'],
            ['manialink' => $manialink]
        );

        $manialink->setCloseAction($actionId);

        return $manialink;
    }

    public function closeManialink($login, $answerValues, $arguments)
    {
        /** @var Manialink $manialink */
        $manialink = $arguments['manialink'];
        $this->destroy($manialink->getUserGroup());
    }
}
