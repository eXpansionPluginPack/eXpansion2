<?php


namespace eXpansion\Core\Services;


use eXpansion\Core\Model\Gui\ManialinkInerface;
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
class GuiHandler
{
    /** @var  Connection */
    protected $connection;

    /** @var ManialinkInerface[][] */
    protected $displayQueu = [];
    protected $hideQueu = [];

    /**
     * GuiHandler constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function addToDisplay(ManialinkInerface $manialink)
    {
        $userGroup = $manialink->getUserGroup()->getName();

        if (AssociativeArray::getFromKey($this->hideQueu, [$userGroup, $manialink->getId()])) {
            unset($this->hideQueu[$userGroup][$manialink->getId()]);
        }

        $this->displayQueu[$userGroup][$manialink->getId()] = $manialink;
    }

    public function addToHide(ManialinkInerface $manialink)
    {
        $userGroup = $manialink->getUserGroup()->getName();

        if (AssociativeArray::getFromKey($this->displayQueu, [$userGroup, $manialink->getId()])) {
            unset($this->displayQueu[$userGroup][$manialink->getId()]);
        }

        $this->hideQueu[$userGroup][$manialink->getId()] = true;
    }

    public function displayManialinks()
    {
        foreach ($this->displayQueu as $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $this->connection->sendDisplayManialinkPage($manialink->getUserGroup()->getLogins(), $manialink->getXml());
            }
        }

        foreach ($this->hideQueu as $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $this->connection->sendHideManialinkPage($manialink->getUserGroup()->getLogins());
            }
        }
    }
}