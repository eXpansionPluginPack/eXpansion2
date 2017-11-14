<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use Doctrine\Bundle\DoctrineBundle\ManagerConfigurator;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyManialink;
use eXpansion\Framework\Core\Model\Gui\Action;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;

/**
 * Class ActionFactory Handles available Gui Actions.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class ActionFactory implements ListenerInterfaceMpLegacyManialink
{
    protected $class;

    /** @var  Action[] */
    protected $actions = [];

    /** @var Action[][] */
    protected $actionsByManialink = [];

    /** @var ManialinkInterface[] */
    protected $manialinkByAction = [];

    /**
     * ActionFactory constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }


    /**
     * Create a Manialink action for a manialink.
     *
     * @param ManialinkInterface $manialink
     * @param $callable
     * @param array $args
     *
     * @return string action Id
     */
    public function createManialinkAction(ManialinkInterface $manialink, $callable, $args)
    {
        $class = $this->class;
        $action = new $class($callable, $args);
        $this->actions[$action->getId()] = $action;
        $this->actionsByManialink[$manialink->getId()][$action->getId()] = $action;
        $this->manialinkByAction[$action->getId()] = $manialink;

        return $action->getId();
    }

    /**
     * Destroy all actions for a manialink.
     *
     * @param ManialinkInterface $manialink
     */
    public function destroyManialinkActions(ManialinkInterface $manialink)
    {
        if (isset($this->actionsByManialink[$manialink->getId()])) {
            foreach ($this->actionsByManialink[$manialink->getId()] as $actionId => $action) {
                unset($this->actions[$actionId]);
                unset($this->manialinkByAction[$actionId]);
            }

            unset($this->actionsByManialink[$manialink->getId()]);
        }
    }

    /**
     * Destroy an individual action.
     *
     * @param $actionId
     */
    public function destroyAction($actionId)
    {
        if (isset($this->manialinkByAction[$actionId])) {
            unset($this->actionsByManialink[$this->manialinkByAction[$actionId]->getId()][$actionId]);
            unset($this->actions[$actionId]);
            unset($this->manialinkByAction[$actionId]);

        }
    }

    /**
     * When a player uses an action dispatch information.
     *
     * @param $login
     * @param $actionId
     * @param array $entryValues
     *
     */
    public function onPlayerManialinkPageAnswer($login, $actionId, array $entryValues)
    {
        if (isset($this->actions[$actionId])) {
            $this->actions[$actionId]->execute($this->manialinkByAction[$actionId], $login, $entryValues);
        }
    }
}
