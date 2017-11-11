<?php

namespace eXpansion\Framework\Core\Helpers\JobRunner;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Structures\HttpRequest;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use oliverde8\AsynchronousJobs\Job;
use oliverde8\AsynchronousJobs\Job\CallbackCurl;
use oliverde8\AsynchronousJobs\JobRunner;


/**
 * Class Factory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package Tests\eXpansion\Framework\Core\Helpers\JobRunner
 */
class Factory implements ListenerInterfaceExpTimer
{
    /**
     * @return JobRunner
     */
    public function getJobRunner()
    {
        return JobRunner::getInstance('expansion', PHP_BINARY, 'var/tmp/asynchronous');
    }

    /**
     * @param $url
     * @param $callback
     * @param null|mixed $additionalData
     * @param array $options
     *
     * @param array|\stdClass $parameters one dimensional array or \stdClass with post key-value pairs
     * @return CallbackCurl
     */
    public function createCurlJob($url, $callback, $additionalData = null, $options = [], $parameters = [])
    {
        $curlJob = new HttpRequest();
        $curlJob->setCallback($callback);
        $curlJob->setUrl($url);
        $curlJob->setOptions($options);
        if (is_object($parameters)) {
            $parameters = (array)$parameters;
        }
        $curlJob->setParameters($parameters);
        $curlJob->setAdditionalData($additionalData);

        return $curlJob;
    }

    /**
     * @param Job $job
     */
    public function startJob(Job $job)
    {
        $job->start();
    }


    public function onPreLoop()
    {

    }

    public function onPostLoop()
    {
        $this->getJobRunner()->proccess();
    }

    public function onEverySecond()
    {

    }
}
