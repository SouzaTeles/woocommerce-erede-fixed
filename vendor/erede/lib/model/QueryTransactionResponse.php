<?php

namespace erede\model;

use erede\model\BaseResponse;

/**
* Class QueryTransactionResponse
*
* This class is filled with transaction information from query.
*/
class QueryTransactionResponse extends BaseResponse
{
    private $requestDateTime;
    private $authorization;
    private $capture;
    private $iata;
    private $threeDSecure;
    private $refunds;

    /**
     * @return string
     */
    public function getRequestDateTime()
    {
        return $this->requestDateTime;
    }

    /**
     * @param string $requestDateTime
     */
    public function setRequestDateTime($requestDateTime)
    {
        $this->requestDateTime = $requestDateTime;
    }

    /**
     * @return AuthorizationResponse
     */
    public function getAuthorization(){
    	return $this->authorization;
    }
    
    /**
     * @param AuthorizationResponse $authorization
     */
    public function setAuthorization(AuthorizationResponse $authorization){
    	return $this->authorization = $authorization;
    }

    /**
     * @return CaptureResponse
     */
    public function getCapture()
    {
        return $this->capture;
    }

    /**
     * @param CaptureResponse $capture
     */
    public function setCapture(CaptureResponse $capture)
    {
        $this->capture = $capture;
    }

    /**
     * @return IataResponse
     */
    public function getIata()
    {
        return $this->iata;
    }

    /**
     * @param IataResponse $iata
     */
    public function setIata($iata)
    {
        $this->iata = $iata;
    }
    
    /**
     * @return ThreeDSecureResponse
     */
    public function getThreeDSecure(){
    	return $this->threeDSecure;
    }
    
    /**
     * @param ThreeDSecureResponse $threeDSecure
     */
    public function setThreeDSecure(ThreeDSecureResponse $threeDSecure){
    	$this->threeDSecure = $threeDSecure;
    }
    
    /**
     * @return RefundResponse[]
     */
    public function getRefunds()
    {
        return $this->refunds;
    }

    /**
     * @param RefundResponse[] $refunds
     */
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;
    }

    /**
     * @param string $json
     * @return QueryTransactionResponse
     */
    public static function mapFromJson($json){
    	$response = json_decode($json, true);
    	if (!$response)
    		return null;
    	
        $queryResponse = new QueryTransactionResponse();
        if (array_key_exists('requestDateTime', $response))
        	$queryResponse->setRequestDateTime($response['requestDateTime']);
        if (array_key_exists('returnCode', $response))
        	$queryResponse->setReturnCode($response['returnCode']);
        if (array_key_exists('returnMessage', $response))
        	$queryResponse->setReturnMessage($response['returnMessage']);
        
        //Authorization
        if (array_key_exists('authorization', $response)){
        	$authorizationResponse = $response['authorization'];
        	if (is_array($authorizationResponse) && !empty($authorizationResponse)){
	        	$authorization = new AuthorizationResponse();
		        if (array_key_exists('returnCode', $authorizationResponse))
		        	$authorization->setReturnCode($authorizationResponse['returnCode']);
		        if (array_key_exists('returnMessage', $authorizationResponse))
		        	$authorization->setReturnMessage($authorizationResponse['returnMessage']);
		        if (array_key_exists('dateTime', $authorizationResponse))
		        	$authorization->setDateTime($authorizationResponse['dateTime']);
		        if (array_key_exists('affiliation', $authorizationResponse))
		        	$authorization->setAffiliation($authorizationResponse['affiliation']);
		        if (array_key_exists('status', $authorizationResponse))
		        	$authorization->setStatus($authorizationResponse['status']);
		        if (array_key_exists('reference', $authorizationResponse))
		        	$authorization->setReference($authorizationResponse['reference']);
		        if (array_key_exists('tid', $authorizationResponse))
		        	$authorization->setTid($authorizationResponse['tid']);
		        if (array_key_exists('nsu', $authorizationResponse))
		        	$authorization->setNsu($authorizationResponse['nsu']);
		        if (array_key_exists('authorizationCode', $authorizationResponse))
		        	$authorization->setAuthorizationCode($authorizationResponse['authorizationCode']);
		        if (array_key_exists('kind', $authorizationResponse))
		        	$authorization->setKind($authorizationResponse['kind']);
		        if (array_key_exists('amount', $authorizationResponse))
		        	$authorization->setAmount($authorizationResponse['amount']);
		        if (array_key_exists('installments', $authorizationResponse))
		        	$authorization->setInstallments($authorizationResponse['installments']);
		        if (array_key_exists('cardHolderName', $authorizationResponse))
		        	$authorization->setCardholderName($authorizationResponse['cardHolderName']);
		        if (array_key_exists('cardBin', $authorizationResponse))
		        	$authorization->setCardBin($authorizationResponse['cardBin']);
		        if (array_key_exists('last4', $authorizationResponse))
		        	$authorization->setLast4($authorizationResponse['last4']);
		        if (array_key_exists('softDescriptor', $authorizationResponse))
		        	$authorization->setSoftDescriptor($authorizationResponse['softDescriptor']);
		        if (array_key_exists('origin', $authorizationResponse))
		        	$authorization->setOrigin($authorizationResponse['origin']);
		        if (array_key_exists('subscription', $authorizationResponse))
		        	$authorization->setSubscription($authorizationResponse['subscription']);
		        if (array_key_exists('distributorAffiliation', $authorizationResponse))
		        	$authorization->setDistributorAffiliation($authorizationResponse['distributorAffiliation']);
		        $queryResponse->setAuthorization($authorization);
        	}
        }

        // Capture
        if (array_key_exists('capture', $response)){
        	$captureResponse = $response['capture'];
        	if (is_array($captureResponse) && !empty($captureResponse)){
        		$capture = new CaptureResponse();
        		if (array_key_exists('dateTime', $captureResponse))
        			$capture->setDateTime($captureResponse['dateTime']);
        		if (array_key_exists('nsu', $captureResponse))
        			$capture->setNsu($captureResponse['nsu']);
        		if (array_key_exists('amount', $captureResponse))
        			$capture->setAmount($captureResponse['amount']);
        		$queryResponse->setCapture($capture);
        	}
        }
        
        // Iata
        if (array_key_exists('iata', $response)){
        	$iataResponse = $response['iata'];
        	if (is_array($iataResponse) && !empty($iataResponse)){
        		$iata = new IataResponse();
        		if (array_key_exists('code', $iataResponse))
        			$iata->setCode($iataResponse['code']);
        		if (array_key_exists('departureTax', $iataResponse))
        			$iata->setDepartureTax($iataResponse['departureTax']);
        		$queryResponse->setIata($iata);
        	}
        }
        
        // 3DS
        if (array_key_exists('threeDSecure', $response))
        {
        	$threeDsResponse = $response['threeDSecure'];
        	if (is_array($threeDsResponse) && !empty($threeDsResponse)){
        		$threeDs = new ThreeDSecureResponse();
        		if (array_key_exists('returnCode', $threeDsResponse))
        			$threeDs->setReturnCode($threeDsResponse['returnCode']);
        		if (array_key_exists('returnMessage', $threeDsResponse))
        			$threeDs->setReturnMessage($threeDsResponse['returnMessage']);
        		if (array_key_exists('embedded', $threeDsResponse))
        			$threeDs->setEmbedded($threeDsResponse['embedded']);
        		if (array_key_exists('eci', $threeDsResponse))
        			$threeDs->setEci($threeDsResponse['eci']);
        		if (array_key_exists('cavv', $threeDsResponse))
        			$threeDs->setCavv($threeDsResponse['cavv']);
        		if (array_key_exists('xid', $threeDsResponse))
        			$threeDs->setXid($threeDsResponse['xid']);
        		$queryResponse->setThreeDSecure($threeDs);
        	}
        }
        
        // Refund
        if (array_key_exists('refunds', $response))
        {
        	$refundsResponse = $response['refunds'];
        	if (is_array($refundsResponse) && !empty($refundsResponse)){
        		$refunds = array();
        		foreach ($refundsResponse as $refundResponse){
        			if (is_array($refundResponse) && !empty($refundResponse)){
        				$refund = new RefundResponse();
        				if (array_key_exists('refundDateTime', $refundResponse))
        					$refund->setRefundDateTime($refundResponse['refundDateTime']);
        				if (array_key_exists('refundId', $refundResponse))
        					$refund->setRefundId($refundResponse['refundId']);
        				if (array_key_exists('amount', $refundResponse))
        					$refund->setAmount($refundResponse['amount']);
        				if (array_key_exists('status', $refundResponse))
        					$refund->setStatus($refundResponse['status']);
        				$refunds[] = $refund;
        			}
        		}
        		$queryResponse->setRefunds($refunds);
        	}
        }
        
        //HATEOAS Links
        if (array_key_exists('links', $response))
        	$queryResponse->setLinks(self::mapLinksFromArray($response['links']));

        return $queryResponse;
    }
}