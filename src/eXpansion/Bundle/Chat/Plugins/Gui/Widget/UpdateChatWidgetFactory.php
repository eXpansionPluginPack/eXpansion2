<?php

namespace eXpansion\Bundle\Chat\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Quad;
use FML\Script\Builder;
use FML\Script\Script;
use FML\Script\ScriptLabel;

class UpdateChatWidgetFactory extends WidgetFactory
{

    public $texts = [];
    public $console = [];

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
        WidgetFactoryContext $context
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

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
        $hash = uniqid("exp2_");

        $console = Builder::getArray($this->console, false);
        if (count($this->texts) == 0) {
            $text = "Text[][Text]";
        } else {
            $text = Builder::getArray($this->texts);
        }

        $script = new Script();
        $script->addCustomScriptLabel(ScriptLabel::OnInit,
            <<<EOL
            declare Text[][Text] Exp_Chat_UpdateText for This = Text[][Text];
            declare Text[] Exp_Chat_UpdateConsole for This = Text[];
            declare Text Exp_Chat_check for This = "";
            declare Text Exp_Chat_oldCheck = "";
                      
            Exp_Chat_UpdateText = $text; 
            Exp_Chat_UpdateConsole = $console;                                    
            Exp_Chat_check = "$hash";                                                                                       
EOL
        );

        $manialink->getFmlManialink()->setScript($script);
        $this->reset();
    }

    public function reset()
    {
        $this->console = [];
        $this->texts = [];
    }

    public function updateConsole($message)
    {
        $this->console[] = $message;
    }

    public function updateMessage($login, $text)
    {
        $this->texts[$login][] = $text;
    }

}
