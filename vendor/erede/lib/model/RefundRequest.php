<?php

namespace erede\model;

use erede\common\BaseModel;

/**
 * Class RefundRequest
*
* This class is filled with refund information.
* Request object sent to the server.
*/
class RefundRequest extends BaseModel
{
    private $amount;
    private $urls;

    /**
     * @return string
     */
    public function getAmount(){
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount){
        $this->amount = $amount;
    }

    /**
     * @return UrlRequest[]
     */
    public function getUrls(){
        return $this->urls;
    }

    /**
     * @param UrlRequest[] $urls
     */
    public function setUrls($urls){
        $this->urls = $urls;
    }
}
