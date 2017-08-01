<?php
/**
 * File ObjectFilterTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\Core\Helpers\Data;

use eXpansion\Framework\Core\Helpers\Data\ObjectFilter;

class ObjectFilterTest extends \PHPUnit_Framework_TestCase
{

    public function testGetFieldValue()
    {
        $of = new ObjectFilter();
        $this->assertEquals($this->getTestValue(), $of->getFieldValue($this, 'getTestValue'));
    }

    public function getTestValue()
    {
        return 'Test toto';
    }
}
