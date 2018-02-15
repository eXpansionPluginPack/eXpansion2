<?php

namespace eXpansion\Framework\Core\Model\Gui\Script;

/**
 * Class Variable
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Core\Model\Gui\Script
 */
class Variable
{
    /** @var string */
    protected $variableName;

    /** @var string */
    protected $type;

    /** @var string */
    protected $for = '';

    /** @var bool  */
    protected $isUnique = false;

    /** @var mixed */
    protected $value;

    /**
     * Variable constructor.
     *
     * @param string $variableName
     * @param string $type
     * @param null|string $for
     * @param bool $isUnique
     */
    public function __construct(string $variableName, string $type, string $for = '', string $value, bool $isUnique = false)
    {
        $this->variableName = $variableName;
        $this->type = $type;
        $this->for = $for;
        $this->isUnique = $isUnique;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        $varName = $this->variableName;
        if ($this->isUnique()) {
            $varName = spl_object_hash($this) . "_" . $varName;
        }

        return $varName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getFor(): string
    {
        return $this->for;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->isUnique;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get variable declaration script.
     *
     * @return string
     */
    public function getScriptDeclaration()
    {
        $varName = $this->getVariableName();
        $for = !empty($this->for) ? "for $this->for" : "";
        return "declare $this->type $varName $for = $this->type;";
    }

    /**
     * Get script to update variable value.
     *
     * @return string
     */
    public function getScriptValueSet()
    {
        $varName = $this->getVariableName();
        return "$varName = $this->value;";
    }
}
