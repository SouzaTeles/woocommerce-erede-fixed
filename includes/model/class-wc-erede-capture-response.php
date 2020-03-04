<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Capture_Response implements JsonSerializable {

    private $returnCode;
    private $returnMessage;
    private $reference;
    private $authorizationCode;
    private $nsu;
    private $tid;
    private $datetime;

    public function __construct($response) {                       
        $this->returnCode = $response->getReturnCode();
        $this->returnMessage = $response->getReturnMessage();
        $this->reference = $response->getReference();
        $this->authorizationCode = $response->getAuthorizationCode();
        $this->nsu = $response->getNsu();
        $this->tid = $response->getTid();
        $this->datetime = $response->getDateTime();                              
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

    public function getReturnMessage(){
        return $this->returnMessage;
    }

    public function getReference(){
        return $this->reference;
    }

    public function getAuthorizationCode(){
        return $this->authorizationCode;
    }

    public function getNsu(){
        return $this->nsu;
    }

    public function getTid(){
        return $this->tid;
    }

    public function getDateTime(){
        return $this->datetime;
    }
   
    public function jsonSerialize() {
		return get_object_vars($this);
	}
}

?>