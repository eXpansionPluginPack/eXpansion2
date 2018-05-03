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
     * @param string|array $fields
     * @param callable $callback callback with returning datas
     * @param null|mixed $additionalData If you need to pass additional metadata.
     *                                   You will get this back in the callback.
     * @param array $options Single dimensional array of curl_setopt key->values
     */
    public function post($url, $fields, callable $callback, $additionalData = null, $options = [])
    {
        $this->doCall("POST", $url, $fields, $callback, $additionalData, $options);
    }

    /**
     * Make a put http query.
     *
     * @param string $url address
     * @param string|array $fields
     * @param callable $callback callback with returning datas
     * @param null|mixed $additionalData If you need to pass additional metadata.
     *                                   You will get this back in the callback.
     * @param array $options Single dimensional array of curl_setopt key->values
     */
    public function put($url, $fields, callable $callback, $additionalData = null, $options = [])
    {
        $this->doCall("PUT", $url, $fields, $callback, $additionalData, $options);
    }


    /**
     * Make a delete http query.
     *
     * @param string $url address
     * @param string|array $fields
     * @param callable $callback callback with returning datas
     * @param null|mixed $additionalData If you need to pass additional metadata.
     *                                   You will get this back in the callback.
     * @param array $options Single dimensional array of curl_setopt key->values
     */
    public function delete($url, $fields, callable $callback, $additionalData = null, $options = [])
    {
        $this->doCall("DELETE", $url, $fields, $callback, $additionalData, $options);
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

    protected function doCall($method, $url, $fields, callable $callback, $additionalData = null, $options = [])
    {
        $defaultOptions = [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => "eXpansionPluginPack v ".AbstractApplication::EXPANSION_VERSION,
        ];

        switch ($method) {
            case "POST" :
                $defaultOptions[CURLOPT_POST] = true;
                break;
            case "PUT" :
                $defaultOptions[CURLOPT_PUT] = true;
                break;
            default :
                $defaultOptions[CURLOPT_CUSTOMREQUEST] = "DELETE";
        }

        $options = $options + $defaultOptions;

        if (is_array($fields)) {
            $query = http_build_query($fields, '', '&');
        } else {
            $query = $fields;
        }

        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_POSTFIELDS] = $query;

        $additionalData['callback'] = $callback;

        $curlJob = $this->factory->createCurlJob($url, [$this, 'process'], $additionalData, $options);

        // Start job execution.
        $this->factory->startJob($curlJob);
    }
}

