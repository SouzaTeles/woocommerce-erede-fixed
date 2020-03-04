<?php

use erede\common\Request;
use erede\model\Security;
use erede\model\TransactionRequest;
use erede\model\TransactionResponse;
use erede\model\ReturnCode;
use erede\model\RefundRequest;
use erede\model\RefundResponse;

/**
* Class Acquirer
*
* This class encapsulates the api call
*/
class Acquirer {

    private $security = null;

    function __construct($affiliation, $password, $environment) {
        $this->security = new Security($affiliation, $password, $environment);
    }

    /**
     * @param TransactionRequest $transactionRequest
     * @return TransactionResponse
     */
    function authorize(TransactionRequest $transactionRequest)
    {
        $url_path = 'transactions';

        try {
	        $request = new Request($this->security);
	        $response = $request->post($url_path, $transactionRequest->toJson());
	        
	        if ($response == null) {
	        	throw new Exception();
	        }
        }
        catch (\Exception $e){
        	$response = new TransactionResponse();
        	$response->setReturnCode(ReturnCode::UNSUCCESSFUL);
        	$response->setReturnMessage($e->getMessage());
            return $response;
        }
        
        $transactionResponse = TransactionResponse::mapFromJson($response);
        
        return $transactionResponse;
    }

    /**
     * @param string $tid
     * @param TransactionRequest $transactionRequest
     * @return TransactionResponse
     */
    function capture($tid, TransactionRequest $transactionRequest = null)
    {
        $url_path = "transactions/$tid";
        
        if (is_null($transactionRequest))
        	$transactionRequest = new TransactionRequest();

        try {
	        $request = new Request($this->security);
	        $response = $request->put($url_path, $transactionRequest->toJson());
        }
        catch (\Exception $e){
        	$response = new TransactionResponse();
        	$response->setReturnCode(ReturnCode::UNSUCCESSFUL);
        	$response->setReturnMessage($e->getMessage());
            return $response;
        }
        
        $transactionResponse = TransactionResponse::mapFromJson($response);
        
        return $transactionResponse;
    }

    /**
     * @param string $tid
     * @param RefundRequest $refundRequest
     * @return RefundResponse
     */
    function refund($tid, $refundRequest = null)
    {
        $url_path = "transactions/$tid/refunds";
        
        if (is_null($refundRequest))
        	$refundRequest = new RefundRequest();
        
        try {
        	$request = new Request($this->security);
        	$response = $request->post($url_path, $refundRequest->toJson());
        }
        catch (\Exception $e){
        	$response = new RefundResponse();
        	$response->setReturnCode(ReturnCode::UNSUCCESSFUL);
        	$response->setReturnMessage($e->getMessage());
            return $response;
        }
        	
        $refundResponse = RefundResponse::mapFromJson($response);

        return $refundResponse;
    }
}