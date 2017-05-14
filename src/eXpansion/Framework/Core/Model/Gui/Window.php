<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Exceptions\Gui\MissingCloseActionException;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Controls\Quads\Quad_Bgs1;
use FML\Controls\Quads\Quad_Bgs1InRace;
use FML\Elements\Format;
use FML\Types\Container;
use FML\Types\Renderable;

class Window extends Manialink implements Container
{
    /** @var \FML\ManiaLink  */
    protected $manialink;

    /** @var Label  */
    protected $closeButton;

    /** @var Frame  */
    protected $contentFrame;

    public function __construct(
        Group $group,
        ManiaScriptFactory $windowManiaScriptFactory,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($group, $name, $sizeX, $sizeY, $posX, $posY);

        $titleHeight = 5.5;
        $closeButtonWidth = 9.5;
        $titlebarColor = "3afe";

        // Manialink containing everything
        $this->manialink = new \FML\ManiaLink();
        $this->manialink->setId($this->getId())
                        ->setName($name)
                        ->setVersion(\FML\ManiaLink::VERSION_3);
        $windowFrame = new Frame('Window');
        $windowFrame->setPosition($posX, $posY);
        $this->manialink->addChild($windowFrame);

        // Title bar & title.
        $titleLabel = new Label();
        $titleLabel->setPosition(3, -$titleHeight/3 - 1)
            ->setAlign(Label::LEFT, Label::CENTER2)
            ->setText($name)
            ->setTextColor('fff')
            ->setTextSize(2)
            ->setTextFont('RajdhaniMono');
        $windowFrame->addChild($titleLabel);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX, 0.33)
            ->setAlign(null, null) // Fix issue with FML's default values.
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $windowFrame->addChild($titleBar);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX / 4, 0.5)
            ->setAlign(null, null) // Fix issue with FML's default values.
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $windowFrame->addChild($titleBar);

        $titleBar = new Quad('Title"');
        $titleBar->setSize($sizeX - $closeButtonWidth, $titleHeight)
            ->setAlign(null, null) // Fix issue with FML's default values.
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
            ->setAreaFocusColor($titlebarColor);
        $windowFrame->addChild($this->closeButton);

        //body
        $body = new Quad_Bgs1();
        $body->setSize($sizeX, $sizeY - $titleHeight)
            ->setAlign(null, null) // Fix issue with FML's default values.
            ->setPosition(0, -$titleHeight)
            ->setSubStyle(Quad_Bgs1::SUBSTYLE_BgWindow3);
        $windowFrame->addChild($body);

        $body = new Quad_Bgs1InRace();
        $body->setSize($sizeX + 10, $sizeY + 10)
            ->setAlign(null, null) // Fix issue with FML's default values.
            ->setPosition(-5, 5)
            ->setSubStyle(Quad_Bgs1InRace::SUBSTYLE_BgButtonShadow);
        $windowFrame->addChild($body);

        // Add maniascript for window handling.
        $this->manialink->addChild($windowManiaScriptFactory->createScript([
            ''
        ]));

        // Frame to handle the content of the window.
        $this->contentFrame = new Frame();
        $this->contentFrame->setPosition(2, -$titleHeight - 2);
        $windowFrame->addChild($this->contentFrame);

        // TODO put back maniascript to move windows.
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
        if (empty($this->closeButton->getDataAttribute('action')))
        {
            throw new MissingCloseActionException("Close action is missing for window. Check if you are using the proper factory.");
        }

        echo $this->manialink->__toString();

        return $this->manialink->__toString();
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
    public function setFormat(Format $format = null) {
        return $this->contentFrame->setFormat($format);
    }
}
