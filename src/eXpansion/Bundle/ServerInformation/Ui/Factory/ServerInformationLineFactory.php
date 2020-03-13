<?php

namespace eXpansion\Bundle\ServerInformation\Ui\Factory;


use eXpansion\Framework\Gui\Components\Label;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;

class ServerInformationLineFactory
{
    /** @var Factory */
    protected $factory;

    /** @var float */
    protected $titleWidth;

    /** @var float */
    protected $dataWidth;

    /**
     * ServerInformationLineFactory constructor.
     * @param Factory $factory
     * @param string $class
     * @param float $titleWidth
     * @param float $dataWidth
     */
    public function __construct(Factory $factory, float $titleWidth, float $dataWidth)
    {
        $this->factory = $factory;
        $this->titleWidth = $titleWidth;
        $this->dataWidth = $dataWidth;
    }


    /**
     * Create a server information line.
     *
     * @param $title
     * @param $data
     * @return Frame
     */
    public function create($title, $data)
    {
        if (!is_array($data)) {
            $data = [$data];
        }
        $nbLine = count($data);

        $frame = new Frame();
        $frame->setWidth($this->titleWidth + $this->dataWidth + 1);
        $frame->setHeight(3.5 * $nbLine);

        $frame->addChild($this->factory->createLabel($title, Label::TYPE_TITLE)->setWidth($this->titleWidth)->setTranslate(true));

        foreach ($data as $i => $dataLine) {
            $frame->addChild(
                $this->factory->createLabel($dataLine, Label::TYPE_NORMAL)
                    ->setWidth($this->dataWidth)
                    ->setX($this->titleWidth + 1)
                    ->setY(-3.5 * $i)
            );
        }

        return $frame;
    }
}
