<?php

use erede\common\Request;
use erede\model\Security;
use erede\model\QueryTransactionResponse;
use erede\model\RefundResponse;
use erede\model\RefundListResponse;
use erede\model\ReturnCode;

/**
* Class Query
*
* This class encapsulates the api call
*/
class Query {

    private $security = null;

    function __construct($affiliation, $password, $environment) {
        $this->security = new Security($affiliation, $password, $environment);
    }

    /**
     * Get transaction by id
     * 
     * @param string
     * @return QueryTransactionResponse
     */
    function getTransactionByTid($tid) {
		return $this->getTransaction($tid, null);
    }

    /**
     * Get transaction by reference
     * 
     * @param string $reference
     * @return QueryTransactionResponse
     */
    function getTransactionByReference($reference){
		return $this->getTransaction(null, $reference);
    }

	/**
     * Get transaction
     * 
     * @param string $reference
     * @return QueryTransactionResponse
     */
    function getTransaction($tid, $reference)
    {
        $url_path = "transactions";

		if($tid && $reference)
		{
			$url_path = "transactions?tid=$tid&reference=$reference";
		}
		elseif($tid)
		{
			$url_path = "transactions/$tid";
		}
    	elseif($reference)
		{
			$url_path = "transactions?reference=$reference";
		}

        try {
        	$request = new Request($this->security);
        	$response = $request->get($url_path);
        }
        catch (\Exception $e){
        	$response = new QueryTransactionResponse();
        	$response->setReturnCode(ReturnCode::UNSUCCESSFUL);
        	$response->setReturnMessage($e->getMessage());
            return $response;
        }
        
        $queryTransactionResponse = QueryTransactionResponse::mapFromJson($response);

        return $queryTransactionResponse;
    }

    /**
     * Get a refund of a transaction by refundId
     * 
     * @param string $tid
     * @param string $refundId
     * @return RefundResponse
     */
    function getRefund($tid, $refundId)
    {
        $url_path = "transactions/$tid/refunds/$refundId";

        try {
        	$request = new Request($this->security);
        	$response = $request->get($url_path);
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

    /**
     * Get the refunds of a transaction
     * 
     * @param string $tid
     * @return RefundListResponse
     */
    function getRefunds($tid)
    {
        $url_path = "transactions/$tid/refunds";

        try {
        	$request = new Request($this->security);
        	$response = $request->get($url_path);
        }
        catch (\Exception $e){
        	$response = new RefundListResponse();
        	$response->setReturnCode(ReturnCode::UNSUCCESSFUL);
        	$response->setReturnMessage($e->getMessage());
            return $response;
        }
        
        $refundListResponse = RefundListResponse::mapFromJson($response);

        return $refundListResponse;
    }
}