<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\GuiHandlerInterface;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use \eXpansion\Framework\Gui\Ui\Factory as UiFactory;

/**
 * Class WindowFactoryContext
 *
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WindowFactoryContext extends WidgetFactoryContext
{

    /** @var  WindowFrameFactory */
    protected $windowFrameFactory;

    /**
     * WindowFactoryContext constructor.
     *
     * @param $className
     * @param GuiHandler $guiHandler
     * @param Factory $groupFactory
     * @param ActionFactory $actionFactory
     * @param Translations $translations
     * @param UiFactory $uiFactory
     * @param WindowFrameFactory $windowFrameFactory
     */
    public function __construct(
        $className,
        GuiHandler $guiHandler,
        Factory $groupFactory,
        ActionFactory $actionFactory,
        Translations $translations,
        UiFactory $uiFactory,
        WindowFrameFactory $windowFrameFactory
    ) {

        parent::__construct($className, $guiHandler, $groupFactory, $actionFactory, $translations, $uiFactory);

        $this->windowFrameFactory = $windowFrameFactory;
    }

    /**
     * @return WindowFrameFactory
     */
    public function getWindowFrameFactory()
    {
        return $this->windowFrameFactory;
    }
}
