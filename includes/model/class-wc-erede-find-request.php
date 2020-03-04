<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Find_Request implements JsonSerializable { 
    private $tid;
    private $reference;

    public function __construct($tid, $reference)  {
        $this->tid = $tid;
        $this->reference = $reference;
    }

    public function getTid() {
        return $this->tid;
    }

    public function getReference() {
        return $this->reference;
    }
    
    public function jsonSerialize() {
		return get_object_vars($this);
	}
}

?>