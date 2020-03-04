<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Capture_Request implements JsonSerializable {
    private $tid;
    private $amount;

    public function __construct($tid, $amount) {
        $this->tid = $tid;
        $this->amount = intval(round($amount*100));
    }

    public function getAmount(){
        return $this->amount;
    }

    public function getTid(){
        return $this->tid;
    }

    public function jsonSerialize() {
		return get_object_vars($this);
	}
}

?>