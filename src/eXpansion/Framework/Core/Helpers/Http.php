<?php

namespace eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\JobRunner\Factory;
use eXpansion\Framework\Core\Helpers\Structures\HttpRequest;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;

/**
 * Class Http
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
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

        $defaultOptions = [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => "eXpansionPluginPack v ".AbstractApplication::EXPANSION_VERSION,
        ];

        $options = $options + $defaultOptions;
        $additionalData['callback'] = $callback;

        $this->call($url, [$this, 'process'], $additionalData, $options);
    }

    /**
     * Make a post http query.
     *
     * @param string $url address
     * @param string|array $postFields
     * @param callable $callback callback with returning datas
     * @param null|mixed $additionalData If you need to pass additional metadata.
     *                                   You will get this back in the callback.
     * @param array $options Single dimensional array of curl_setopt key->values
     */
    public function post($url, $postFields, callable $callback, $additionalData = null, $options = [])
    {
        $defaultOptions = [
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => "eXpansionPluginPack v ".AbstractApplication::EXPANSION_VERSION,
        ];

        $options = $options + $defaultOptions;

        if (is_array($postFields)) {
            $query = http_build_query($postFields, '', '&');
        } else {
            $query = $postFields;
        }

        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_POSTFIELDS] = $query;

        $additionalData['callback'] = $callback;

        $curlJob = $this->factory->createCurlJob($url, [$this, 'process'], $additionalData, $options);

        // Start job execution.
        $this->factory->startJob($curlJob);
    }

    /**
     * processes the request return value
     * @param HttpRequest $curl
     */
    public function process(HttpRequest $curl)
    {
        $data = $curl->getData();
        $additionalData = $curl->getAdditionalData();
        $callback = $additionalData['callback'];
        unset($additionalData['callback']);

        $obj = new HttpResult($data['response'], $data['curlInfo'], $curl->getCurlError(), $additionalData);
        call_user_func($callback, $obj);
    }


}

