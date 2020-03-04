<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Cancel_Request implements JsonSerializable {

    
    private $amount = "";
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

    public function jsonSerialize() {
		return get_object_vars($this);
	}
}

?>