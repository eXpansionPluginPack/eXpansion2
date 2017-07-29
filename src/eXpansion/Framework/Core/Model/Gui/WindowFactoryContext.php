<?php


namespace eXpansion\Framework\Core\Model\Gui;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;


/**
 * Class WindowFactoryContext
 *
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WindowFactoryContext extends WidgetFactoryContext
{

    /** @var  ManiaScriptFactory */
    protected $windowManiaScriptFactory;

    /**
     * WindowFactoryContext constructor.
     *
     * @param                    $className
     * @param GuiHandler         $guiHandler
     * @param Factory            $groupFactory
     * @param ActionFactory      $actionFactory
     * @param Translations       $translations
     * @param ManiaScriptFactory $maniaScriptFactory
     */
    public function __construct(
        $className,
        GuiHandler $guiHandler,
        Factory $groupFactory,
        ActionFactory $actionFactory,
        Translations $translations,
        ManiaScriptFactory $maniaScriptFactory
    ) {
        parent::__construct($className, $guiHandler, $groupFactory, $actionFactory, $translations);

        $this->windowManiaScriptFactory = $maniaScriptFactory;
    }

    /**
     * @return ManiaScriptFactory
     */
    public function getWindowManiaScriptFactory()
    {
        return $this->windowManiaScriptFactory;
    }
}
