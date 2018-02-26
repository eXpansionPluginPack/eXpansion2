<?php


namespace eXpansion\Framework\Config\Model;


/**
 * Class TextConfig
 *
 * @package eXpansion\Framework\Config\Model;
 * @author oliverde8
 */
class TextListConfig extends TextConfig
{
    public function __toString(): string
    {
        return implode(',', $this->get());
    }
}
