<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Bundle\VoteManager\Structures\AbstractVote;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetBackground;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class VoteWidgetFactory extends WidgetFactory
{

    const x = 90;
    const y = 20;
    /** @var uiLabel */
    protected $label;
    /**
     * @var VoteService
     */
    private $voteService;


    /***
     * MenuFactory constructor.
     *
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param WidgetFactoryContext $context
     * @param Factory $uiFactory
     * @param VoteService $voteService
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        VoteService $voteService
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->voteService = $voteService;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $label = $this->uiFactory->createLabel("", UiLabel::TYPE_HEADER);
        $label->setTextColor("fff")
            ->setPosition(self::x / 2, -1)
            ->setTextSize(4)
            ->setAlign("center", "top")
            ->setTranslate(true);
        $this->label = $label;
        $manialink->addChild($this->label);

        $btnPosition = -9;
        $btn = $this->uiFactory->createButton(" F1", UiButton::TYPE_DEFAULT);
        $btn->setSize(18, 6)->setPosition(1, $btnPosition)
            ->setId("ButtonYes")
            ->setBackgroundColor("0f09");
        $btn->setAction(
            $this->actionFactory->createManialinkAction($manialink, [$this, "callbackYes"], null)
        );
        $manialink->addChild($btn);

        $btn = $this->uiFactory->createButton(" F2", UiButton::TYPE_DEFAULT);
        $btn->setSize(18, 6)->setPosition(self::x - 19, $btnPosition)
            ->setBackgroundColor("f009")
            ->setId("ButtonNo");
        $btn->setAction(
            $this->actionFactory->createManialinkAction($manialink, [$this, "callbackNo"], null)
        );
        $manialink->addChild($btn);

        $quad = Quad::create();
        $quad->setAlign("center", "center2");
        $quad->setSize(0.5, 9);
        $quad->setPosition(self::x / 2, $btnPosition - 3)
            ->setBackgroundColor("fff");
        $manialink->addChild($quad);

        $quad = Quad::create("yes");
        $quad->setAlign("left", "top");
        $quad->setSize((self::x - 20 * 2) / 2, 6);
        $quad->setPosition(20, $btnPosition)
            ->setBackgroundColor("0f09");
        $manialink->addChild($quad);

        $quad = Quad::create("no");
        $quad->setAlign("right", "top");
        $quad->setSize((self::x - 20 * 2) / 2, 6);
        $quad->setPosition(self::x - 20, $btnPosition)
            ->setBackgroundColor("f009");
        $manialink->addChild($quad);

        $quad = Quad::create("timer");
        $quad->setSize(self::x - 4, 1);
        $quad->setPosition(2, -self::y + 1)
            ->setAlign("left", "bottom")
            ->setBackgroundColor("fffa");
        $manialink->addChild($quad);

        $bg = new WidgetBackground(90, 20);
        $manialink->addChild($bg);

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
            declare CMlQuad Timer = (Page.GetFirstChild("timer") as CMlQuad);
            declare Real Exp_Vote_Yes for This = 1.;
            declare Real Exp_Vote_No for This = 0.;
            declare Real Exp_Vote_TimeElapsed for This = 1.;
            declare Real Exp_Vote_TimeTotal for This = 30.;
            declare Text Exp_Vote_check for This = "";
            declare Text Exp_Vote_oldCheck = "";                                                                                                       
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
            if (Exp_Vote_check != Exp_Vote_oldCheck) {  
               Exp_Vote_oldCheck = Exp_Vote_check;          
               declare Real Total = (Exp_Vote_Yes + Exp_Vote_No);
               
               if (Total > 0) {
                    declare Real Ratio = 1. * (Exp_Vote_Yes / Total);
                    BgYes.Size.X = SizeX  * Ratio ;
                    BgNo.Size.X = SizeX  * (1. - Ratio);
               }
               
               Timer.Size.X = 86. *((Exp_Vote_TimeTotal - Exp_Vote_TimeElapsed) / Exp_Vote_TimeTotal);
            }                                                                            
EOL
        );


    }

    public function setMessage($message)
    {
        $this->label->setTextId($message);
    }


    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink); // TODO: Change the autogenerated stub
    }

    public function callbackYes($manialink, $login, $entries, $args)
    {
        if ($this->voteService->getCurrentVote() instanceof AbstractVote) {
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
