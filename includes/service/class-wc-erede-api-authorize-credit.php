<?php

use erede\model\TransactionRequest;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Api_Authorize_Credit extends WC_Erede_Api 
{

   public function __construct() {
	   parent::__construct();
	   $this->includes();
	   	
   }

   	private function includes() {
   		require_once dirname ( __FILE__ ) . '/../../vendor/erede/Classloader.php';   		
	}

    public function callApi($order, $request){ 
        $mappedRequest = $this->mapRequest($request);
        $response = new WC_Erede_Payment_Response($this->acquirer->authorize($mappedRequest));
        
        WC_Erede_Log_Helper::getInstance()->add(new WC_erede_logger_template(
                                                                         WC_erede_logger_template::AUTHORIZE, 
                                                                         WC_Erede_Config_Static::getPluginConfig()->getAffiliation(), 
                                                                         $this->getRequestMaskedSensitiveData($request), 
                                                                         $response));  

        
        $response->setStatus(WC_Erede_Functions::getCheckoutStatus($order, $request, $response));
        
        $this->action = WC_Erede_Actions::Checkout;
        
        $this->saveResumeLog($order, $request, $response);
        return $response;
    } 

    private function mapRequest($object) {
    	$mappedObject = new TransactionRequest();
    	
        if($object->getInstallments() != '1'){
            $mappedObject->setInstallments($object->getInstallments());
        }else{
            $object->setInstallments('00');
        }

        if($object->getCardBrand() === 'Credz') {
            $mappedObject->setCapture(true);
        } else {
            $mappedObject->setCapture(WC_Erede_Config_Static::getPluginConfig()->getCaptureFlag());
        }

        $mappedObject->setAmount($object->getAmount());
        $mappedObject->setReference($object->getReference());
        $mappedObject->setCardNumber($object->getCardNumber());
        $mappedObject->setSecurityCode($object->getSecurityCode());
        $expiry = explode("/", $object->getCreditExpiry());
        $mappedObject->setExpirationMonth(str_replace(' ', '',$expiry[0]));
        $mappedObject->setExpirationYear(str_replace(' ', '',$expiry[1]));
        $mappedObject->setCardHolderName($object->getCardHolderName());
        $mappedObject->setSoftDescriptor(WC_Erede_Config_Static::getPluginConfig()->getInvoiceName());
        $mappedObject->setSubscription(0);
        $mappedObject->setOrigin(01);

        return $mappedObject;
    }

    private function getRequestMaskedSensitiveData($request) {
        $maskedRequest = clone $request;
        
        $creditNumber = $request->getCardNumber();
        if($creditNumber != null && !empty($creditNumber) && strlen($creditNumber) >= 16) {
            $maskLenght = strlen($creditNumber) - 10;
            $mask = str_repeat("*", $maskLenght);
            $maskedRequest->setCardNumber(substr_replace($creditNumber, $mask, 6, $maskLenght));
        }
        
        return $maskedRequest;    
    }

     public function overrideLogValues($order, $request, $response)
     {
        $this->erede_log->setStatus(WC_Erede_Status::getTranslatedStatus($response->getStatus()));
	    $this->erede_log->setCreditHolderName($request->getCardHolderName());
	    $this->erede_log->setInstallments($request->getInstallments());
	    $this->erede_log->setCardBin(substr($request->getCardNumber(), 0, 6));
	    $this->erede_log->setLastCardDigits(substr($request->getCardNumber(), -4));
     }
}
?>
