<?php

namespace eXpansion\Bundle\Chat\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Quad;
use FML\Script\Script;
use FML\Script\ScriptLabel;

class UpdateChatWidgetFactory extends WidgetFactory
{

    public $login;
    public $text;
    public $console;

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
        $login = $this->login;
        $text = $this->text;
        $console = $this->console;

        $hash = uniqid("exp2_");

        $script = new Script();
        $script->addCustomScriptLabel(ScriptLabel::OnInit,
            <<<EOL
            declare Text Exp_Chat_UpdateLogin for This = "";         
            declare Text Exp_Chat_UpdateText for This = "";
            declare Text Exp_Chat_UpdateConsole for This = "";
            declare Text Exp_Chat_check for This = "";
            declare Text Exp_Chat_oldCheck = "";   
            
            Exp_Chat_UpdateLogin = "$login";
            Exp_Chat_UpdateText = "$text"; 
            Exp_Chat_UpdateConsole = "$console";                                    
            Exp_Chat_check = "$hash";                                                                                       
EOL
        );

        $manialink->getFmlManialink()->setScript($script);
    }

    public function updateConsole($message)
    {
        $this->login = "";
        $this->text = "";
        $this->console = $message;
    }

    public function updateMessage($login, $text)
    {
        $this->login = $login;
        $this->text = $text;
        $this->console = "";
    }

}
