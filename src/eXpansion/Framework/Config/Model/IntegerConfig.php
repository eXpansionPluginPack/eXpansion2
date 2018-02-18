<?php


namespace eXpansion\Framework\Config\Model;
use eXpansion\Framework\Config\Exception\InvalidConfigException;
use eXpansion\Framework\Config\Services\ConfigManager;


/**
 * Class Integer
 *
 * @package eXpansion\Framework\Config\Model;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class IntegerConfig extends DecimalConfig
{
    /**
     * @inheritdoc
     */
    public function validate(&$value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw new InvalidConfigException(
                "Non Integer value set for configuration {$this->path}.",
                0,
                null,
                'expansion_config.error.int.not_int',
                ['%path%' => $this->path, '%value%' => $value]
            );
        }

        parent::validate($value);
        $value = (int)$value;
    }
}