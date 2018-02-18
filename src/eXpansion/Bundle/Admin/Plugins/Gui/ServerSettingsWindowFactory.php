<?php

namespace eXpansion\Bundle\Admin\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Gui\Components\Button;
use eXpansion\Framework\Gui\Components\Checkbox;
use eXpansion\Framework\Gui\Components\Input;
use eXpansion\Framework\Gui\Components\InputMasked;
use eXpansion\Framework\Gui\Components\Label;
use eXpansion\Framework\Gui\Components\Textbox;
use eXpansion\Framework\Gui\Components\Dropdown;
use FML\Controls\Frame;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\ServerOptions;


/**
 * Class Script settings factory
 *
 * @package eXpansion\Bundle\Menu\Plugins\Gui;
 * @author reaby
 */
class ServerSettingsWindowFactory extends WindowFactory
{
    /** @var  Checkbox */
    public $allowMapDownload;
    /** @var  Checkbox */
    public $autoSaveReplays;
    /** @var  Checkbox */
    public $autoSaveValidationReplays;
    /** @var  Checkbox */
    public $disableHorns;
    /** @var  Checkbox */
    public $disableServiceAnnounces;
    /** @var  Checkbox */
    public $nextUseChangingValidationSeed;

    /** @var  Input */
    public $nextMaxPlayers;
    /** @var  Input */
    public $nextMaxSpectators;
    /** @var  Checkbox */
    public $keepPlayerSlots;
    /** @var  Input */
    public $ladderServerLimitMax;
    /** @var  Input */
    public $ladderServerLimitMin;
    /** @var  Dropdown */
    public $nextLadderMode;
    /** @var  Dropdown */
    public $refereeMode;
    /** @var  Checkbox */
    public $isP2PUpload;
    /** @var  Checkbox */
    public $isP2PDownload;

    /** @var Console */
    protected $console;

    /** @var Connection */
    protected $connection;

    /** @var  AdminGroups */
    protected $adminGroupsHelper;

    /** @var  Input */
    protected $serverName;

    /** @var  Textbox */
    protected $comment;

    /** @var  InputMasked */
    protected $password;

    /** @var  InputMasked */
    protected $specpassword;

    /** @var  InputMasked */
    protected $refpassword;


    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WindowFactoryContext $context,
        AdminGroups $adminGroupsHelper,
        Connection $connection,
        Console $console
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->currentMenuView = Frame::create();
        $this->connection = $connection;
        $this->console = $console;
    }

    /**
     * @param ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {

        $options = $this->connection->getServerOptions();
        $manialink->setData("options", $options);

        $tooltip = $this->uiFactory->createTooltip();

        $frame = Frame::create();
        $firstColumn = $this->uiFactory->createLayoutRow(0, 0, [], 0);
        $secondColumn = $this->uiFactory->createLayoutRow(90, 0, [], 0);

        $label = $this->uiFactory->createLabel("Name", Label::TYPE_HEADER);
        $this->serverName = $this->uiFactory->createInput("name_string", "", 60);
        $firstColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->serverName]));

        $label = $this->uiFactory->createLabel("Comment", Label::TYPE_HEADER);
        $this->comment = $this->uiFactory->createTextbox("comment_string", "", 7, 60);
        $firstColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->comment]));

        $label = $this->uiFactory->createLabel("Password for Players", Label::TYPE_HEADER);
        $label->setWidth(60);
        $this->password = $this->uiFactory->createInputMasked("password_string", "", 60);
        $firstColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->password]));

        $label = $this->uiFactory->createLabel("Password for Spectators", Label::TYPE_HEADER);
        $label->setWidth(60);
        $this->specpassword = $this->uiFactory->createInputMasked("passwordForSpectator_string", "", 60);
        $firstColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->specpassword]));

        $label = $this->uiFactory->createLabel("Referee Mode", Label::TYPE_HEADER);
        $label->setWidth(60);
        $this->refereeMode = $this->uiFactory->createDropdown("refereeMode_integer",
            ["Validate top 3" => "0", "Validate all" => "1"]);
        $this->refereeMode->setWidth(60);
        $firstColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->refereeMode]));

        $label = $this->uiFactory->createLabel("Password for Referee", Label::TYPE_HEADER);
        $label->setWidth(60);
        $this->refpassword = $this->uiFactory->createInputMasked("refereePassword_string", "", 60);
        $firstColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->refpassword]));

        $frame->addChild($firstColumn);

// @region second column
        $label = $this->uiFactory->createLabel("Ladder Server", Label::TYPE_HEADER);
        $label->setWidth(60);
        $this->nextLadderMode = $this->uiFactory->createDropdown("nextLadderMode_integer",
            ["Disable Ladder" => "0", "Use Ladder" => "1"]);
        $this->nextLadderMode->setWidth(60);
        $secondColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->nextLadderMode]));

        $label = $this->uiFactory->createLabel("Ladder Limits ", Label::TYPE_HEADER);
        $label->setWidth(60);
        $separator = $this->uiFactory->createLabel("   to ", Label::TYPE_HEADER);
        $separator->setSize(10, 5);
        $this->ladderServerLimitMax = $this->uiFactory->createInput("ladderServerLimitMax_integer", "", 25);
        $this->ladderServerLimitMin = $this->uiFactory->createInput("ladderServerLimitMin_integer", "", 25);

        $line = $this->uiFactory->createLayoutLine(0, 0,
            [$this->ladderServerLimitMin, $separator, $this->ladderServerLimitMax]);
        $secondColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $line]));


        $label = $this->uiFactory->createLabel("Server Max Players", Label::TYPE_HEADER);
        $label->setWidth(60);
        $this->nextMaxPlayers = $this->uiFactory->createInput("nextMaxPlayers_integer", "", 60);
        $secondColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->nextMaxPlayers]));

        $label = $this->uiFactory->createLabel("Server Max Spectators", Label::TYPE_HEADER);
        $label->setWidth(60);
        $this->nextMaxSpectators = $this->uiFactory->createInput("nextMaxSpectators_integer", "", 60);
        $secondColumn->addChild($this->uiFactory->createLayoutRow(0, 0, [$label, $this->nextMaxSpectators]));


        $box = $this->uiFactory->createCheckbox('Autosave Validation Replays',
            'autoSaveValidationReplays_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->autoSaveValidationReplays = $box;

        $box = $this->uiFactory->createCheckbox('Autosave Replays', 'autoSaveReplays_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->autoSaveReplays = $box;

        $box = $this->uiFactory->createCheckbox('Allow Map Download', 'allowMapDownload_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->allowMapDownload = $box;

        $box = $this->uiFactory->createCheckbox('Disable Horns', 'disableHorns_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->disableHorns = $box;

        $box = $this->uiFactory->createCheckbox('Disable Service Announces',
            'disableServiceAnnounces_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->disableServiceAnnounces = $box;

        $box = $this->uiFactory->createCheckbox('Keep Player Slots',
            'keepPlayerSlots_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->keepPlayerSlots = $box;

        $box = $this->uiFactory->createCheckbox('Enable P2P Upload',
            'isP2PUpload_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->isP2PUpload = $box;

        $box = $this->uiFactory->createCheckbox('Enable P2P Download',
            'isP2PDownload_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->isP2PDownload = $box;

        $box = $this->uiFactory->createCheckbox('Keep Player Slots',
            'keepPlayerSlots_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->keepPlayerSlots = $box;

        $box = $this->uiFactory->createCheckbox('Use Changing Validation Seed',
            'nextUseChangingValidationSeed_boolean');
        $box->setWidth(60);
        $secondColumn->addChild($box);
        $this->nextUseChangingValidationSeed = $box;
// @endregion
        $frame->addChild($secondColumn);
        $frame->addChild($tooltip);

        $apply = $this->uiFactory->createButton("Apply", Button::TYPE_DECORATED);
        $apply->setAction(
            $this->actionFactory->createManialinkAction(
                $manialink,
                [$this, "callbackApply"],
                ["options" => $manialink->getData('options')],
                true
            )
        );
        $content = $manialink->getContentFrame();
        $apply->setPosition($content->getWidth() - $apply->getWidth(), -($content->getHeight() - $apply->getHeight()));
        $frame->addChild($apply);

        $manialink->addChild($frame);


    }

    public function callbackApply(ManialinkInterface $manialink, $login, $entries, $args)
    {
        /** @var ServerOptions $options */
        $options = $args['options'];

        foreach ($entries as $key => $value) {
            $array = explode("_", $key);
            settype($value, $array[1]);
            $options->{$array[0]} = $value;
        }
        try {
            $this->connection->setServerOptions($options);
            $this->connection->chatSendServerMessage("Done.");
            $this->closeManialink($manialink);

        } catch (\Exception $ex) {
            $this->console->write('$f00Error while setting server options: $fff'.$ex->getMessage());
        }

    }


    protected function updateContent(ManialinkInterface $manialink)
    {
        /** @var ServerOptions $options */
        $options = $manialink->getData('options');

        $this->serverName->setDefault($options->name);
        $this->comment->setDefault($options->comment);
        $this->password->setDefault($options->password);
        $this->specpassword->setDefault($options->passwordForSpectator);
        $this->refpassword->setDefault($options->refereePassword);
        $this->refereeMode->setSelectedIndex($options->refereeMode);
        $this->nextMaxSpectators->setDefault($options->nextMaxSpectators);
        $this->nextMaxPlayers->setDefault($options->nextMaxPlayers);
        $this->ladderServerLimitMax->setDefault($options->ladderServerLimitMax);
        $this->ladderServerLimitMin->setDefault($options->ladderServerLimitMin);
        $this->nextLadderMode->setSelectedIndex($options->nextLadderMode);
        $this->allowMapDownload->setChecked($options->allowMapDownload);
        $this->autoSaveValidationReplays->setChecked($options->autoSaveValidationReplays);
        $this->autoSaveReplays->setChecked($options->autoSaveReplays);
        $this->disableHorns->setChecked($options->disableHorns);
        $this->disableServiceAnnounces->setChecked($options->disableServiceAnnounces);
        $this->keepPlayerSlots->setChecked($options->keepPlayerSlots);
        $this->isP2PDownload->setChecked($options->isP2PDownload);
        $this->isP2PUpload->setChecked($options->isP2PUpload);


    }


}
