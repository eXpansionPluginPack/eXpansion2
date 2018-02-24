<?php


namespace eXpansion\Framework\Config\Model;

use eXpansion\Framework\Config\Exception\InvalidConfigException;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;


/**
 * Class Integer
 *
 * @package eXpansion\Framework\Config\Model;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class DecimalConfig extends AbstractConfig
{
    /** @var float Maximum possible value */
    protected $maxValue;

    /** @var float Minimum possible value */
    protected $minValue;

    /**
     * DecimalConfig constructor.
     *
     * @param string                 $path
     * @param string                 $scope
     * @param string                 $name
     * @param string                 $description
     * @param float                  $maxValue
     * @param float                  $minValue
     * @param float|int              $defaultValue
     * @param ConfigManagerInterface $configManager
     */
    public function __construct(
        string $path,
        string $scope,
        string $name,
        string $description,
        float $maxValue = PHP_INT_MAX,
        float $minValue = -PHP_INT_MAX,
        $defaultValue,
        ConfigManagerInterface $configManager
    ) {
        parent::__construct($path, $scope, $name, $description, $defaultValue, $configManager);

        $this->maxValue = $maxValue;
        $this->minValue = $minValue;
    }

    /**
     * @inheritdoc
     */
    public function validate(&$value)
    {
        parent::validate($value);

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            throw new InvalidConfigException(
                "Non numeric value set for configuration {$this->path}.",
                0,
                null,
                'expansion_config.error.decimal.not_decimal',
                ['%path%' => $this->path, '%value%' => $value]
            );
        }
        $value = (float)$value;

        if ($value > $this->maxValue) {
            throw new InvalidConfigException(
                "Max allowed value for configuration {$this->path}.",
                0,
                null,
                'expansion_config.error.decimal.max',
                ['%path%' => $this->path, '%value%' => $value, '%max%' => $this->maxValue]
            );
        }

        if ($value < $this->minValue) {
            throw new InvalidConfigException(
                "Min allowed value for configuration {$this->path}.",
                0,
                null,
                'expansion_config.error.decimal.min',
                ['%path%' => $this->path, '%value%' => $value, '%min%' => $this->minValue]
            );
        }
    }

}