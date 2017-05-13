<?php


namespace Tests\eXpansion\Framework\Core\TestHelpers;


trait ContainerDataTrait
{
    public function CheckSimpleSettersGetters($object, $ignore = []) {

        $reflect = new \ReflectionClass(get_class($object));
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
        $methods   = $reflect->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods = array_reduce(
            $methods,
            function($methods, $method) {$methods[] = $method->getName(); return $methods;},
            []
        );

        // Call all setters;
        foreach ($props as $prop)
        {
            foreach ($props as $prop)
            {
                if (!in_array($prop->getName(), $ignore) && in_array('set' . ucfirst($prop->getName()), $methods)) {
                    $method = 'set' . ucfirst($prop->getName());
                    $object->$method('value-' . $prop->getName());
                }
            }
        }

        // check all getters
        foreach ($props as $prop)
        {
            if (!in_array($prop->getName(), $ignore)) {
                if (in_array('get' . ucfirst($prop->getName()), $methods)) {
                    $method = 'get' . ucfirst($prop->getName());
                    $this->assertEquals('value-' . $prop->getName(), $object->$method(), $method);
                } elseif (in_array('is' . ucfirst($prop->getName()), $methods)) {
                    $method = 'is' . ucfirst($prop->getName());
                    $this->assertEquals('value-' . $prop->getName(), $object->$method(), $method);
                }
            }
        }

    }
}