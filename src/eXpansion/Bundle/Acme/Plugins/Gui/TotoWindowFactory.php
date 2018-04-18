<?php


namespace eXpansion\Bundle\Acme\Plugins\Gui;


use eXpansion\Bundle\LocalRecords\Model\RecordQueryBuilder;
use eXpansion\Bundle\Maps\Model\MxmapQuery;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\Countries;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Gui\Builders\uiBuilder;
use eXpansion\Framework\Gui\Components\Button;
use FML\Controls\Frame;

class TotoWindowFactory extends GridWindowFactory
{
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var ChatCommandDataProvider
     */
    private $chatCommandDataProvider;
    /**
     * @var Factory
     */
    private $factory;
    /**
     * @var AdminGroups
     */
    private $adminGroups;

    /** @var Countries */
    protected $countries;

    /** @var Time */
    protected $time;
    /**
     * @var RecordQueryBuilder
     */
    private $queryBuilder;
    /**
     * @var MxmapQuery
     */
    private $mxmapQuery;

    /**
     * PlayersWindow constructor.
     * @param                         $name
     * @param                         $sizeX
     * @param                         $sizeY
     * @param null                    $posX
     * @param null                    $posY
     * @param WindowFactoryContext    $context
     * @param MapStorage              $mapStorage
     * @param DataCollectionFactory   $dataCollectionFactory
     * @param GridBuilderFactory      $gridBuilderFactory
     * @param ChatCommandDataProvider $chatCommandDataProvider
     * @param Factory                 $factory
     * @param AdminGroups             $adminGroups
     * @param Countries               $countries
     * @param Time                    $time
     * @param RecordQueryBuilder      $queryBuilder
     * @param MxmapQuery              $mxmapQuery
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        MapStorage $mapStorage,
        DataCollectionFactory $dataCollectionFactory,
        GridBuilderFactory $gridBuilderFactory,
        ChatCommandDataProvider $chatCommandDataProvider,
        Factory $factory,
        AdminGroups $adminGroups,
        Countries $countries,
        Time $time,
        RecordQueryBuilder $queryBuilder,
        MxmapQuery $mxmapQuery
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->mapStorage = $mapStorage;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->chatCommandDataProvider = $chatCommandDataProvider;
        $this->factory = $factory;
        $this->adminGroups = $adminGroups;
        $this->countries = $countries;

        $this->time = $time;
        $this->queryBuilder = $queryBuilder;
        $this->mxmapQuery = $mxmapQuery;
    }

    protected function createContent(
        ManialinkInterface $manialink
    ) {
        parent::createContent($manialink);

        $manialink->setData('playerActions', []);
        $manialink->setData('mapUid', $this->mapStorage->getCurrentMap()->uId);

        $frame = Frame::create();;
        $frame->setPosition(75, 0);
        $manialink->setData("playerFrame", $frame);
        $manialink->addChild($frame);

    }

    /**
     * @param ManialinkInterface $manialink
     */
    protected function createGrid(
        ManialinkInterface $manialink
    ) {
        $this->updateData($manialink);

        $selectButton = $this->uiFactory->createButton('expansion_players.gui.players.window.column.select',
            Button::TYPE_DEFAULT)->setSize(10, 5)->setTranslate(true);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($manialink->getData('dataCollection'))
            ->setManialinkFactory($this)
            ->addTextColumn(
                'length',
                'Length',
                1,
                true,
                false

            )
            ->addTextColumn(
                'name',
                'Name',
                3,
                true,
                false
            )
            ->addActionColumn('uid', "Select",
                1, [$this, "callbackSetMap"], $selectButton);


        $manialink->setData('grid', $gridBuilder);

        $frame = $manialink->getContentFrame();
        $this->setGridSize(73, $frame->getHeight());
        $this->setGridPosition(0, 0);

    }

    protected function updateContent(
        ManialinkInterface $manialink
    ) {
        parent::updateContent($manialink);


        $recipient = $manialink->getUserGroup()->getLogins()[0];
        $map = $this->mapStorage->getMap($manialink->getData('mapUid'));


        $recs = $this->queryBuilder->getMapRecords($map->uId, 1, "asc", 10);
        $inc = <<<EOL
           <uiLabel size="40 6" textSize="3"></uiLabel>
           <uiLabel width="40" textSize="2">Local Records</uiLabel>
EOL;

        $rank = 1;
        foreach ($recs as $rec) {
            $inc .= "<uiLayoutLine margin='1'>";
            $inc .= '<uiLabel width="3">'.$rank.'.</uiLabel>';
            $inc .= '<uiLabel width="10">'.$this->time->timeToText($rec->getScore(), true).'</uiLabel>';
            $inc .= '<uiLabel width="30">'.$rec->getPlayer()->getNickname().'</uiLabel>';
            $inc .= "</uiLayoutLine>";
            $rank++;
        }

        /** @var Frame $frame */
        $frame = $manialink->getData('playerFrame');
        $frame->removeAllChildren();

        $builder = new uiBuilder($this->uiFactory, $this->actionFactory, $manialink, $this);

        $contents = $builder->build(/** @lang text */
            <<<EOL
        <window>
            <uiLayoutLine margin="3">
                <uiLayoutRow margin="1">
                    <uiLabel pos="45 0" align="center top" textSize="4" size="90 4">{$map->name}</uiLabel>
                    <uiLabel pos="45 0" align="center top" textSize="2" size="90 3">{$map->author}</uiLabel>
                    <quad size="90 0.2" backgroundColor="fff"/>
                    <uiLabel>Environment: $map->environnement</uiLabel>
                    <uiLabel>Mood: $map->mood</uiLabel>
                </uiLayoutRow>
                <uiLayoutRow margin="1">
                   $inc          
                </uiLayoutRow>     
             </uiLayoutLine>
        </window>
EOL
        );

        $frame->addChild($contents);

        $builder = new uiBuilder($this->uiFactory, $this->actionFactory, $manialink, $this);
        $xm = /** @lang text */
            <<<EOL
        <frame pos="-10 -80">
            <uiLayoutLine margin="2">
                <uiButton backgroundColor="090">Jukebox</uiButton>
                <uiConfirmButton backgroundColor="900">Delete</uiConfirmButton>
                <uiButton>Action</uiButton>
            </uiLayoutLine>
        </frame>
EOL;

        $actions = $builder->build($xm);
        $frame->addChild($actions);

    }


    /**
     * @param ManialinkInterface|Window $manialink
     * @param string                    $login
     * @param array                     $entries
     * @param array                     $args
     */
    public function callbackSetMap(
        $manialink,
        $login,
        $entries,
        $args
    ) {
        $manialink->setData("mapUid", $args['uid']);
        $this->update($manialink->getUserGroup());
    }


    public function callbackChatCommand(
        $manialink,
        $login,
        $entries,
        $args
    ) {
        $this->callChatCommand($login, $args['action']);
    }


    public function updateData(
        $manialink
    ) {
        $maps = $this->mapStorage->getMaps();
        $data = [];
        foreach ($maps as $map) {
            $data[] = [
                "uid" => $map->uId,
                "author" => $map->author,
                "name" => TMString::trimLinks($map->name),
                "length" => $this->time->timeToText($map->goldTime, false),
            ];
        }
        $this->setData($manialink, $data);
    }


    /**
     * @param $login
     * @param $command
     */
    public function callChatCommand(
        $login,
        $command
    ) {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, $command, true);
    }

}