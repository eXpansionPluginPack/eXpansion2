<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\GuiHandlerInterface;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;

/**
 * Class ManialinkFactoryContext
 *
 * @package eXpansion\Framework\Core\Model\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ManialinkFactoryContext
{
    /** @var  string */
    protected $className;

    /** @var  GuiHandlerInterface */
    protected $guiHandler;

    /** @var Factory */
    protected $groupFactory;

    /** @var ActionFactory */
    protected $actionFactory;

    /**
     * ManialinkFactoryContext constructor.
     *
     * @param GuiHandlerInterface $guiHandler
     * @param Factory             $groupFactory
     * @param ActionFactory       $actionFactory
     */
    public function __construct($className, GuiHandlerInterface $guiHandler, Factory $groupFactory, ActionFactory $actionFactory)
    {
        $this->className = $className;
        $this->guiHandler = $guiHandler;
        $this->groupFactory = $groupFactory;
        $this->actionFactory = $actionFactory;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return GuiHandlerInterface
     */
    public function getGuiHandler()
    {
        return $this->guiHandler;
    }

    /**
     * @return Factory
     */
    public function getGroupFactory()
    {
        return $this->groupFactory;
    }

    /**
     * @return ActionFactory
     */
    public function getActionFactory()
    {
        return $this->actionFactory;
    }
}