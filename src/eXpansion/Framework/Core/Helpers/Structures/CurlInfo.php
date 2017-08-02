<?php

namespace eXpansion\Framework\Core\Helpers\Structures;

class CurlInfo
{

    public $url;
    public $content_type;
    public $http_code;
    public $header_size;
    public $request_size;
    public $filetime;
    public $ssl_verify_result;
    public $redirect_count;
    public $total_time;
    public $namelookup_time;
    public $connect_time;
    public $pretransfer_time;
    public $size_upload;
    public $size_download;
    public $speed_download;
    public $speed_upload;
    public $download_content_length;
    public $upload_content_length;
    public $starttransfer_time;
    public $redirect_time;


    public function __construct($curlinfo)
    {
        foreach ($curlinfo as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
