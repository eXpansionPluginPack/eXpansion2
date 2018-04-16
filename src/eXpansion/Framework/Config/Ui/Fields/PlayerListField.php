<?php

namespace eXpansion\Framework\Config\Ui\Fields;

/**
 * Class PlayerListField
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Framework\Config\Ui\Fields
 */
class PlayerListField extends TextListField
{
    /**
     * @inheritdoc
     */
    protected function getElementName($element)
    {
        if (!empty($element->getNickName())) {
            return "{$element->getNickname()} {$element->getLogin()}";
        }

        return $element->getLogin();
    }
}