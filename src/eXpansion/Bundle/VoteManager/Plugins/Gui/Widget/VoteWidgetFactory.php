<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetBackground;
use eXpansion\Framework\Gui\Components\Animation;
use eXpansion\Framework\Gui\Components\Button;
use eXpansion\Framework\Gui\Components\Label;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class VoteWidgetFactory extends WidgetFactory
{

    const x = 90;
    const y = 20;

    /** @var Label */
    protected $label;
    /**
     * @var VoteService
     */
    private $voteService;

    /**
     * @var UpdateVoteWidgetFactory
     */
    private $updateVoteWidgetFactory;


    /***
     * MenuFactory constructor.
     *
     * @param                         $name
     * @param                         $sizeX
     * @param                         $sizeY
     * @param null                    $posX
     * @param null                    $posY
     * @param WidgetFactoryContext    $context
     * @param Factory                 $uiFactory
     * @param VoteService             $voteService
     * @param UpdateVoteWidgetFactory $updateVoteWidgetFactory
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        Factory $uiFactory,
        VoteService $voteService,
        UpdateVoteWidgetFactory $updateVoteWidgetFactory

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->uiFactory = $uiFactory;
        $this->voteService = $voteService;
        $this->updateVoteWidgetFactory = $updateVoteWidgetFactory;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $frame = Frame::create();
        $frame->setScale(0.8);
        $manialink->addChild($frame);

        $label = $this->uiFactory->createLabel($this->voteService->getCurrentVote()->getQuestion(), Label::TYPE_HEADER);
        $label->setTextColor("fff")
            ->setPosition(self::x / 2, -1)
            ->setTextSize(4)
            ->setAlign("center", "top")
            ->setTranslate(true);
        $this->label = $label;
        $frame->addChild($this->label);

        $btnPosition = -9;
        $btn = $this->uiFactory->createButton(" F1", Button::TYPE_DEFAULT);
        $btn->setSize(18, 6)->setPosition(1, $btnPosition)
            ->setId("ButtonYes")
            ->setBackgroundColor("0f09");
        $btn->setAction(
            $this->actionFactory->createManialinkAction($manialink, [$this, "callbackYes"], null)
        );
        $frame->addChild($btn);

        $btn = $this->uiFactory->createButton(" F2", Button::TYPE_DEFAULT);
        $btn->setSize(18, 6)->setPosition(self::x - 19, $btnPosition)
            ->setId("ButtonNo")
            ->setBackgroundColor("f009");
        $btn->setAction(
            $this->actionFactory->createManialinkAction($manialink, [$this, "callbackNo"], null)
        );
        $frame->addChild($btn);

        $quad = Quad::create();
        $quad->setAlign("center", "center2");
        $quad->setSize(0.5, 9);
        $quad->setPosition(self::x / 2, $btnPosition - 3)
            ->setBackgroundColor("fff");
        $frame->addChild($quad);

        $quad = Quad::create("yes");
        $quad->setAlign("left", "top");
        $quad->setSize((self::x - 20 * 2) / 2, 6);
        $quad->setPosition(20, $btnPosition)
            ->setBackgroundColor("0f09");
        $frame->addChild($quad);

        $quad = Quad::create("no");
        $quad->setAlign("right", "top");
        $quad->setSize((self::x - 20 * 2) / 2, 6);
        $quad->setPosition(self::x - 20, $btnPosition)
            ->setBackgroundColor("0000");
        $frame->addChild($quad);

        $animation = $this->uiFactory->createAnimation();
        $manialink->addChild($animation);

        $quad = Quad::create("timer");
        $quad->setSize(self::x - 4, 1);
        $quad->setPosition(2, -self::y + 1)
            ->setAlign("left", "bottom")
            ->setBackgroundColor("fffa");
        $frame->addChild($quad);

        $animation->addAnimation($quad,
            "size='0 1'",
            $this->voteService->getCurrentVote()->getDuration() * 1000,
            0,
            Animation::Linear);


        $bg = $this->uiFactory->createWidgetBackground(90, 20);
        $frame->addChild($bg);

        $x = self::x;
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::KeyPress,
            <<<EOL
            
            if (Event.KeyName == "F1") {
                TriggerButtonClick("ButtonYes");                            
            }
            
            if (Event.KeyName == "F2") {
               TriggerButtonClick("ButtonNo");                                 
            }
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit,

            <<<EOL
            declare Real SizeX = 1. * ($x - 40) ;
            declare CMlQuad BgYes = (Page.GetFirstChild("yes") as CMlQuad);
            declare CMlQuad BgNo = (Page.GetFirstChild("no") as CMlQuad);
            {$this->updateVoteWidgetFactory->getScriptInitialization()}                                                                       
EOL
        );

        $variable = $this->updateVoteWidgetFactory->getVariable('VoteUpdater')->getVariableName();
        $onChange = $this->updateVoteWidgetFactory->getScriptOnChange(/** @lang text */
            <<<EOL
            
               declare Integer Yes = {$variable}["yes"];
               declare Integer No = {$variable}["no"];
               declare Real Total = 1. * (Yes + No);
               
               if (Total > 0) {
                    declare Real Ratio = 1. * (Yes / Total);
                    AnimMgr.Add(BgYes, "<elem size=\""^( SizeX * Ratio )^" 6\" />", 250, CAnimManager::EAnimManagerEasing::QuadInOut);               
                    AnimMgr.Add(BgNo, "<elem size=\""^( SizeX  * (1. - Ratio) )^" 6\" />", 250, CAnimManager::EAnimManagerEasing::QuadInOut);                                         
               }
               
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop, $onChange);
    }

    public function callbackYes($manialink, $login, $entries, $args)
    {
        if ($this->voteService->getCurrentVote()) {
            $this->voteService->getCurrentVote()->castYes($login);
        }

    }

    public function callbackNo($manialink, $login, $entries, $args)
    {
        if ($this->voteService->getCurrentVote()) {
            $this->voteService->getCurrentVote()->castNo($login);
        }
    }

}