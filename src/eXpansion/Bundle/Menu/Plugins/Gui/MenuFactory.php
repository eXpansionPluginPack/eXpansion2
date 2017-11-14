<?php

namespace eXpansion\Bundle\Menu\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;


/**
 * Class MenuFactory
 *
 * @package eXpansion\Bundle\Menu\Plugins\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class MenuFactory extends WidgetFactory
{
    /** @var  MenuContentFactory */
    protected $menuContentFactory;

    /***
     * MenuFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WidgetFactoryContext $context
     * @param MenuContentFactory   $menuContentFactory
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        MenuContentFactory $menuContentFactory
    ){
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->menuContentFactory = $menuContentFactory;
    }

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $button = $this->uiFactory->createButton('expansion_menu.menu_open')
            ->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackShowMenu'], []));
        $button->setTranslate(true);
        $manialink->addChild($button);
    }

    /**
     * Callback to show the menu.
     *
     * @param $login
     */
    public function callbackShowMenu(ManialinkInterface $manialink, $login)
    {
        $this->menuContentFactory->create($login);
    }

}
