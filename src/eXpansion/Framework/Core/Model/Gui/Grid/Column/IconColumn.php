<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid\Column;


/**
 * Class IconColumn
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  reaby
 */
class IconColumn extends AbstractColumn
{

    /**
     * IconColumn constructor.
     *
     * @param string $key
     * @param string $name
     * @param float  $width
     */
    public function __construct($key, $name, $width)
    {
        parent::__construct($key, $name, $width);
    }
}
