<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Exceptions\Gui\MissingCloseActionException;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Controls\Quads\Quad_Bgs1;
use FML\Controls\Quads\Quad_Bgs1InRace;
use FML\Elements\Dico;
use FML\Elements\Format;
use FML\Types\Container;
use FML\Types\Renderable;

class Window extends Manialink implements Container
{
    /** @var Translations */
    protected $translationHelper;

    /** @var \FML\ManiaLink */
    protected $manialink;

    /** @var Dico */
    protected $dictionary;

    /** @var Label */
    protected $closeButton;

    /** @var Frame */
    protected $contentFrame;

    public function __construct(
        Group $group,
        ManiaScriptFactory $windowManiaScriptFactory,
        Translations $translationHelper,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    )
    {
        parent::__construct($group, $name, $sizeX, $sizeY, $posX, $posY);

        $this->translationHelper = $translationHelper;

        $titleHeight = 5.5;
        $closeButtonWidth = 9.5;
        $titlebarColor = "3afe";

        // Manialink containing everything
        $this->manialink = new \FML\ManiaLink();
        $this->manialink->setId($this->getId())
            ->setName($name)
            ->setVersion(\FML\ManiaLink::VERSION_3);

        $this->dictionary = new Dico();
        $this->manialink->setDico($this->dictionary);

        $windowFrame = new Frame('Window');
        $windowFrame->setPosition($posX, $posY);
        $this->manialink->addChild($windowFrame);

        // Frame to handle the content of the window.
        $this->contentFrame = new Frame();
        $this->contentFrame->setPosition(2, -$titleHeight - 2);
        $this->contentFrame->setSize($sizeX - 4, $sizeY - $titleHeight - 4);
        $windowFrame->addChild($this->contentFrame);

        // Title bar & title.
        $titleLabel = new Label();
        $titleLabel->setPosition(3, -$titleHeight / 3 - 1)
            ->setAlign(Label::LEFT, Label::CENTER2)
            ->setTextId($name)
            ->setTextColor('fff')
            ->setTextSize(2)
            ->setTranslate(true)
            ->setTextFont('RajdhaniMono')
            ->setId("TitleText");
        $windowFrame->addChild($titleLabel);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX, 0.33)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $windowFrame->addChild($titleBar);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX / 4, 0.5)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $windowFrame->addChild($titleBar);

        $titleBar = new Quad('Title');
        $titleBar->setSize($sizeX - $closeButtonWidth, $titleHeight)
            ->setBackgroundColor($titlebarColor)
            ->setScriptEvents(true);
        $windowFrame->addChild($titleBar);

        $this->closeButton = new Label('Close');
        $this->closeButton->setSize($closeButtonWidth, $titleHeight)
            ->setPosition($sizeX - $closeButtonWidth + ($closeButtonWidth / 2), -$titleHeight / 2)
            ->setAlign(Label::CENTER, Label::CENTER2)
            ->setText("✖")
            ->setTextColor('fff')
            ->setTextSize(2)
            ->setTextFont('OswaldMono')
            ->setScriptEvents(true)
            ->setAreaColor('d00')
            ->setAreaFocusColor('f22');
        $windowFrame->addChild($this->closeButton);

        //body
        $body = new Quad_Bgs1();
        $body->setSize($sizeX, $sizeY - $titleHeight)
            ->setPosition(0, -$titleHeight)
            ->setSubStyle(Quad_Bgs1::SUBSTYLE_BgWindow3)
            ->setId('WindowBg')
            ->setScriptEvents(true);
        $windowFrame->addChild($body);

        $body = new Quad_Bgs1InRace();
        $body->setSize($sizeX + 10, $sizeY + 10)
            ->setPosition(-5, 5)
            ->setSubStyle(Quad_Bgs1InRace::SUBSTYLE_BgButtonShadow);
        $windowFrame->addChild($body);

        // Add maniascript for window handling.
        $this->manialink->addChild($windowManiaScriptFactory->createScript(['']));
    }

    /**
     * Set action to close the window.
     *
     * @param $actionId
     */
    public function setCloseAction($actionId)
    {
        $this->closeButton->setDataAttributes(['action' => $actionId]);
    }

    /**
     * @inheritdoc
     */
    public function getXml()
    {
        $this->addDictionaryInformation();

        if (empty($this->closeButton->getDataAttribute('action'))) {
            throw new MissingCloseActionException("Close action is missing for window. Check if you are using the proper factory.");
        }

        return $this->manialink->__toString();
    }

    /**
     * Add translations to dictionary.
     */
    protected function addDictionaryInformation()
    {
        $translations = array();
        $this->getDictionaryInformation($this->manialink, $translations);
        $this->dictionary->removeAllEntries();

        foreach ($translations as $msgId => $messages) {
            foreach ($messages as $message) {
                $this->dictionary->setEntry($message['Lang'], $msgId, htmlspecialchars ($message['Text']));
            }
        }
    }

    /**
     * Recursive search all dome tree in order to find all translatable labels.
     *
     * @param $frame
     * @param $translations
     */
    protected function getDictionaryInformation($frame, &$translations)
    {
        foreach ($frame->getChildren() as $child) {
            if ($child instanceof Label && $child->getTranslate()) {
                $textId = 'exp_' . md5($child->getTextId());
                $translations[$textId] = $this->translationHelper->getTranslations($child->getTextId(), []);

                // Replaces with text id that can be used in the xml.
                $child->setTextId($textId);
            } else if ($child instanceof Frame) {
                $this->getDictionaryInformation($child, $translations);
            }
        }
    }

    /**
     * Get the children
     *
     * @api
     * @return Renderable[]
     */
    public function getChildren()
    {
        return $this->contentFrame->getChildren();
    }

    /**
     * Add a new child
     *
     * @api
     *
     * @param Renderable $child Child Control to add
     *
     * @return static
     */
    public function addChild(Renderable $child)
    {
        $this->contentFrame->addChild($child);

        return $this;
    }

    /**
     * Add a new child
     *
     * @api
     *
     * @param Renderable $child Child Control to add
     *
     * @return static
     * @deprecated Use addChild()
     * @see        Container::addChild()
     */
    public function add(Renderable $child)
    {
        $this->contentFrame->addChild($child);

        return $this;
    }

    /**
     * Add new children
     *
     * @api
     *
     * @param Renderable[] $children Child Controls to add
     *
     * @return static
     */
    public function addChildren(array $children)
    {
        $this->contentFrame->addChildren($children);

        return $this;
    }

    /**
     * Remove all children
     *
     * @api
     * @return static
     */
    public function removeAllChildren()
    {
        $this->contentFrame->removeAllChildren();

        return $this;
    }

    /**
     * Remove all children
     *
     * @api
     * @return static
     * @deprecated Use removeAllChildren()
     * @see        Container::removeAllChildren()
     */
    public function removeChildren()
    {
        $this->contentFrame->removeAllChildren();

        return $this;
    }

    /**
     * Get the Format
     *
     * @api
     *
     * @param bool $createIfEmpty If the format should be created if it doesn't exist yet
     *
     * @return Format
     * @deprecated Use Style
     * @see        Style
     */
    public function getFormat($createIfEmpty = true)
    {
        return $this->contentFrame->getFormat($createIfEmpty);
    }

    /**
     * Set the Format
     *
     * @api
     *
     * @param Format $format New Format
     *
     * @return static
     * @deprecated Use Style
     * @see        Style
     */
    public function setFormat(Format $format = null)
    {
        return $this->contentFrame->setFormat($format);
    }

    /**
     * @return Frame
     */
    public function getContentFrame()
    {
        return $this->contentFrame;
    }
}
