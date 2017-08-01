<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Gui\Components\uiLabel;

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
        $label = new uiLabel($text, $type);

        if ($translate) {
            $label->setTranslate(true);
            $label->setTextId($text);
        } else {
            $label->setText($text);
        }

        return $label;
    }
}
