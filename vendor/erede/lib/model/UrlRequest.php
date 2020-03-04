<?php

namespace erede\model;

use erede\common\BaseModel;

/**
 * Class UrlRequest
 *
 * This class is filled with url information.
 * Request object sent to the server.
 */
class UrlRequest extends BaseModel
{
    private $kind;
    private $url;

    /**
     * @return string (kind)
     */
    public function getKind(){
        return $this->kind;
    }

    /**
     * @param string (UrlKind) $Kind
     */
    public function setKind($kind){
        $this->kind = $kind;
    }

    /**
     * @return string
     */
    public function getUrl(){
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url){
        $this->url = $url;
    }
}