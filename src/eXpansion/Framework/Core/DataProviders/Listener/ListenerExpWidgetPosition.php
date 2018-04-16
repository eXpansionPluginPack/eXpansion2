<?php


namespace eXpansion\Framework\Core\DataProviders\Listener;


/**
 * Interface ListenerExpWidgetPosition
 *
 * @package eXpansion\Framework\Core\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface ListenerExpWidgetPosition
{
    /**
     * Update options and position of a widget.
     *
     * @param float $posX
     * @param float $posY
     * @param array $options
     *
     * @return mixed
     */
    public function updateOptions($posX, $posY, $options);
}