<?php


namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WidgetFrameFactoryInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\GuiHandlerInterface;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use \eXpansion\Framework\Gui\Ui\Factory as UiFactory;

/**
 * Class WidgetFactoryContext
 *
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WidgetFactoryContext extends FmlManialinkFactoryContext
{

    /** @var WidgetFrameFactoryInterface */
    protected $widgetFrameFactory;

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
        UiFactory $uiFactory,
        WidgetFrameFactoryInterface $widgetFrameFactory
    ) {
        parent::__construct($className, $guiHandler, $groupFactory, $actionFactory, $translations, $uiFactory);

        $this->widgetFrameFactory = $widgetFrameFactory;
    }

    /**
     * @return WidgetFrameFactoryInterface
     */
    public function getWidgetFrameFactory(): WidgetFrameFactoryInterface
    {
        return $this->widgetFrameFactory;
    }
}
