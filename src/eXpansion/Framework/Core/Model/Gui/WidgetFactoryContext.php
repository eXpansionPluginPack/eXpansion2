<?php


namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\GuiHandlerInterface;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use \eXpansion\Framework\Gui\Ui\Factory as UiFactory;


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

    /** @var UiFactory  */
    protected $uiFactory;

    /**
     * WidgetFactoryContext constructor.
     *
     * @param $className
     * @param GuiHandlerInterface $guiHandler
     * @param Factory $groupFactory
     * @param ActionFactory $actionFactory
     * @param Translations $translations
     * @param UiFactory $uiFactory
     */
    public function __construct(
        $className,
        GuiHandlerInterface $guiHandler,
        Factory $groupFactory,
        ActionFactory $actionFactory,
        Translations $translations,
        UiFactory $uiFactory
    ) {
        parent::__construct($className, $guiHandler, $groupFactory, $actionFactory);

        $this->translationsHelper = $translations;
        $this->uiFactory = $uiFactory;
    }

    /**
     * @return Translations
     */
    public function getTranslationsHelper()
    {
        return $this->translationsHelper;
    }

    /**
     * @return UiFactory
     */
    public function getUiFactory()
    {
        return $this->uiFactory;
    }
}
