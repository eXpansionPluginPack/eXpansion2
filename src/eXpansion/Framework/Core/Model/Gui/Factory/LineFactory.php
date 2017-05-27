<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;
use FML\Controls\Frame;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class LineBuilder
 *
 * @package eXpansion\Framework\Core\Model\Gui\Builders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class LineFactory
{
    /** @var BackGroundFactory */
    protected $backGroundFactory;

    /** @var LabelFactory */
    protected $labelFactory;

    /** @var string */
    protected $type;

    /**
     * TitleLineFactory constructor.
     *
     * @param BackGroundFactory $backGroundFactory
     * @param LabelFactory      $labelFactory
     * @param string            $type
     */
    public function __construct(
        BackGroundFactory $backGroundFactory,
        LabelFactory $labelFactory,
        $type = LabelFactory::TYPE_NORMAL
    )
    {
        $this->backGroundFactory = $backGroundFactory;
        $this->labelFactory = $labelFactory;
        $this->type = $type;
    }

    /**
     * TODO Handle more options such as pregenerated buttons and such instead of text.
     *
     * @param $totalWidth
     * @param $titles
     *
     * @return Frame
     */
    public function create($totalWidth, $titles, $index = 0)
    {
        $totalCoef
            = ($totalWidth - 1) / array_reduce($titles, function($carry, $item){return $carry + $item['width'];});

        $frame = new Frame();
        $frame->setHeight(5);
        $frame->setWidth($totalWidth);

        $postX = 1;
        foreach ($titles as $title)
        {
            $label = $this->labelFactory->create(
                $title['text'],
                AssociativeArray::getFromKey($title, 'translatable', false),
                $this->type
            );
            $label->setWidth(($title["width"] * $totalCoef) - 0.5);
            $label->setPosition($postX, -0.5);
            $frame->addChild($label);

            $postX += $title["width"] * $totalCoef;
        }

        $frame->addChild($this->backGroundFactory->create($totalWidth, 5, $index));
        return $frame;
    }
}