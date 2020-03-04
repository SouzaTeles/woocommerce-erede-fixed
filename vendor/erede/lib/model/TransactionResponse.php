<?php

namespace erede\model;

use erede\model\BaseResponse;

/**
* Class TransactionResponse
*
* This class is filled with transaction information.
* Response object from the server.
*/
class TransactionResponse extends BaseResponse
{
    private $reference;
    private $tid;
    private $nsu;
    private $authorizationCode;
    private $dateTime;
    private $amount;
    private $installments;
    private $cardBin;
    private $last4;
    private $threeDSecure;

    /**
     * @return string
     */
    public function getReference(){
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference){
        $this->reference = $reference;
    }
    
    /**
     * @return string
     */
    public function getTid(){
    	return $this->tid;
    }
    
    /**
     * @param string $tid
     */
    public function setTid($tid){
    	$this->tid = $tid;
    }
    
    /**
     * @return string
     */
    public function getNsu(){
    	return $this->nsu;
    }
    
    /**
     * @param string $nsu
     */
    public function setNsu($nsu){
    	$this->nsu = $nsu;
    }
    
    /**
     * @return string
     */
    public function getAuthorizationCode(){
    	return $this->authorizationCode;
    }
    
    /**
     * @param string $authorizationCode
     */
    public function setAuthorizationCode($authorizationCode){
    	$this->authorizationCode = $authorizationCode;
    }

    /**
     * @return string
     */
    public function getDateTime(){
        return $this->dateTime;
    }

    /**
     * @param string $dateTime
     */
    public function setDateTime($dateTime){
        $this->dateTime = $dateTime;
    }

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
     * @return string
     */
    public function getInstallments(){
    	return $this->installments;
    }
    
    /**
     * @param string $installments
     */
    public function setInstallments($installments){
    	$this->installments = $installments;
    }
    
    /**
     * @return string
     */
    public function getCardBin(){
    	return $this->cardBin;
    }
    
    /**
     * @param string $cardBin
     */
    public function setCardBin($cardBin){
    	 $this->cardBin = $cardBin;
    }
    
    /**
     * @return string
     */
    public function getLast4(){
    	 return $this->last4;
    }
    
    /**
     * @param string $last4
     */
    public function setLast4($last4){
    	$this->last4 = $last4;
    }
    
    //3DS
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
     * @param string $json
     * @return TransactionResponse
     */
    public static function mapFromJson($json){
    	$response = json_decode($json, true);
    	if (!$response)
    		return null;
    	
        $transactionResponse = new TransactionResponse();
        if (array_key_exists('returnCode', $response))
        	$transactionResponse->setReturnCode($response['returnCode']);
        if (array_key_exists('returnMessage', $response))
        	$transactionResponse->setReturnMessage($response['returnMessage']);
        if (array_key_exists('reference', $response))
        	$transactionResponse->setReference($response['reference']);
        if (array_key_exists('tid', $response))
        	$transactionResponse->setTid($response['tid']);
        if (array_key_exists('nsu', $response))
        	$transactionResponse->setNsu($response['nsu']);
        if (array_key_exists('authorizationCode', $response))
        	$transactionResponse->setAuthorizationCode($response['authorizationCode']);
        if (array_key_exists('dateTime', $response))
        	$transactionResponse->setDateTime($response['dateTime']);
        if (array_key_exists('amount', $response))
        	$transactionResponse->setAmount($response['amount']);
        if (array_key_exists('installments', $response))
        	$transactionResponse->setInstallments($response['installments']);
        if (array_key_exists('cardBin', $response))
        	$transactionResponse->setCardBin($response['cardBin']);
        if (array_key_exists('last4', $response))
        	$transactionResponse->setLast4($response['last4']);
        
        //3DS
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
        		if (array_key_exists('url', $threeDsResponse))
        			$threeDs->setUrl($threeDsResponse['url']);
        		$transactionResponse->setThreeDSecure($threeDs);
        	}
        }
        
        //HATEOAS Links
        if (array_key_exists('links', $response))
        	$transactionResponse->setLinks(self::mapLinksFromArray($response['links']));

        return $transactionResponse;
    }
}
