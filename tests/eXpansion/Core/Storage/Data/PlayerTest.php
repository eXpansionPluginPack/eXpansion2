<?php


namespace Tests\eXpansion\Core\Storage\Data;


use eXpansion\Core\Storage\Data\Player;


class PlayerTest extends \PHPUnit_Framework_TestCase
{

    public function testAllMethods()
    {
        $player = new Player();

        $reflect = new \ReflectionClass(Player::class);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
        $methods   = $reflect->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = array_reduce(
            $methods,
            function($methods, $method) {$methods[] = $method->getName(); return $methods;},
            []
        );

        // Put values in player object
        $values = [];
        foreach ($props as $prop)
        {
            $values[$prop->getName()] = "Value-" . $prop->getName();
        }
        $player->merge($values);

        // Check all getters;
        foreach ($props as $prop)
        {
            if (in_array('is' . ucfirst($prop->getName()), $methods)) {
                $method = 'is' . ucfirst($prop->getName());
                $this->assertEquals("Value-" . $prop->getName(), $player->$method());
            } elseif (in_array('get' . ucfirst($prop->getName()), $methods)) {
                $method = 'get' . ucfirst($prop->getName());
                $this->assertEquals("Value-" . $prop->getName(), $player->$method());
            }
        }
    }

    public function testMerge()
    {
        $player = new Player();
        $initialData = [
            'Login' => 'test',
            'SpectatorStatus' => 2
        ];
        $player->merge($initialData);

        $this->assertEquals('test', $player->getLogin());
        $this->assertEquals(2, $player->getSpectatorStatus());

        $data = [
            'SpectatorStatus' => 0
        ];
        $player->merge($data);
        $this->assertEquals(0, $player->getSpectatorStatus());

    }

}
