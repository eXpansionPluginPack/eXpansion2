<?php

namespace eXpansion\Framework\Core\Helpers\Structures;

use oliverde8\AsynchronousJobs\Job\CallbackCurl;

class HttpRequest extends CallbackCurl
{
    public $__additionalData;

    /**
     * @return null
     */
    public function getAdditionalData()
    {
        return $this->__additionalData;
    }

    /**
     * @param null $additionalData
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
