<?php

namespace eXpansion\Framework\Core\Helpers\JobRunner;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Structures\HttpRequest;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Services\Console;
use oliverde8\AsynchronousJobs\Job;
use oliverde8\AsynchronousJobs\Job\CallbackCurl;
use oliverde8\AsynchronousJobs\JobRunner;
use Psr\Log\LoggerInterface;


/**
 * Class Factory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package Tests\eXpansion\Framework\Core\Helpers\JobRunner
 */
class Factory implements ListenerInterfaceExpTimer
{


    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Console
     */
    private $console;

    public function __construct(LoggerInterface $logger, Console $console)
    {

        $this->logger = $logger;
        $this->console = $console;
    }

    /**
     * @return JobRunner
     */
    public function getJobRunner()
    {
        try {
            return JobRunner::getInstance('expansion', PHP_BINARY, 'var/tmp/asynchronous/');
        } catch (\Exception $ex) {
            $this->console->writeln('PHP exec is not enabled, therefore all http transport is disabled!');
            $this->logger->critical('PHP exec is not enabled, therefore all http transport is disabled!');
            exit(1);
        }
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
