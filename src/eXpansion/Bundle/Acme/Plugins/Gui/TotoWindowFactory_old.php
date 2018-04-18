<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory as BaseWindowFactory;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Gui\Builders\uiBuilder;

class TotoWindowFactory_old extends BaseWindowFactory
{
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var Time
     */
    private $time;
    /**
     * @var JukeboxService
     */
    private $jukeboxService;

    /**
     * TotoWindowFactory constructor.
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WindowFactoryContext $context
     * @param MapStorage           $mapStorage
     * @param Time                 $time
     * @param JukeboxService       $jukeboxService
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        MapStorage $mapStorage,
        Time $time,
        JukeboxService $jukeboxService
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->mapStorage = $mapStorage;
        $this->time = $time;
        $this->jukeboxService = $jukeboxService;
    }

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);

        $manialink->getContentFrame()->removeAllChildren();

        $builder = new uiBuilder($this->uiFactory, $this->actionFactory, $manialink, $this);

        $maps = $this->mapStorage->getMaps();

        $current = $this->mapStorage->getCurrentMap();

        $queue = $this->jukeboxService->getMapQueue();


        $x = 0;

        $inc = '<uiLayoutLine margin="1">';
        foreach ($maps as $map) {
            $juke = "";

            if ($x % 8 == 0) {
                if ($x != 0) {
                    $inc .= '</uiLayoutRow>';
                }
                $inc .= '<uiLayoutRow margin="1">';
            }

            if ($map->uId == $current->uId) {
                $color = "0f03";
                $juke = "<uiLabel pos='1 -7' width='30' textColor='fff' textSize='1'>Map Currently Playing</uiLabel>";
            } else {
                $color = "fff3";
            }



            $n = $x + 1;


            $idx = 1;
            $sym = "";
            $gtime = "";
            foreach ($queue as $qmap) {
                if ($map->uId == $qmap->getMap()->uId) {
                    $nick = $qmap->getPlayer()->getNickName();
                    $color = "ff03";
                    $sym = "⏳".$idx;
                    $juke = "<uiLabel pos='1 -7' width='30' textColor='fff' textSize='1'>In Queue. Wished by $nick</uiLabel>";
                }
                $idx += 1;
            }

            $inc .= <<<EOL
                <frame size="40 10">              
                     <uiLabel actionCallback='callbackOk' actionParam='{$map->uId}' pos="32 -1" textColor="fff" textSize="4">$sym</uiLabel>                 
                     <uiLabel pos="1 -1" width="30" textColor="fff" textSize="1.5">{$map->name}</uiLabel>
                     <uiLabel pos="1 -4" width="30" textColor="fff" textSize="1">{$map->author} / {$gtime}</uiLabel> 
                     $juke
                     <quad backgroundColor="$color" size="40 10"/>         
                </frame>
EOL;
            $x++;
        }

        $inc .= '</uiLayoutRow>';
        $inc .= '</uiLayoutLine>';

        $manialink->getContentFrame()->addChild($builder->build(/** @lang text */
            <<<EOL
<window id="main">
           $inc
</window>
EOL
        ));

    }

    /*
     * <uiLayoutRow margin="2.">
            <uiLayoutLine margin="2">
                <uiButton actionCallback="callbackOk" type="decorated" >Ok</uiButton>
                <uiButton>Cancel</uiButton>
            </uiLayoutLine>
        </uiLayoutRow>
     */

    /** @var ManialinkInterface $manialink */
    public function callbackOk(
        $manialink,
        $login,
        $entries,
        $args
    ) {

        $map = $this->mapStorage->getMap($args['id']);
        if ($this->jukeboxService->checkMap($map)) {
            $this->jukeboxService->removeMap($map, $login, false);

        } else {
            $this->jukeboxService->addMapLast($map, $login, false);

        }


        $this->update($manialink->getUserGroup());
    }
}
