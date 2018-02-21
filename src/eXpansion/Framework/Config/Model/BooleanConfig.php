<?php


namespace eXpansion\Framework\Config\Model;

use eXpansion\Framework\Config\Exception\InvalidConfigException;


/**
 * Class Integer
 *
 * @package eXpansion\Framework\Config\Model;
 * @author  reaby
 */
class BooleanConfig extends AbstractConfig
{
    /**
     * @inheritdoc
     */
    public function validate(&$value)
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === null) {
            throw new InvalidConfigException(
                "Non boolean value set for configuration {$this->path}.",
                0,
                null,
                'expansion_config.error.bool.not_bool',
                ['%path%' => $this->path, '%value%' => $value]
            );
        }

        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * @return boolean
     */
    public function getRawValue()
    {
        return parent::getRawValue();
    }
}