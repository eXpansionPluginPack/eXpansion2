<?php

namespace eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\JobRunner\Factory;
use oliverde8\AsynchronousJobs\JobRunner;

/**
 * Class Http
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Helpers
 */
class Http {

    /** @var Factory */
    protected $factory;

    /**
     * Http constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Make a http query.
     *
     * @param string $url
     * @param callable $callback
     * @param null|mixed $additionalData If you need to pass additional metadata.
     *                                   You will get this back in the callback.
     * @param array $options curl options array
     */
    public function call($url, $callback, $additionalData = null, $options = [])
    {
        $curlJob = $this->factory->createCurlJob($url, $callback, $additionalData, $options);

        // Start job execution.
        $this->factory->startJob($curlJob);
    }
}
