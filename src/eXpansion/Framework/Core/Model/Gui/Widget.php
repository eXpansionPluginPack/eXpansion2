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

class Widget extends Manialink implements Container
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

    /** @var Frame */
    protected $windowFrame;

    public function __construct(
        Group $group,
        Translations $translationHelper,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($group, $name, $sizeX, $sizeY, $posX, $posY);

        $this->translationHelper = $translationHelper;

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
        $this->contentFrame->setPosition(0, 0);
        $this->contentFrame->setSize($sizeX, $sizeY);
        $windowFrame->addChild($this->contentFrame);

        $this->windowFrame = $windowFrame;
    }

    /**
     * @inheritdoc
     */
    public function getXml()
    {
        $this->addDictionaryInformation();

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
                $this->dictionary->setEntry($message['Lang'], $msgId, htmlspecialchars($message['Text']));
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
                $textId = 'exp_'.md5($child->getTextId());
                $translations[$textId] = $this->translationHelper->getTranslations($child->getTextId(), []);

                // Replaces with text id that can be used in the xml.
                $child->setTextId($textId);
            } else {
                if ($child instanceof Frame) {
                    $this->getDictionaryInformation($child, $translations);
                }
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
