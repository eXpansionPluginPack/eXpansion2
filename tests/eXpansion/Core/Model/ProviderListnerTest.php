<?php


namespace Tests\eXpansion\Core\Model;

use eXpansion\Core\Model\ProviderListner;
use Tests\eXpansion\Core\TestHelpers\ContainerDataTrait;


class ProviderListnerTest extends \PHPUnit_Framework_TestCase
{
    use ContainerDataTrait;

    public function testObject()
    {
        $providerListener = new ProviderListner('test-event', 'test-provider', 'onTest');
        $this->CheckSimpleSettersGetters($providerListener);
    }
}
