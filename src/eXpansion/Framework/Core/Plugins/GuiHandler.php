<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpUserGroup;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use Maniaplanet\DedicatedServer\Connection;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use Psr\Log\LoggerInterface;

/**
 * Class GuiHandler will send manialinks to player as needed.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class GuiHandler implements
    ListenerInterfaceExpTimer,
    ListenerInterfaceExpUserGroup,
    ListenerInterfaceMpLegacyPlayer,
    StatusAwarePluginInterface,
    GuiHandlerInterface
{
    /** @var Factory */
    protected $factory;

    /** @var LoggerInterface */
    protected $logger;

    /** @var Console */
    protected $console;

    /** @var ActionFactory */
    protected $actionFactory;

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

    /** @var ManialinkInterface[][] */
    protected $hideIndividualQueu = [];

    /** @var String[] */
    protected $disconnectedLogins = [];

    /**
     * GuiHandler constructor.
     *
     * @param Factory $factory
     * @param LoggerInterface $logger
     * @param Console $console
     * @param ActionFactory $actionFactory
     * @param int $charLimit
     */
    public function __construct(
        Factory $factory,
        LoggerInterface $logger,
        Console $console,
        ActionFactory $actionFactory,
        $charLimit = 262144
    ) {
        $this->factory = $factory;
        $this->logger = $logger;
        $this->console = $console;
        $this->actionFactory = $actionFactory;
        $this->charLimit = $charLimit;
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->factory->getConnection()->sendHideManialinkPage(null);
        }
    }


    /**
     * @inheritdoc
     **/
    public function addToDisplay(ManialinkInterface $manialink)
    {

        $userGroup = $manialink->getUserGroup()->getName();
        $id = $manialink->getManialinkFactory()->getId();

        if (AssociativeArray::getFromKey($this->hideQueu, [$userGroup, $id])) {
            unset($this->hideQueu[$userGroup][$id]);
        }

        $this->displayQueu[$userGroup][$id] = $manialink;
    }

    /**
     * @inheritdoc
     */
    public function addToHide(ManialinkInterface $manialink)
    {
        $userGroup = $manialink->getUserGroup()->getName();
        $id = $manialink->getManialinkFactory()->getId();

        if (AssociativeArray::getFromKey($this->displayQueu, [$userGroup, $id])) {
            unset($this->displayQueu[$userGroup][$id]);
        }

        if (AssociativeArray::getFromKey($this->displayeds, [$userGroup, $id])) {
            unset($this->displayeds[$userGroup][$id]);
        }

        $this->actionFactory->destroyManialinkActions($manialink);
        $this->hideQueu[$userGroup][$id] = $manialink;
    }

    /**
     * @inheritdoc
     */
    public function getManialink(Group $group, ManialinkFactoryInterface $manialinkFactory)
    {
        $varsToCheck = ['displayeds', 'hideQueu', 'displayQueu'];

        foreach ($varsToCheck as $var) {
            if (isset($this->$var[$group->getName()]) && isset($this->$var[$group->getName()][$manialinkFactory->getId()])) {
                return $this->$var[$group->getName()][$manialinkFactory->getId()];
            }
        }

        return null;
    }

    /**
     * Display & hide all manialinks.
     * @throws \Maniaplanet\DedicatedServer\InvalidArgumentException
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

            $logins = array_filter($mlData['logins'], function ($value) {
                return $value != '';
            });

            if (!empty($logins)) {
                $this->factory->getConnection()->sendDisplayManialinkPage(
                    $mlData['logins'],
                    $mlData['ml'],
                    $mlData['timeout'],
                    false,
                    true
                );
            }
        }

        if ($size > 0) {
            $this->executeMultiCall();
        }

        // Reset the queues.
        $this->displayQueu = [];
        $this->individualQueu = [];
        $this->hideQueu = [];
        $this->hideIndividualQueu = [];
        $this->disconnectedLogins = [];
    }

    /**
     * Execute multi call & handle error.
     */
    protected function executeMultiCall()
    {
        try {
            $this->factory->getConnection()->executeMulticall();
        } catch (\Exception $e) {
            $this->logger->error("Couldn't deliver all manialinks : ".$e->getMessage(), ['exception' => $e]);
            $this->console->writeln('$F00ERROR - Couldn\'t deliver all manialinks : '.$e->getMessage());
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
            foreach ($manialinks as $factoryId => $manialink) {
                $logins = $manialink->getUserGroup()->getLogins();

                $this->displayeds[$groupName][$factoryId] = $manialink;
                if (!empty($logins)) {
                    yield ['logins' => $logins, 'ml' => $manialink->getXml(), "timeout" => $manialink->getTimeout()];
                }
            }
        }

        foreach ($this->individualQueu as $manialinks) {
            // Fetch all logins
            $logins = [];
            $lastManialink = null;
            foreach ($manialinks as $login => $manialink) {
                $logins[] = $login;
                $lastManialink = $manialink;
            }

            if ($lastManialink) {
                $xml = $manialink->getXml();
                yield ['logins' => $logins, 'ml' => $xml, "timeout" => $manialink->getTimeout()];
            }
        }

        foreach ($this->hideQueu as $manialinks) {
            foreach ($manialinks as $manialink) {
                $id = $manialink->getId();
                $manialink->destroy();

                $logins = $manialink->getUserGroup()->getLogins();
                $logins = array_diff($logins, $this->disconnectedLogins);

                if (!empty($logins)) {
                    yield ['logins' => $logins, 'ml' => '<manialink id="'.$id.'" />', "timeout" => 0];
                }
            }
        }

        foreach ($this->hideIndividualQueu as $id => $manialinks) {
            // Fetch all logins.
            $logins = [];
            $lastManialink = null;
            foreach ($manialinks as $login => $manialink) {
                if (!in_array($login, $this->disconnectedLogins)) {
                    $logins[] = $login;
                    $lastManialink = $manialink;
                }
            }

            if ($lastManialink) {
                // Manialink is not destroyed just not shown at a particular user that left the group.
                yield ['logins' => $logins, 'ml' => '<manialink id="'.$lastManialink->getId().'" />', "timeout" => 0];
            }
        }
    }

    /**
     * @param int $charLimit
     */
    public function setCharLimit($charLimit)
    {
        $this->charLimit = $charLimit;
    }

    /**
     * List of all manialinks that are currently displayed.
     *
     * @return ManialinkInterface[][]
     */
    public function getDisplayeds()
    {
        return $this->displayeds;
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
                $this->individualQueu[$mlId][$loginAdded] = $manialink;

                if (isset($this->hideIndividualQueu[$mlId]) && isset($this->hideIndividualQueu[$mlId][$loginAdded])) {
                    unset ($this->hideIndividualQueu[$mlId][$loginAdded]);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved)
    {
        $group = $group->getName();

        // User was removed from group, need to hide all manialinks of the group to this user
        if (isset($this->displayeds[$group])) {
            foreach ($this->displayeds[$group] as $mlId => $manialink) {
                $this->hideIndividualQueu[$mlId][$loginRemoved] = $manialink;

                if (isset($this->individualQueu[$mlId]) && isset($this->individualQueu[$mlId][$loginRemoved])) {
                    unset ($this->individualQueu[$mlId][$loginRemoved]);
                }
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
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // To prevent sending manialinks to those players.
        $this->disconnectedLogins[] = $player->getLogin();
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
    }
}
