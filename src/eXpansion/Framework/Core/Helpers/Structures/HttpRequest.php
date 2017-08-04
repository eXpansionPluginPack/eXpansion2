<?php

namespace eXpansion\Framework\Core\Helpers\Structures;

use oliverde8\AsynchronousJobs\Job\CallbackCurl;

class HttpRequest extends CallbackCurl
{
    /** @var  mixed */
    public $__additionalData;

    /**
     * @return mixed
     */
    public function getAdditionalData()
    {
        return $this->__additionalData;
    }

    /**
     * @param mixed $additionalData
     */
    public function setAdditionalData($additionalData)
    {
        $this->__additionalData = $additionalData;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->response['response'];
    }
}
