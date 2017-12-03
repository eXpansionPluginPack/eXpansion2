<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Plugins\VoteManager;
use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Quad;
use FML\Script\Script;
use FML\Script\ScriptLabel;

class UpdateVoteWidgetFactory extends WidgetFactory
{

    /**
     * @var VoteManager
     */
    private $voteManager;
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
        $quad = Quad::create();
        $quad->setPosition(900, 900);
        $manialink->addChild($quad);
    }


    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        $vote = $this->voteService->getCurrentVote();

        if ($vote) {
            $yes = number_format($vote->getYes(), 1, ".", "");
            $no = number_format($vote->getNo(), 1, ".", "");
            $elapsed = number_format($vote->getElapsedTime(), 1, ".", "");
            $total = number_format($vote->getTotalTime(), 1, ".", "");
            $hash = uniqid("exp2_");

            $script = new Script();
            $script->addCustomScriptLabel(ScriptLabel::OnInit,
                <<<EOL
            declare Real Exp_Vote_Yes for This = 1.;
            Exp_Vote_Yes = $yes;
            declare Real Exp_Vote_No for This = 0.;
            Exp_Vote_No = $no;            
            declare Real Exp_Vote_TimeElapsed for This = 1.;
            Exp_Vote_TimeElapsed = $elapsed;            
            declare Real Exp_Vote_TimeTotal for This = 30.;
            Exp_Vote_TimeTotal = $total;
            declare Text Exp_Vote_check for This = "";  
            Exp_Vote_check = "$hash";                                                                                       
EOL
            );

            $manialink->getFmlManialink()->setScript($script);

        }
    }

}
