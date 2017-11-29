<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Elements\Dico;
use FML\Elements\Format;
use FML\Script\Features\ToggleInterface;
use FML\Types\Container;
use FML\Types\Renderable;

class Widget extends Manialink implements Container
{
    /** @var  string */
    protected $scriptData;

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

    /** @var array[] */
    protected $cachedMessages = [];

    /**
     * Widget constructor.
     *
     * @param ManialinkFactoryInterface $manialinkFactory
     * @param Group $group
     * @param Translations $translationHelper
     * @param int $name
     * @param int $sizeX
     * @param float|null $sizeY
     * @param null $posX
     * @param null $posY
     */
    public function __construct(
        ManialinkFactoryInterface $manialinkFactory,
        Group $group,
        Translations $translationHelper,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($manialinkFactory, $group, $name, $sizeX, $sizeY, $posX, $posY);

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

        $toggleInterfaceF9 = new ToggleInterface($windowFrame, "F9");
        $this->getFmlManialink()->getScript()
            ->addFeature($toggleInterfaceF9);

        $this->windowFrame = $windowFrame;
    }

    /**
     * @return \FML\ManiaLink
     */
    public function getFmlManialink()
    {
        return $this->manialink;
    }

    /**
     * sets scripts data
     *
     * @param ManiaScript $script
     */
    public function setScriptData(ManiaScript $script)
    {
        $this->scriptData = $script->__toString();
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
        $translations = [];
        $this->dictionary->removeAllEntries();
        $this->getDictionaryInformation($this->manialink, $translations);

        foreach ($translations as $msgId => $messages) {
            foreach ($messages as $message) {
                $this->dictionary->setEntry($message['Lang'], $msgId, htmlspecialchars($message['Text']));
            }
        }
    }

    /**
     * Recursive search all dome tree in order to find all translatable labels.
     *
     * @param Container|\FML\ManiaLink $control
     * @param $translations
     */
    protected function getDictionaryInformation($control, &$translations)
    {
        foreach ($control->getChildren() as $child) {
            if (($child instanceof Label || $child instanceof uiLabel) && $child->getTranslate()) {
                $id = $child->getTextId();

                if (!isset($this->cachedMessages[$id])) {
                    $textId = 'exp_'.md5($id);

                    $messages = $this->translationHelper->getTranslations($child->getTextId(), []);
                    $translations[$textId] = $messages;
                    $this->cachedMessages[$textId] = $messages;

                    // Replaces with text id that can be used in the xml.
                    $child->setTextId($textId);
                } else {
                    $translations[$id] = $this->cachedMessages[$id];
                }
            } else {
                if ($child instanceof Container) {
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
     *
     */
    public function setFormat(Format $format = null)
    {
        $this->contentFrame->setFormat($format);

        return $this;
    }

    /**
     * @return Frame
     */
    public function getContentFrame()
    {
        return $this->contentFrame;
    }
}
