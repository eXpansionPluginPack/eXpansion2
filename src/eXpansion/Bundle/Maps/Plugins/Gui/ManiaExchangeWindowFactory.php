<?php

namespace eXpansion\Bundle\Maps\Plugins\Gui;

use eXpansion\Bundle\Maps\Plugins\Jukebox;
use eXpansion\Bundle\Maps\Plugins\ManiaExchange;
use eXpansion\Bundle\Maps\Structure\MxInfo;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiDropdown;
use eXpansion\Framework\Gui\Components\uiLabel;

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
    private $map_styles_tm;
    /**
     * @var array
     */
    private $map_style_sm;
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
     * ManiaExchangeWindowFactory constructor.
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param WindowFactoryContext $context
     * @param GridBuilderFactory $gridBuilderFactory
     * @param DataCollectionFactory $dataCollectionFactory
     * @param Time $time
     * @param ManiaExchange $mxPlugin
     * @param Http $http
     * @param $tracksearch
     * @param $order
     * @param $length
     * @param $map_styles_tm
     * @param $map_style_sm
     * @param $difficulties
     * @param $operator
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
        $tracksearch,
        $order,
        $length,
        $map_styles_tm,
        $map_style_sm,
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
        $this->map_styles_tm = array_flip($map_styles_tm);
        $this->map_style_sm = array_flip($map_style_sm);
        $this->difficulties = array_flip($difficulties);
        $this->operator = array_flip($operator);
        $this->http = $http;
    }

    /**
     * @param ManialinkInterface $manialink
     * @return mixed
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $collection = $this->dataCollectionFactory->create($this->getData());
        $collection->setPageSize(20);

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
        $this->stylebox = $this->uiFactory->createDropdown("style", $this->map_styles_tm, 0);
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

        $this->sitebox = $this->uiFactory->createDropdown("site", ["Trackmania" => "tm", "Storm" => "sm"], 0);
        $this->sitebox->setPosition(0, -14, 2);
        $manialink->addChild($this->sitebox);

        $mapname = $this->uiFactory->createInput("map");
        $mapname->setHeight(8);
        $author = $this->uiFactory->createInput("author");
        $author->setHeight(8);

        $search = $this->uiFactory->createButton('🔍 Search', uiButton::TYPE_DECORATED);
        $search->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackSearch'], null));

        $line = $this->uiFactory->createLayoutLine(32, -14, [$mapname, $author, $search], 2);
        $manialink->addChild($line);

        $addButton = $this->uiFactory->createButton('install', uiButton::TYPE_DEFAULT);
        $addButton->setTextColor("fff")->setSize(20, 5);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'index',
                'expansion_mx.gui.window.column.index',
                1,
                true,
                false
            )->addTextColumn(
                'name',
                'expansion_mx.gui.window.column.name',
                5,
                true,
                false
            )->addTextColumn(
                'author',
                'expansion_mx.gui.window.column.author',
                3,
                false
            )->addTextColumn(
                'envir',
                'expansion_mx.gui.window.column.envir',
                2,
                true,
                false
            )->addTextColumn(
                'awards',
                'expansion_mx.gui.window.awards',
                1,
                true,
                false
            )->addTextColumn(
                'length',
                'expansion_mx.gui.window.length',
                2,
                true,
                false
            )->addTextColumn(
                'style',
                'expansion_mx.gui.window.column.style',
                2,
                true,
                false
            )
            ->addActionColumn('add', 'expansion_maps.gui.window.column.add', 2, array($this, 'callbackAdd'),
                $addButton);
        $this->setGridPosition(0, -24);

        $content = $manialink->getContentFrame();
        $this->setGridSize($content->getWidth(), $content->getHeight() - 24);
        $manialink->setData('grid', $gridBuilder);

    }


    public function findIndex($arr, $search)
    {
        $x = 0;
        foreach ($arr as $idx => $value) {
            if ($value == $search) {
                return $x;
            }
            $x++;
        }

        return -1;
    }

    public function callbackAdd($login, $params, $args)
    {
        $this->mxPlugin->addMap($login, $args['mxid'], $params['site']);
    }

    public function callbackSearch($login, $params, $args)
    {
        $params = (object)$params;

        $this->modebox->setSelectedIndex($this->findIndex($this->tracksearch, $params->mode));
        $this->orderbox->setSelectedIndex($this->findIndex($this->order, $params->order));
        $this->opbox->setSelectedIndex($this->findIndex($this->operator, $params->operator));
        $this->lengthBox->setSelectedIndex($this->findIndex($this->length, $params->length));
        $this->stylebox->setSelectedIndex($this->findIndex($this->map_styles_tm, $params->style));
        $this->difficultiesBox->setSelectedIndex($this->findIndex($this->difficulties, $params->difficulties));


        $args = "&mode=".$params->mode."&trackname=".urlencode($params->map)."&anyauthor=".urlencode($params->author).
            "&style=".$params->style."&priord=".$params->order."&length=".$params->length."&lengthop=".$params->operator."&limit=100&gv=1";

        $query = 'https://'.$params->site.'.mania-exchange.com/tracksearch2/search?api=on'.$args;
        $this->http->get($query, [$this, 'setMaps'], ['login' => $login]);

    }


    public function setMaps(HttpResult $result)
    {


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
                "name" => TMString::trimControls($map->GbxMapName),
                "author" => $map->Username,
                "envir" => $map->EnvironmentName,
                "awards" => $map->AwardCount,
                "length" => $map->LengthName,
                "style" => $map->StyleName,
                "mxid" => $map->TrackID,
            ];
        }

        $this->setData($data);
        echo "results:".count($this->getData())."\n";

        $group = $this->groupFactory->createForPlayer($result->getAdditionalData()['login']);
        $this->update($group);
    }
}
