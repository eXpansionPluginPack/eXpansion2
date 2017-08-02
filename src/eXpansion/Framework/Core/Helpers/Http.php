<?php

namespace eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\JobRunner\Factory;
use oliverde8\AsynchronousJobs\Job\CallbackCurl;
use oliverde8\AsynchronousJobs\JobRunner;

/**
 * Class Http
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Helpers
 */
class Http
{

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

    /**
     * Make a get http query.
     *
     * @param string $url address
     * @param callable $callback callback
     * @param null|mixed $additionalData If you need to pass additional metadata.
     *                                          You will get this back in the callback.
     * @param array $options Single dimensional array of curl_setopt key->values
     */
    public function get($url, callable $callback, $additionalData = null, $options = [])
    {
        $options[CURLOPT_FOLLOWLOCATION] = true;
        $additionalData['_callback'] = $callback;

        $this->call($url, [$this, 'process'], $additionalData, $options);
    }

    /**
     * Make a post http query.
     *
     * @param string $url address
     * @param array $postFields array<string, string>
     * @param callable $callback callback with returning datas
     * @param null|mixed $additionalData If you need to pass additional metadata.
     *                                   You will get this back in the callback.
     * @param array $options Single dimensional array of curl_setopt key->values
     */
    public function post($url, $postFields, callable $callback, $additionalData = null, $options = [])
    {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_FOLLOWLOCATION] = true;

        $query = '';
        if (!empty($postFields)) {
            $query = '?'.http_build_query($postFields);
        }
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_POSTFIELDS] = $query;

        $additionalData['_callback'] = $callback;

        $curlJob = $this->factory->createCurlJob($url, [$this, 'process'], $additionalData, $options, $postFields);

        // Start job execution.
        $this->factory->startJob($curlJob);
    }

    public function process(CallbackCurl $curl)
    {

        $curl->getData();
        $curl->getCurlInfo();
        $curl->getCurlError();

        print_r($curl->__additionalData);

    }


}
