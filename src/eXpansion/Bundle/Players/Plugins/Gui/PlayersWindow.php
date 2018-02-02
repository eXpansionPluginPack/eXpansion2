<?php

namespace eXpansion\Bundle\Players\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\Countries;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Layouts\layoutRow;
use FML\Controls\Frame;
use Maniaplanet\DedicatedServer\Connection;

class PlayersWindow extends GridWindowFactory
{
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var ChatCommandDataProvider
     */
    private $chatCommandDataProvider;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var AdminGroups
     */
    private $adminGroups;

    /** @var Countries */
    protected $countries;

    /** @var null|string */
    protected $playerToSet = null;

    /**
     * PlayersWindow constructor.
     * @param                         $name
     * @param                         $sizeX
     * @param                         $sizeY
     * @param null                    $posX
     * @param null                    $posY
     * @param WindowFactoryContext    $context
     * @param PlayerStorage           $playerStorage
     * @param DataCollectionFactory   $dataCollectionFactory
     * @param GridBuilderFactory      $gridBuilderFactory
     * @param ChatCommandDataProvider $chatCommandDataProvider
     * @param Connection              $connection
     * @param AdminGroups             $adminGroups
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        PlayerStorage $playerStorage,
        DataCollectionFactory $dataCollectionFactory,
        GridBuilderFactory $gridBuilderFactory,
        ChatCommandDataProvider $chatCommandDataProvider,
        Connection $connection,
        AdminGroups $adminGroups,
        Countries $countries
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->playerStorage = $playerStorage;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->chatCommandDataProvider = $chatCommandDataProvider;
        $this->connection = $connection;
        $this->adminGroups = $adminGroups;
        $this->countries = $countries;
    }

    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $manialink->setData('playerActions', []);
        $this->setPlayer($manialink, $manialink->getUserGroup()->getLogins()[0]);

        $recipient = $manialink->getUserGroup()->getLogins()[0];

        if ($this->adminGroups->isAdmin($recipient)) {

            $ignoreList = $this->uiFactory->createButton("expansion_players.gui.players.window.ignorelist")
                ->setTranslate(true)
                ->setAction(
                    $this->actionFactory->createManialinkAction(
                        $manialink,
                        [$this, "callbackChatCommand"],
                        ["action" => "//ignorelist"],
                        true
                    )
                );

            $guestList = $this->uiFactory->createButton("expansion_players.gui.players.window.guestlist")
                ->setTranslate(true)
                ->setAction(
                    $this->actionFactory->createManialinkAction(
                        $manialink,
                        [$this, "callbackChatCommand"],
                        ["action" => "//guestlist"],
                        true
                    )
                );

            $banList = $this->uiFactory->createButton("expansion_players.gui.players.window.banlist")
                ->setTranslate(true)
                ->setAction(
                    $this->actionFactory->createManialinkAction(
                        $manialink,
                        [$this, "callbackChatCommand"],
                        ["action" => "//banlist"],
                        true
                    )
                );

            $blackList = $this->uiFactory->createButton("expansion_players.gui.players.window.blacklist")
                ->setTranslate(true)
                ->setAction(
                    $this->actionFactory->createManialinkAction(
                        $manialink,
                        [$this, "callbackChatCommand"],
                        ["action" => "//blacklist"],
                        true
                    )
                );

            $row = $this->uiFactory->createLayoutLine(125, 0,
                [$guestList, $ignoreList, $banList, $blackList], 2);
            $manialink->addChild($row);


        }


        $frame = Frame::create();;
        $frame->setPosition(120, -16);

        $manialink->setData("playerFrame", $frame);
        $manialink->addChild($frame);

    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);

        if ($this->playerToSet) {
            $this->setPlayer($manialink, $this->playerToSet);
            $this->playerToSet = null;
        }

        $width = 60;
        $recipient = $manialink->getUserGroup()->getLogins()[0];

        /** @var Frame $frame */
        $login = $manialink->getData('playerLogin');
        $player = $this->playerStorage->getPlayerInfo($login);

        $frame = $manialink->getData('playerFrame');
        $frame->removeAllChildren();

        $row = $this->uiFactory->createLayoutRow(0, 0, [], -2);


        $element = $this->uiFactory->createLabel($player->getNickName(), uiLabel::TYPE_HEADER);
        $element->setTextSize(5)->setSize($width, 10)->setAlign("center", "top")
            ->setPosition($width / 2, 0);
        $row->addChild($element);

        $elem = [
            $this->uiFactory->createLabel("expansion_players.gui.players.window.column.from")
                ->setSize(20, 5)->setTranslate(true),
            $this->uiFactory->createLabel($player->getPath()),
        ];
        $line = $this->uiFactory->createLayoutLine(0, 0, $elem, 2);
        $row->addChild($line);


        $elem = [
            $this->uiFactory->createLabel("expansion_players.gui.players.window.language")
                ->setSize(20, 5)->setTranslate(true),
            $this->uiFactory->createLabel($player->getLanguage()),
        ];
        $line = $this->uiFactory->createLayoutLine(0, 0, $elem, 2);
        $row->addChild($line);

        $elem = [
            $this->uiFactory->createLabel("expansion_players.gui.players.window.gameversion")
                ->setSize(20, 5)->setTranslate(true),
            $this->uiFactory->createLabel($player->getClientVersion()),
        ];
        $line = $this->uiFactory->createLayoutLine(0, 0, $elem, 2);
        $row->addChild($line);

        $elem = [
            $this->uiFactory->createLabel("expansion_players.gui.players.window.ladder")
                ->setSize(20, 5)->setTranslate(true),
            $this->uiFactory->createLabel(floor($player->getLadderScore())),
        ];

        $line = $this->uiFactory->createLayoutLine(0, 0, $elem, 1);
        $row->addChild($line);


        if ($this->adminGroups->isAdmin($recipient)) {
            $this->createAdminControls($manialink, $row);
        }

        $frame->addChild($row);
    }


    /**
     * @param ManialinkInterface $manialink
     * @param layoutRow          $row
     */
    private function createAdminControls($manialink, $row)
    {
        $actions = $manialink->getData('playerActions');
        $login = $manialink->getData('playerLogin');

        if ($this->getIgnoredStatus($login)) {
            $muteText = "expansion_players.gui.players.window.allow";
            $color = uiButton::COLOR_SUCCESS;
        } else {
            $muteText = "expansion_players.gui.players.window.mute";
            $color = uiButton::COLOR_WARNING;
        }

        $elem = [
            $this->uiFactory->createConfirmButton($muteText, uiButton::TYPE_DEFAULT)
                ->setAction($actions['mute'])
                ->setBackgroundColor($color),
            $this->uiFactory->createConfirmButton("expansion_players.gui.players.window.guest", uiButton::TYPE_DEFAULT)
                ->setAction($actions['guest'])
                ->setBackgroundColor(UiButton::COLOR_DEFAULT),
        ];
        $line = $this->uiFactory->createLayoutLine(10, 0, $elem, 2);
        $row->addChild($line);

        $separator = $this->uiFactory->createLine(0, 0)->setLength(60)->setStroke(0.5);
        $row->addChild($separator);


        $elem = $this->uiFactory->createLabel("expansion_players.gui.players.window.reason")
            ->setSize(20, 5)
            ->setTranslate(true);
        $row->addChild($elem);
        $elem = $this->uiFactory->createInput('reason', "", 60);
        $row->addChild($elem);


        $elem = [
            $this->uiFactory->createConfirmButton("expansion_players.gui.players.window.kick", uiButton::TYPE_DEFAULT)
                ->setAction($actions['kick'])
                ->setBackgroundColor(UiButton::COLOR_DEFAULT),
            $this->uiFactory->createConfirmButton("expansion_players.gui.players.window.ban", uiButton::TYPE_DEFAULT)
                ->setAction($actions['ban'])
                ->setBackgroundColor(UiButton::COLOR_DEFAULT),
            $this->uiFactory->createConfirmButton("expansion_players.gui.players.window.black", uiButton::TYPE_DEFAULT)
                ->setAction($actions['black'])
                ->setBackgroundColor(UiButton::COLOR_SECONDARY),


        ];
        $line = $this->uiFactory->createLayoutLine(10, 0, $elem, 2);
        $row->addChild($line);
    }


    /**
     * @param ManialinkInterface $manialink
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $this->updateData($manialink);

        $selectButton = $this->uiFactory->createButton('expansion_players.gui.players.window.column.select',
            uiButton::TYPE_DEFAULT)->setSize(10, 5)->setTranslate(true);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($manialink->getData('dataCollection'))
            ->setManialinkFactory($this)
            ->addIconColumn('zoneIcon', '#', 1)
            ->addTextColumn(
                'country',
                'expansion_players.gui.players.window.column.from',
                4,
                true,
                true

            )
            ->addTextColumn(
                'login',
                'expansion_players.gui.players.window.column.login',
                4,
                true,
                true

            )
            ->addTextColumn(
                'nickname',
                'expansion_players.gui.players.window.column.nickname',
                6,
                true,
                true

            )
            ->addActionColumn('login', "expansion_players.gui.players.window.column.select",
                3, [$this, "callbackSetPlayer"], $selectButton);


        $manialink->setData('grid', $gridBuilder);

        $frame = $manialink->getContentFrame();
        $this->setGridSize($frame->getWidth(), $frame->getHeight() - 10);

        $this->setGridSize(100, 90);
        $this->setGridPosition(0, 0);

    }


    /**
     * @param ManialinkInterface|Window $manialink
     * @param string                    $login
     * @param array                     $entries
     * @param array                     $args
     */
    public function callbackSetPlayer($manialink, $login, $entries, $args)
    {
        $this->playerToSet = $args['login'];
        $this->update($manialink->getUserGroup());
    }

    /**
     * @param ManialinkInterface|Window $manialink
     * @param string                    $login
     */
    public function setPlayer($manialink, $login)
    {

        $actions = [
            "mute" => (string)$this->actionFactory->createManialinkAction($manialink, [$this, 'callbackIgnore'],
                [
                    "login" => $login,
                ]),
            "kick" => (string)$this->actionFactory->createManialinkAction($manialink, [$this, 'callbackKick'],
                [
                    "login" => $login,
                ]),
            "ban" => (string)$this->actionFactory->createManialinkAction($manialink, [$this, 'callbackBan'],
                [
                    "login" => $login,
                ]),
            "black" => (string)$this->actionFactory->createManialinkAction($manialink, [$this, 'callbackBlack'],
                [
                    "login" => $login,
                ]),
            "guest" => (string)$this->actionFactory->createManialinkAction($manialink, [$this, 'callbackGuest'],
                [
                    "login" => $login,
                ]),
        ];

        $manialink->setData('playerActions', $actions);
        $manialink->setData("playerLogin", $login);
    }

    public function callbackIgnore($manialink, $login, $entries, $args)
    {
        $status = $this->getIgnoredStatus($args['login']);

        if ($status) {
            $this->callChatCommand($login, "//unignore ".$args['login']);
        } else {
            $this->callChatCommand($login, "//ignore ".$args['login']);
        }
        $this->updateData($manialink);
        $this->callbackSetPlayer($manialink, $login, [], ['login' => $args['login']]);
    }

    public function callbackKick($manialink, $login, $entries, $args)
    {
        $this->callChatCommand($login, "//kick ".$args['login'].' "'.$entries['reason'].'"');
        $this->updateData($manialink);
        $this->callbackSetPlayer($manialink, $login, [], ['login' => $args['login']]);
    }

    public function callbackGuest($manialink, $login, $entries, $args)
    {
        $this->callChatCommand($login, "//addguest ".$args['login']);
        $this->updateData($manialink);
        $this->callbackSetPlayer($manialink, $login, [], ['login' => $args['login']]);
    }

    public function callbackBan($manialink, $login, $entries, $args)
    {
        $this->callChatCommand($login, "//ban ".$args['login'].' "'.$entries['reason'].'"');
        $this->updateData($manialink);
        $this->callbackSetPlayer($manialink, $login, [], ['login' => $args['login']]);
    }

    public function callbackBlack($manialink, $login, $entries, $args)
    {
        $this->callChatCommand($login, "//black ".$args['login'].' "'.$entries['reason'].'"');
        $this->updateData($manialink);
        $this->callbackSetPlayer($manialink, $login, [], ['login' => $args['login']]);
    }

    public function callbackChatCommand($manialink, $login, $entries, $args)
    {
        $this->callChatCommand($login, $args['action']);
    }


    public function updateData($manialink)
    {
        $players = $this->playerStorage->getOnline();
        $data = [];
        foreach ($players as $login => $player) {
            $country = $this->countries->parseCountryFromPath($player->getPath());
            $data[] = [
                "login" => $player->getLogin(),
                "nickname" => TMString::trimLinks($player->getNickName()),
                "country" => $country,
                "zoneIcon" => "file://Media/Flags/".$this->countries->getCodeFromCountry($country).".dds",
            ];
        }
        $this->setData($manialink, $data);
    }


    /**
     * @param $login
     * @param $command
     */
    public function callChatCommand($login, $command)
    {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, $command, true);
    }

    /** Get ignore status for a player;
     *
     * @param string $login
     * @return bool
     */
    private function getIgnoredStatus($login)
    {
        try {
            $ignoreList = $this->connection->getIgnoreList();
            foreach ($ignoreList as $player) {
                if ($player->login === $login) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

}

