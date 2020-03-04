<?php

use erede\model\TransactionRequest;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Api_Capture extends WC_Erede_Api {
    
    public function __construct() {
       parent::__construct();
       $this->includes();
   }

   	private function includes() {
		require_once dirname ( __FILE__ ) . '/../../vendor/erede/Classloader.php';
	}

    public function callApi($order, $request){

        $mappedRequest = $this->mapRequest($request);
        $response = new WC_Erede_Capture_Response($this->acquirer->capture($request->getTid(), $mappedRequest));
        WC_Erede_Log_Helper::getInstance()->add(new WC_erede_logger_template(WC_erede_logger_template::CAPTURE, WC_Erede_Config_Static::getPluginConfig()->getAffiliation(), $request, $response));
        
		$this->action = WC_Erede_Actions::Capture;
        $this->saveResumeLog($order, $request, $response);       

        return $response;
    }

    private function mapRequest($object) {
    	$mappedRequest = new TransactionRequest();
        $mappedRequest->setAmount($object->getAmount());
        $mappedRequest->setCapture(true);

        return $mappedRequest;
    }

	 public function overrideLogValues($order, $request, $response)
     {
         $this->erede_log->setStatus(WC_Erede_Status::getTranslatedStatus($order->get_status()));
     }
}

?>