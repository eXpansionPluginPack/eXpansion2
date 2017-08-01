<?php

namespace eXpansion\Framework\Core\Helpers\JobRunner;

use oliverde8\AsynchronousJobs\Job;
use oliverde8\AsynchronousJobs\Job\CallbackCurl;
use oliverde8\AsynchronousJobs\JobRunner;


/**
 * Class Factory
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package Tests\eXpansion\Framework\Core\Helpers\JobRunner
 */
class Factory
{
    /**
     * @return JobRunner
     */
    public function getJobRunner()
    {
        return JobRunner::getInstance('expansion', 'php', 'var/tmp/asnychronous');
    }

    /**
     * @param $url
     * @param $callback
     * @param null $additionalData
     * @param array $options
     *
     * @return CallbackCurl
     */
    public function createCurlJob($url, $callback, $additionalData = null, $options = [])
    {
        $curlJob = new CallbackCurl();
        $curlJob->setCallback($callback);
        $curlJob->setUrl($url);
        $curlJob->setOptions($options);
        $curlJob->__additionalData = $additionalData;

        return $curlJob;
    }

    /**
     * @param Job $job
     */
    public function startJob(Job $job)
    {
        $job->start();
    }


    /**
     * On each loop check for finished jobs.
     */
    public function onExpansionPostLoop()
    {
        $this->getJobRunner()->proccess();
    }
}
