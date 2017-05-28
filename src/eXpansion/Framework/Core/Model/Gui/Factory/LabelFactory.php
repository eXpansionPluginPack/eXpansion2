<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;
use FML\Controls\Label;

/**
 * Class LineBuilder
 *
 * @package eXpansion\Framework\Core\Model\Gui\Builders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class LabelFactory
{
    const TYPE_NORMAL = "normal";
    const TYPE_TITLE = 'title';

    public function create($text, $translate = false, $type = self::TYPE_NORMAL)
    {
        $label = new Label();
        $label->setTextSize(2)
            ->setHeight(4)
            ->setTranslate($translate);

        if ($translate) {
            $label->setTextId($text);
        } else {
            $label->setText($text);
        }

        if ($type == self::TYPE_TITLE) {
            $label->setTextFont('RajdhaniMono');
        }

        return $label;
    }
}
