<?php
use erede\model\RefundRequest;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Api_Cancel extends WC_Erede_Api {
  
   public function __construct() {
       parent::__construct();
       $this->includes();    
   }

   	private function includes() {
		require_once dirname ( __FILE__ ) . '/../../vendor/erede/Classloader.php';
	}

    public function callApi($order, $request, $tid){
    	
        $mappedRequest = $this->mapRequest($request);
        
        $response = new WC_Erede_Cancel_Response($this->acquirer->refund($tid, $mappedRequest));
        WC_Erede_Log_Helper::getInstance()->add(new WC_erede_logger_template(WC_erede_logger_template::REFUND, WC_Erede_Config_Static::getPluginConfig()->getAffiliation(), $request, $response));

		$this->action = WC_Erede_Actions::Refund;
        $this->saveResumeRefundLog($order, $request, $response);

        return $response;
    } 

    private function mapRequest($object) {
        $mappedObject = new RefundRequest();
 		
        $mappedObject->setAmount($object->getAmount());
        
        return $mappedObject;
    }

	 public function overrideLogValues($order, $request, $response)
     {
         $this->erede_log->setStatus(WC_Erede_Status::getTranslatedStatus($order->get_status()));
     }
}
?>