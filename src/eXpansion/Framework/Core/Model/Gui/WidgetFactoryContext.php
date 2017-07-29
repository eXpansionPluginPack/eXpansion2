<?php


namespace eXpansion\Framework\Core\Model\Gui;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;


/**
 * Class WidgetFactoryContext
 *
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WidgetFactoryContext extends ManialinkFactoryContext
{
    /** @var Translations */
    protected $translationsHelper;

    /**
     * WidgetFactoryContext constructor.
     *
     * @param               $className
     * @param GuiHandler    $guiHandler
     * @param Factory       $groupFactory
     * @param ActionFactory $actionFactory
     * @param Translations  $translations
     */
    public function __construct(
        $className,
        GuiHandler $guiHandler,
        Factory $groupFactory,
        ActionFactory $actionFactory,
        Translations $translations)
    {
        parent::__construct($className, $guiHandler, $groupFactory, $actionFactory);

        $this->translationsHelper = $translations;
    }

    /**
     * @return Translations
     */
    public function getTranslationsHelper()
    {
        return $this->translationsHelper;
    }
}