<?php

namespace eXpansion\Core\Plugins;

use eXpansion\Core\DataProviders\Listener\TimerDataListenerInterface;
use eXpansion\Core\DataProviders\Listener\UserGroupDataListenerInterface;
use eXpansion\Core\Model\Gui\ManialinkInerface;
use eXpansion\Core\Model\UserGroups\Group;
use Maniaplanet\DedicatedServer\Connection;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class GuiHandler
 *
 * @TODO handle better update for a manialink for a single player.
 *
 * @package eXpansion\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class GuiHandler implements TimerDataListenerInterface, UserGroupDataListenerInterface
{
    /** @var  Connection */
    protected $connection;

    /** @var ManialinkInerface[][] */
    protected $displayQueu = [];

    /** @var ManialinkInerface[][] */
    protected $individualQueu = [];

    /** @var ManialinkInerface[][] */
    protected $displayeds = [];

    /** @var ManialinkInerface[][] */
    protected $hideQueu = [];

    /** @var String[][] */
    protected $hideIndividualQueu = [];

    /**
     * GuiHandler constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Add a manialink to the diplay queue.
     *
     * @param ManialinkInerface $manialink
     */
    public function addToDisplay(ManialinkInerface $manialink)
    {
        $userGroup = $manialink->getUserGroup()->getName();

        if (AssociativeArray::getFromKey($this->hideQueu, [$userGroup, $manialink->getId()])) {
            unset($this->hideQueu[$userGroup][$manialink->getId()]);
        }

        $this->displayQueu[$userGroup][$manialink->getId()] = $manialink;
    }

    /**
     * Add a manialink to the destruction queue.
     *
     * @param ManialinkInerface $manialink
     */
    public function addToHide(ManialinkInerface $manialink)
    {
        $userGroup = $manialink->getUserGroup()->getName();

        if (AssociativeArray::getFromKey($this->displayQueu, [$userGroup, $manialink->getId()])) {
            unset($this->displayQueu[$userGroup][$manialink->getId()]);
        }

        if (AssociativeArray::getFromKey($this->displayeds, [$userGroup, $manialink->getId()])) {
            unset($this->displayeds[$userGroup][$manialink->getId()]);
        }

        $this->hideQueu[$userGroup][$manialink->getId()] = $manialink;
    }

    /**
     * Display & hide all manialinks.
     */
    protected function displayManialinks()
    {
        // TODO Use multi calls.
        foreach ($this->displayQueu as $groupName => $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $logins = $manialink->getUserGroup()->getLogins();
                if (!empty($logins)) {
                    $this->connection->sendDisplayManialinkPage($logins, $manialink->getXml());
                }
                $this->displayeds[$groupName][$id] = $manialink;
            }
        }

        foreach ($this->individualQueu as $login => $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $xml = $manialink->getXml();
                $this->connection->sendDisplayManialinkPage($login, $xml);
            }
        }

        foreach ($this->hideQueu as $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $logins = $manialink->getUserGroup()->getLogins();
                if (!empty($logins)) {
                    $this->connection->sendDisplayManialinkPage($logins, '<manialink id="' . $id . '" />');
                }
            }
        }

        foreach ($this->hideIndividualQueu as $login => $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $this->connection->sendDisplayManialinkPage($login, '<manialink id="' . $id . '" />');
            }
        }


        // Reset the queues.
        $this->displayQueu = [];
        $this->individualQueu = [];
        $this->hideQueu = [];
        $this->hideIndividualQueu = [];
    }

    /**
     * @inheritdoc
     */
    public function onPostLoop()
    {
        $this->displayManialinks();
    }

    /**
     * @inheritdoc
     */
    public function onPreLoop()
    {
    }

    /**
     * @inheritdoc
     */
    public function onEverySecond()
    {
    }

    /**
     * @inheritdoc
     */
    public function onExpansionGroupAddUser(Group $group, $loginAdded)
    {
        $group = $group->getName();

        // User was added to group, need to display all manialinks of the group to this user
        if (isset($this->displayeds[$group])) {
            foreach ($this->displayeds[$group] as $mlId => $manialink) {
                $this->individualQueu[$loginAdded][$mlId] = $manialink;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved)
    {
        $group = $group->getName();

        // User was added to group, need to hide all manialinks of the group to this user
        if (isset($this->displayeds[$group])) {
            foreach ($this->displayeds[$group] as $mlId => $manialink) {
                $this->hideIndividualQueu[$loginRemoved][$mlId] = $manialink;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function onExpansionGroupDestroy(Group $group, $lastLogin)
    {
       if (isset($this->displayeds[$group->getName()])) {
           unset($this->displayeds[$group->getName()]);
       }
    }

    /**
     * List of all manialinks that are currentyl displayed.
     *
     * @return \eXpansion\Core\Model\Gui\ManialinkInerface[][]
     */
    public function getDisplayeds()
    {
        return $this->displayeds;
    }
}
