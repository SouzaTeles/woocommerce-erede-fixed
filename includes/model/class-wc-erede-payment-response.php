<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

class WC_Erede_Payment_Response implements JsonSerializable {

    public function __construct($response) {
        $this->returnCode = $response->getReturnCode();
        $this->authorizationCode = $response->getAuthorizationCode();
        $this->reference = $response->getReference();
        $this->nsu = $response->getNsu();
        $this->tid = $response->getTid();
        $this->installments = $response->getInstallments();
        $this->amount = $response->getAmount();
        $this->datetime = $response->getDateTime();
        $this->returnMessage = $response->getReturnMessage();
        $this->status = ""; 
    }

    public function isSucess() {
        if($this->returnCode == 0 || $this->returnCode == '00' || $this->returnCode == 00) {
            return true;
        } else {
            return false;
        } 
    }
    public function getReturnCode(){
        return $this->returnCode;
    }

//     public function getInterest(){
//         return $this->interest;
//     }

    public function getReturnMessage(){
        return $this->returnMessage;
    }

    public function getAuthorizationCode(){
        return $this->authorizationCode;
    }

    public function getReference(){
        return $this->reference;
    }

    public function getNsu(){
        return $this->nsu;
    }

    public function getTid(){
        return $this->tid;
    }

    public function getInstallments(){
        return $this->installments;
    }

    public function getDateTime(){
        return $this->datetime;
    }

    public function getStatus() {
		return $this->status;
	}

	public function setStatus($status) {
		return $this->status = $status;
	}

    public function jsonSerialize() {
		$arrayJson = get_object_vars($this);
        unset($arrayJson['status']);
        return $arrayJson;
	}
}
