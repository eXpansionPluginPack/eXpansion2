<?php


namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\JobRunner\Factory;
use oliverde8\AsynchronousJobs\Job;


/**
 * Class Http
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package Tests\eXpansion\Framework\Core\Helpers
 */
class HttpTest  extends \PHPUnit_Framework_TestCase
{

    public function testHttp()
    {
        $curlMock = $this->getMockBuilder(Job::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factoryMock = $this->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factoryMock->expects($this->once())->method('createCurlJob')
            ->with('url', 'callback', null, ['test' => 'val'])
            ->willReturn($curlMock);
        $factoryMock->expects($this->once())->method('startJob');

        $http = new Http($factoryMock);
        $http->call('url', 'callback', null, ['test' => 'val']);
    }
}
