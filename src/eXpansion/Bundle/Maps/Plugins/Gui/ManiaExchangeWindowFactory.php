<?php

namespace eXpansion\Bundle\Maps\Plugins\Gui;

use eXpansion\Bundle\Maps\Plugins\ManiaExchange;
use eXpansion\Bundle\Maps\Structure\MxInfo;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollection;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiDropdown;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Quad;

class ManiaExchangeWindowFactory extends GridWindowFactory
{
    /** @var  uiDropdown */
    public $lengthBox;
    /** @var  uiDropdown */
    public $stylebox;
    /** @var  uiDropdown */
    public $sitebox;
    /** @var  uiDropdown */
    public $difficultiesBox;
    /** @var  uiDropdown */
    public $tpackBox;

    public $tpack = [
        "Server Titlepack" => "!server",
        "All" => "",
    ];

    /** @var  uiDropdown */
    private $orderbox;
    /** @var  uiDropdown */
    private $opbox;
    /** @var  uiDropdown */
    private $modebox;

    /** @var  array */
    private $tracksearch;

    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var Time */

    protected $timeFormatter;
    /**
     * @var  ManiaExchange $mxPlugin
     */
    private $mxPlugin;
    /**
     * @var array
     */
    private $order;
    /**
     * @var array
     */
    private $length;
    /**
     * @var array
     */
    private $mapStylesTm;
    /**
     * @var array
     */
    private $mapStylesSm;
    /**
     * @var array
     */
    private $difficulties;
    /**
     * @var array
     */
    private $operator;

    /**
     * @var Http
     */
    private $http;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;

    /**
     * ManiaExchangeWindowFactory constructor.
     *
     * @param                       $name
     * @param                       $sizeX
     * @param                       $sizeY
     * @param null                  $posX
     * @param null                  $posY
     * @param WindowFactoryContext  $context
     * @param GridBuilderFactory    $gridBuilderFactory
     * @param DataCollectionFactory $dataCollectionFactory
     * @param Time                  $time
     * @param ManiaExchange         $mxPlugin
     * @param Http                  $http
     * @param GameDataStorage       $gameDataStorage
     * @param                       $tracksearch
     * @param                       $order
     * @param                       $length
     * @param                       $mapStylesTm
     * @param                       $mapStylesSm
     * @param                       $difficulties
     * @param                       $operator
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WindowFactoryContext $context,
        GridBuilderFactory $gridBuilderFactory,
        DataCollectionFactory $dataCollectionFactory,
        Time $time,
        ManiaExchange $mxPlugin,
        Http $http,
        GameDataStorage $gameDataStorage,
        $tracksearch,
        $order,
        $length,
        $mapStylesTm,
        $mapStylesSm,
        $difficulties,
        $operator

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->timeFormatter = $time;
        $this->mxPlugin = $mxPlugin;
        $this->tracksearch = array_flip($tracksearch);

        $this->order = array_flip($order);
        $this->length = array_flip($length);
        $this->mapStylesTm = array_flip($mapStylesTm);
        $this->mapStylesSm = array_flip($mapStylesSm);
        $this->difficulties = array_flip($difficulties);
        $this->operator = array_flip($operator);
        $this->http = $http;
        $this->gameDataStorage = $gameDataStorage;
    }

    /**
     * @param ManialinkInterface $manialink
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $x = 0;

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);

        $this->modebox = $this->uiFactory->createDropdown("mode", $this->tracksearch, 0);
        $this->modebox->setPosition($x, -6, 2);
        $manialink->addChild($this->modebox);

        $label = $this->uiFactory->createLabel("Sort by", uiLabel::TYPE_HEADER);
        $label->setPosition($x, 0);
        $manialink->addChild($label);

        $x += 32;
        $this->orderbox = $this->uiFactory->createDropdown("order", $this->order, 0);
        $this->orderbox->setPosition($x, -6, 2);
        $manialink->addChild($this->orderbox);

        $label = $this->uiFactory->createLabel("Order", uiLabel::TYPE_HEADER);
        $label->setPosition($x, 0);
        $manialink->addChild($label);

        $x += 32;
        $this->opbox = $this->uiFactory->createDropdown("operator", $this->operator, 0);
        $this->opbox->setPosition($x, -6, 2);
        $manialink->addChild($this->opbox);

        $label = $this->uiFactory->createLabel("Operator", uiLabel::TYPE_HEADER);
        $label->setPosition($x, 0);
        $manialink->addChild($label);

        $x += 32;
        $this->lengthBox = $this->uiFactory->createDropdown("length", $this->length, 0);
        $this->lengthBox->setPosition($x, -6, 2);
        $manialink->addChild($this->lengthBox);

        $label = $this->uiFactory->createLabel("Length", uiLabel::TYPE_HEADER);
        $label->setPosition($x, 0);
        $manialink->addChild($label);

        $x += 32;
        $this->stylebox = $this->uiFactory->createDropdown("style", $this->mapStylesTm, 0);
        $this->stylebox->setPosition($x, -6, 2);
        $manialink->addChild($this->stylebox);

        $label = $this->uiFactory->createLabel("Style", uiLabel::TYPE_HEADER);
        $label->setPosition($x, 0);
        $manialink->addChild($label);

        $x += 32;
        $this->difficultiesBox = $this->uiFactory->createDropdown("difficulties", $this->difficulties, 0);
        $this->difficultiesBox->setPosition($x, -6, 2);
        $manialink->addChild($this->difficultiesBox);

        $label = $this->uiFactory->createLabel("Difficulty", uiLabel::TYPE_HEADER);
        $label->setPosition($x, 0);
        $manialink->addChild($label);

        /// second line
        $idx = 0;
        if ($this->gameDataStorage->getTitle() == "SM") {
            $idx = 1;
        }
        $this->sitebox = $this->uiFactory->createDropdown("site", ["Trackmania" => "tm", "Storm" => "sm"], $idx);
        $this->sitebox->setPosition(0, -14, 2);
        $manialink->addChild($this->sitebox);

        $this->tpackBox = $this->uiFactory->createDropdown("tpack", $this->tpack, 0);
        $this->tpackBox->setPosition(32, -14, 2);
        $manialink->addChild($this->tpackBox);

        $mapname = $this->uiFactory->createInput("map");

        $author = $this->uiFactory->createInput("author");


        $search = $this->uiFactory->createButton('🔍 Search', uiButton::TYPE_DECORATED);
        $search->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackSearch'],
            ["ml" => $manialink]));

        $all = $this->uiFactory->createConfirmButton('Install view', uiButton::TYPE_DEFAULT);
        $tooltip->addTooltip($all, "Install all maps from the view");
        $all->setBackgroundColor("f00");
        $all->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackInstallAll'],
            ["ml" => $manialink]));

        $spacer = Quad::create();
        $spacer->setSize(7, 3)->setOpacity(0);

        $line = $this->uiFactory->createLayoutLine(64, -14, [$mapname, $author, $spacer, $search, $all], 2);
        $manialink->addChild($line);

        $addButton = $this->uiFactory->createConfirmButton('Install', uiButton::TYPE_DEFAULT);
        $addButton->setSize(20, 4);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($manialink->getData('dataCollection'))
            ->setManialinkFactory($this)
            ->addTextColumn(
                'index',
                'expansion_mx.gui.mxsearch.column.index',
                1,
                true,
                false
            )->addTextColumn(
                'name',
                'expansion_mx.gui.mxsearch.column.name',
                5,
                true,
                false
            )->addTextColumn(
                'author',
                'expansion_mx.gui.mxsearch.column.author',
                3,
                true
            );


        if ($this->gameDataStorage->getTitle() == "TM") {
            $gridBuilder
                ->addTextColumn(
                    'envir',
                    'expansion_mx.gui.mxsearch.column.envir',
                    2,
                    true,
                    false
                )->addTextColumn(
                    'awards',
                    'expansion_mx.gui.mxsearch.column.awards',
                    1,
                    true,
                    false
                )->addTextColumn(
                    'length',
                    'expansion_mx.gui.mxsearch.column.length',
                    2,
                    true,
                    false
                )->addTextColumn(
                    'style',
                    'expansion_mx.gui.mxsearch.column.style',
                    2,
                    true,
                    false
                );
        }
        if ($this->gameDataStorage->getTitle() == "SM") {
            $gridBuilder->addTextColumn(
                'maptype',
                'expansion_mx.gui.mxsearch.column.maptype',
                2,
                true,
                false
            )->addTextColumn(
                'awards',
                'expansion_mx.gui.mxsearch.column.awards',
                1,
                true,
                false
            )->addTextColumn(
                'difficulty',
                'expansion_mx.gui.mxsearch.column.difficulty',
                2,
                true,
                false
            );
        }

        $gridBuilder->addActionColumn('add', 'expansion_mx.gui.mxsearch.column.add', 2, array($this, 'callbackAdd'),
            $addButton);
        $this->setGridPosition(0, -24);

        $content = $manialink->getContentFrame();
        $this->setGridSize($content->getWidth(), $content->getHeight() - 24);
        $manialink->setData('grid', $gridBuilder);
        $this->gridBuilder = $gridBuilder;
    }

    /**
     * @param ManialinkInterface $manialink
     * @param                    $login
     * @param                    $params
     * @param                    $args
     */
    public function callbackAdd(ManialinkInterface $manialink, $login, $params, $args)
    {
        if ($params['site'] == "") {
            $params['site'] = strtolower($this->gameDataStorage->getTitle());
        }
        $this->mxPlugin->addMapToQueue($login, $args['mxid'], $params['site']);
    }

    public function callbackInstallAll(ManialinkInterface $manialink, $login, $params, $arguments)
    {
        /** @var DataCollection $collection */
        $collection = $manialink->getData('dataCollection');

        /** @var GridBuilder $grid */
        $grid = $manialink->getData('grid');

        $data = $collection->getData($grid->getCurrentPage());
        $this->mxPlugin->addAllMaps($login, $data);
    }

    /**
     * @param ManialinkInterface $manialink
     * @param                    $login
     * @param                    $params
     * @param                    $arguments
     */
    public function callbackSearch(ManialinkInterface $manialink, $login, $params, $arguments)
    {
        $params = (object)$params;

        $this->modebox->setSelectedByValue($params->mode);
        $this->orderbox->setSelectedByValue($params->order);
        $this->opbox->setSelectedByValue($params->operator);
        $this->lengthBox->setSelectedByValue($params->length);
        $this->stylebox->setSelectedByValue($params->style);
        $this->difficultiesBox->setSelectedByValue($params->difficulties);
        $this->tpackBox->setSelectedByValue($params->tpack);

        $options = "";

        if ($params->tpack) {
            $title = $params->tpack;
            if ($params->tpack == "!server") {
                $title = explode("@", $this->gameDataStorage->getVersion()->titleId);
                $title = $title[0];
            }
            $options .= "&tpack=".$title;
        }
        if ($params->operator != -1) {
            $options .= "&lengthop=".$params->operator;
        }

        $args = "&mode=".$params->mode."&trackname=".urlencode($params->map)."&anyauthor=".urlencode($params->author).
            "&style=".$params->style."&priord=".$params->order."&length=".$params->length.
            "&limit=100&gv=1".$options;

        $query = 'https://'.$params->site.'.mania-exchange.com/tracksearch2/search?api=on'.$args;

        $this->http->get($query, [$this, 'setMaps'], ['login' => $login, 'params' => $params, 'ml' => $manialink]);

    }


    /**
     * @param HttpResult $result
     */
    public function setMaps(HttpResult $result)
    {
        $manialink = $result->getAdditionalData()['ml'];
        $params = $result->getAdditionalData()['params'];

        $this->gridBuilder->goToFirstPage($manialink);

        if ($result->hasError()) {
            echo $result->getError();

            return;
        }

        $json = json_decode($result->getResponse(), true);
        $data = [];
        foreach ($json['results'] as $idx => $mxInfo) {
            $map = new MxInfo($mxInfo);
            $data[] = [
                "index" => $idx + 1,
                "name" => TMString::trimControls($map->gbxMapName),
                "author" => $map->username,
                "envir" => $map->environmentName,
                "awards" => $map->awardCount ? '$ff0🏆 $fff'.$map->awardCount : "",
                "length" => $map->lengthName,
                "difficulty" => $map->difficultyName,
                "maptype" => $map->mapType,
                "style" => $map->styleName,
                "mxid" => $map->trackID,
                "mxsite" => $params->site,
            ];
        }

        $this->setData($manialink, $data);
        $group = $this->groupFactory->createForPlayer($result->getAdditionalData()['login']);
        $this->update($group);
    }
}
