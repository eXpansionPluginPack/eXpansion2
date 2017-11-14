<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 14/05/2017
 * Time: 13:22
 */

namespace Tests\eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\Gui\ManiaScript;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use Tests\eXpansion\Framework\Core\TestCore;

class ManiaScriptFactoryTest extends TestCore
{

    public function testManiaScriptFactory()
    {
        /** @var ManiaScriptFactory $factory */
        $factory = $this->container->get('expansion.framework.core.mania_script.window');
        $script = $factory->createScript([]);

        $this->assertInstanceOf(ManiaScript::class, $script);

        $script->render(new \DOMDocument());
        $script->getVarN('toto');
    }

}
