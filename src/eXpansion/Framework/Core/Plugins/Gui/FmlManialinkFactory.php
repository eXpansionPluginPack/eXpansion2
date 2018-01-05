<?php


namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryContext;
use eXpansion\Framework\Gui\Ui\Factory;
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

    /** @var Factory */
    protected $uiFactory;

    /**
     * FmlManialinkFactory constructor.
     * @param                         $name
     * @param                         $sizeX
     * @param                         $sizeY
     * @param null                    $posX
     * @param null                    $posY
     * @param ManialinkFactoryContext $context
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        ManialinkFactoryContext $context
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        // Hack for FML to use default MP alignements.
        Control::clearDefaultAlign();
        $this->uiFactory = $context->getUiFactory();
    }


}
