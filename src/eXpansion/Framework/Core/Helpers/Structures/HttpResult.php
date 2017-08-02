<?php

namespace eXpansion\Framework\Core\Helpers\Structures;

class HttpResult
{
    /** @var  CurlInfo */
    protected $curlInfo;

    /** @var  string */
    protected $error;

    /** @var  string */
    protected $response;

    /** @var  mixed */
    protected $additionalData;

    /**
     * @return null|string
     */
    public function getResponse()
    {
        if (!$this->hasError()) {
            return $this->response;
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * @return CurlInfo
     */
    public function getInfo()
    {
        return $this->curlInfo;
    }

    /**
     * @return integer|null
     */
    public function getHttpCode()
    {
        return $this->curlInfo->http_code;
    }


    /**
     * HttpResult constructor.
     * @param $data
     * @param $curlInfo
     * @param $error
     * @param $additionalData
     */
    public function __construct($data, $curlInfo, $error, $additionalData)
    {
        $this->response = $data;

        $this->curlInfo = new CurlInfo($curlInfo);
        $this->error = $error;
        $this->additionalData = $additionalData;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        if (empty($this->error) && $this->getHttpCode() == 200) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

}
