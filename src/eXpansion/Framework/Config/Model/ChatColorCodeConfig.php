<?php

namespace eXpansion\Framework\Config\Model;

/**
 * Class TextConfig
 *
 * @package eXpansion\Framework\Config\Model;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ChatColorCodeConfig extends TextConfig
{
    /**
     * @inheritdoc
     */
    public function get()
    {
        return '$z$s' . parent::get();
    }
}
