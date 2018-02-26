<?php


namespace eXpansion\Framework\Config\Model;


/**
 * Class TextConfig
 *
 * @package eXpansion\Framework\Config\Model;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class TextConfig extends AbstractConfig
{
    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        return $this->get();
    }
}