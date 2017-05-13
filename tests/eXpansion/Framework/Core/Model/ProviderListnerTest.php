<?php


namespace Tests\eXpansion\Framework\Core\Model;

use eXpansion\Framework\Core\Model\ProviderListner;
use Tests\eXpansion\Framework\Core\TestHelpers\ContainerDataTrait;


class ProviderListnerTest extends \PHPUnit_Framework_TestCase
{
    use ContainerDataTrait;

    public function testObject()
    {
        $providerListener = new ProviderListner('test-event', 'test-provider', 'onTest');
        $this->CheckSimpleSettersGetters($providerListener);
    }
}
