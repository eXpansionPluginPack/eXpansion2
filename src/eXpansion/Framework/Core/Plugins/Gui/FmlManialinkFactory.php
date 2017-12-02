<?php


namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryContext;
use eXpansion\Framework\Gui\Ui\Factory as UiFactory;
use FML\Controls\Control;


/**
 * Class FmlManialinkFactory
 *
 * @package eXpansion\Framework\Core\Plugins\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class FmlManialinkFactory extends ManialinkFactory
{
    /** @var Translations */
    protected $translationsHelper;

    /** @var UiFactory  */
    protected $uiFactory;

    public function __construct($name, $sizeX, $sizeY, $posX = null, $posY = null, ManialinkFactoryContext $context)
    {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        // Hack for FML to use default MP alignements.
        Control::clearDefaultAlign();
    }


}