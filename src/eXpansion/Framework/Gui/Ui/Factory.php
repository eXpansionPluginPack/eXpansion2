<?php

namespace eXpansion\Framework\Gui\Ui;

use eXpansion\Framework\Core\Exceptions\UnknownMethodException;

/**
 * Class Factory
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Gui\Ui
 */
class Factory
{
    /**
     * @var string[]
     */
    protected $classes;

    /**
     * Factory constructor.
     *
     * @param $classes
     */
    public function __construct($classes)
    {
        $this->classes = $classes;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws UnknownMethodException
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'create') === 0) {
            $name = str_replace('create', '', $name);

            if (isset($this->classes[$name])) {
                $class = $this->classes[$name];
                return new $class(...$arguments);
            }
        }

        throw new UnknownMethodException("$name is an unknown method");
    }
}
