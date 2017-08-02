<?php

namespace eXpansion\Framework\Core\Helpers\Structures;

class HttpResult
{
    /** @var  CurlInfo */
    protected $curlInfo;
    /** @var  string */
    protected $error;

    /** @var  string */
    protected $data;

    public function getData()
    {
        if ($this->error) {
            return "error:".$this->error;
        } else {
            return $this->data;
        }
    }

    public function __construct($data, $curlInfo, $error)
    {
        $this->data = $data;
        $this->curlInfo = $curlInfo;
        $this->error = $error;
    }


}
