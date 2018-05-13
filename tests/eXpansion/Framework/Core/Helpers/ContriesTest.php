<?php


namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\Countries;
use Tests\eXpansion\Framework\Core\TestCore;


/**
 * Class ContriesTest
 *
 * @package Tests\eXpansion\Framework\Core\Helpers;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ContriesTest extends TestCore
{
    /**
     * Test that iso conversion works for all registered countries.
     */
    public function testIsoConversion() {
        $countryMappings = $this->container->getParameter("expansion.core.helper.countries.mapping");
        $countriesHelper = $this->container->get(Countries::class);

        foreach ($countryMappings as $countryName => $countryCode) {
            if ($countryCode != "OTH") {
                $this->assertNotEquals("OT", $countriesHelper->getIsoAlpha2FromName($countryName), "Country $countryName couln't be converted");
            }
        }
    }
}