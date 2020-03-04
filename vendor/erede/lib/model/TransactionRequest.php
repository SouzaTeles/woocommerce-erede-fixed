<?php

namespace erede\model;

use erede\common\BaseModel;

/**
 * Class TransactionRequest
 *
 * This class is filled with transaction information.
 * Request object sent to the server.
 */
class TransactionRequest extends BaseModel
{
    private $capture;
    private $kind;
    private $reference;
    private $amount;
    private $installments;
    private $cardHolderName;
    private $cardNumber;
    private $expirationMonth;
    private $expirationYear;
    private $securityCode;
    private $softDescriptor;
    private $subscription;
    private $origin;
    private $distributorAffiliation;

    //3DS
    private $threeDSecure;

    //IATA
    private $iata;

    //urls
    private $urls;

    /**
     * @return string
     */
    public function getCapture(){
        return $this->capture;
    }

    /**
     * @param string $capture
     */
    public function setCapture($capture){
        $this->capture = $capture;
    }
	
    /**
     * @return string (TransactionKind)
     */
    public function getKind(){
        return $this->kind;
    }

    /**
     * @param string (TransactionKind) $kind
     */
    public function setKind($kind){
        $this->kind = $kind;
    }

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
    public function getCardHolderName(){
        return $this->cardHolderName;
    }

    /**
     * @param string $cardHolderName
     */
    public function setCardHolderName($cardHolderName){
        $this->cardHolderName = $cardHolderName;
    }

    /**
     * @return string
     */
    public function getCardNumber(){
        return $this->cardNumber;
    }

    /**
     * @param string $cardNumber
     */
    public function setCardNumber($cardNumber){
        $this->cardNumber = $cardNumber;
    }

    /**
     * @return string
     */
    public function getExpirationMonth(){
        return $this->expirationMonth;
    }

    /**
     * @param string $expirationMonth
     */
    public function setExpirationMonth($expirationMonth){
        $this->expirationMonth = $expirationMonth;
    }

    /**
     * @return string
     */
    public function getExpirationYear(){
        return $this->expirationYear;
    }

    /**
     * @param string $expirationYear
     */
    public function setExpirationYear($expirationYear){
        $this->expirationYear = $expirationYear;
    }

    /**
     * @return string
     */
    public function getSecurityCode(){
        return $this->securityCode;
    }

    /**
     * @param string $securityCode
     */
    public function setSecurityCode($securityCode){
        $this->securityCode = $securityCode;
    }

    /**
     * @return string
     */
    public function getSoftDescriptor(){
        return $this->softDescriptor;
    }

    /**
     * @param string $softDescriptor
     */
    public function setSoftDescriptor($softDescriptor){
        $this->softDescriptor = $softDescriptor;
    }

    /**
     * @return string
     */
    public function getSubscription(){
        return $this->subscription;
    }

    /**
     * @param string $subscription
     */
    public function setSubscription($subscription){
        $this->subscription = $subscription;
    }
    
    /**
     * @return string
     */
    public function getOrigin(){
        return $this->origin;
    }

    /**
     * @param string $origin
     */
    public function setOrigin($origin){
        $this->origin = $origin;
    }

    /**
     * @return string
     */
    public function getDistributorAffiliation(){
        return $this->distributorAffiliation;
    }

    /**
     * @param string $distributorAffiliation
     */
    public function setDistributorAffiliation($distributorAffiliation){
        $this->distributorAffiliation = $distributorAffiliation;
    }
    
    /**
     * @return ThreeDSecureRequest
     */
    public function getThreeDSecure(){
        return $this->threeDSecure;
    }

    /**
     * @param ThreeDSecureRequest $threeDSecure
     */
    public function setThreeDSecure(ThreeDSecureRequest $threeDSecure){
        $this->threeDSecure = $threeDSecure;
    }

    /**
     * @return IataRequest
     */
    public function getIata(){
        return $this->iata;
    }

    /**
     * @param IataRequest $iata
     */
    public function setIata(IataRequest $iata){
        $this->iata = $iata;
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
}