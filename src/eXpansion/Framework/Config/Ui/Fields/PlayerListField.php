<?php

namespace eXpansion\Framework\Config\Ui\Fields;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Model\PlayerListConfig;

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

    /**
     * @inheritdoc
     */
    public function isCompatible(ConfigInterface $config): bool
    {
        return parent::isCompatible($config) && $config instanceof PlayerListConfig;
    }
}