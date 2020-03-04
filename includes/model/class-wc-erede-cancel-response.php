
<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Cancel_Response implements JsonSerializable {

    private $returnCode = 0;
    private $returnMessage = "";
    private $tid = "";
    private $nsu = "";
    private $refundDateTime = "";

    public function __construct($response) {
        $this->returnCode = $response->getReturnCode();
        $this->returnMessage = $response->getReturnMessage();
        $this->nsu = $response->getNsu();
        $this->tid = $response->getTid();
        $this->refundDateTime  = $response->getRefundDateTime();                            
    }

    public function getReturnCode(){
        return $this->returnCode;
    }

    public function setReturnCode($returnCode){
        $this->returnCode = $returnCode;
    }

    public function getReturnMessage(){
        return $this->returnMessage;
    }

    public function setReturnMessage($returnMessage){
        $this->returnMessage = $returnMessage;
    }

    public function getTid(){
        return $this->tid;
    }

    public function setTid($tid){
        $this->tid = $tid;
    }

    public function getNsu(){
        return $this->nsu;
    }

    public function setNsu($nsu){
        $this->nsu = $nsu;
    }

    public function getRefundDateTime(){
        return $this->refundDateTime;
    }

    public function setRefundDateTime($refundDateTime){
        $this->refundDateTime = $refundDateTime;
    }

     public function isSucess() {
	if(!empty($this->getReturnCode())){
     		if($this->returnCode == '00' ||  $this->returnCode == '359' || $this->returnCode == '360') {
     			return true;
     		} else {
     			return false;
     		}
     	}
    }

     public function jsonSerialize() {
		return get_object_vars($this);
	}
}
?>
