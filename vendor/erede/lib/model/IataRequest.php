<?php

namespace erede\model;

use erede\common\BaseModel;

/**
 * Class IataRequest
 *
 * This class is filled with IATA information.
 * Request object sent to the server.
 */
class IataRequest extends BaseModel
{
    private $code;
    private $departureTax;

    /**
     * @return string
     */
    public function getCode(){
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code){
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDepartureTax(){
        return $this->departureTax;
    }

    /**
     * @param string $departureTax
     */
    public function setDepartureTax($departureTax){
        $this->departureTax = $departureTax;
    }
}