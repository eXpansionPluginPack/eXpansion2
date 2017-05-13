<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\TimerDataListenerInterface;
use eXpansion\Framework\Core\DataProviders\Listener\UserGroupDataListenerInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Connection;
use Monolog\Logger;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class GuiHandler will send manialinks to player as needed.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class GuiHandler implements TimerDataListenerInterface, UserGroupDataListenerInterface
{
    /** @var Connection */
    protected $connection;

    /** @var Logger */
    protected $logger;

    /** @var Console */
    protected $console;

    /** @var int */
    protected $charLimit;

    /** @var ManialinkInterface[][] */
    protected $displayQueu = [];

    /** @var ManialinkInterface[][] */
    protected $individualQueu = [];

    /** @var ManialinkInterface[][] */
    protected $displayeds = [];

    /** @var ManialinkInterface[][] */
    protected $hideQueu = [];

    /** @var String[][] */
    protected $hideIndividualQueu = [];

    /**
     * GuiHandler constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection, Logger $logger, Console $console, $charLimit = 262144)
    {
        $this->connection = $connection;

        $this->connection->sendHideManialinkPage(null);

        $this->logger = $logger;
        $this->console = $console;
        $this->charLimit = $charLimit;
    }


    /**
     * Add a manialink to the diplay queue.
     *
     * @param ManialinkInterface $manialink
     */
    public function addToDisplay(ManialinkInterface $manialink)
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
     * @param ManialinkInterface $manialink
     */
    public function addToHide(ManialinkInterface $manialink)
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
        $size = 0;
        foreach ($this->getManialinksToDisplay() as $mlData) {
            $currentSize = $size;
            $size += strlen($mlData['ml']);

            if ($currentSize != 0 && $size > $this->charLimit) {
                $this->executeMultiCall();
                $size = strlen($mlData['ml']);
            }

            $this->connection->sendDisplayManialinkPage($mlData['logins'], $mlData['ml'], 0, false, true);
        }

        if ($size > 0) {
            $this->executeMultiCall();
        }

        // Reset the queues.
        $this->displayQueu = [];
        $this->individualQueu = [];
        $this->hideQueu = [];
        $this->hideIndividualQueu = [];
    }

    /**
     * Execute multicall & handle error.
     */
    protected function executeMultiCall()
    {
        try {
            $this->connection->executeMulticall();
        } catch (\Exception $e) {
            $this->logger->addError("Couldn't deliver all manialinks : " . $e->getMessage(), ['exception' => $e]);
            $this->console->writeln('$F00ERROR - Couldn\'t deliver all manialinks : ' . $e->getMessage());
        }
    }

    /**
     * Get list of all manialinks that needs to be displayed
     *
     * @return \Generator
     */
    protected function getManialinksToDisplay()
    {
        foreach ($this->displayQueu as $groupName => $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $logins = $manialink->getUserGroup()->getLogins();

                $this->displayeds[$groupName][$id] = $manialink;
                if (!empty($logins)) {
                    yield ['logins' => $logins, 'ml' => $manialink->getXml()];
                }
            }
        }

        foreach ($this->individualQueu as $login => $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $xml = $manialink->getXml();
                yield ['logins' => $login, 'ml' => $xml];
            }
        }

        foreach ($this->hideQueu as $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                $logins = $manialink->getUserGroup()->getLogins();
                if (!empty($logins)) {
                    yield ['logins' => $logins, 'ml' => '<manialink id="' . $id . '" />'];
                }
            }
        }

        foreach ($this->hideIndividualQueu as $login => $manialinks) {
            foreach ($manialinks as $id => $manialink) {
                yield ['logins' => $login, 'ml' => '<manialink id="' . $id . '" />'];
            }
        }
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
     * @return ManialinkInterface[]
     */
    public function getDisplayeds()
    {
        return $this->displayeds;
    }

    /**
     * @param int $charLimit
     */
    public function setCharLimit($charLimit)
    {
        $this->charLimit = $charLimit;
    }
}
