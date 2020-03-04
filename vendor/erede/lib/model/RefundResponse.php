<?php

namespace erede\model;

use erede\model\BaseResponse;
use erede\model\RefundHistoryResponse;

/**
* Class RefundResponse
*
* This class is filled with refund response information.
*/
class RefundResponse extends BaseResponse
{
    private $refundId;
    private $tid;
    private $nsu;
    private $refundDateTime;
    private $cancelId;
    private $amount;
    private $status;
    private $statusHistory;

    /**
     * @return string
     */
    public function getRefundId(){
        return $this->refundId;
    }

    /**
     * @param string $refundId
     */
    public function setRefundId($refundId){
        $this->refundId = $refundId;
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
    public function getRefundDateTime(){
        return $this->refundDateTime;
    }

    /**
     * @param string $refundDateTime
     */
    public function setRefundDateTime($refundDateTime){
        $this->refundDateTime = $refundDateTime;
    }

    /**
     * @return string
     */
    public function getCancelId(){
        return $this->cancelId;
    }

    /**
     * @param string $cancelId
     */
    public function setCancelId($cancelId){
        $this->cancelId = $cancelId;
    }
    
    /**
     * @return string
     */
    public function getAmount(){
    	return $this->amount;
    }
    
    /**
     * @param string $status
     */
    public function setAmount($amount){
    	$this->amount = $amount;
    }
    
    /**
     * @return string
     */
    public function getStatus(){
    	return $this->status;
    }
    
    /**
     * @param string $status
     */
    public function setStatus($status){
    	$this->status = $status;
    }
    
    /**
     * @return RefundHistoryResponse[]
     */
    public function getStatusHistory(){
    	return $this->statusHistory;
    }
    
    /**
     * @param RefundHistoryResponse[] $statusHistory
     */
    public function setStatusHistory($statusHistory){
    	$this->statusHistory = $statusHistory;
    }

    /**
     * @param string $json
     * @return RefundResponse
     */
    public static function mapFromJson($json){
    	$response = json_decode($json, true);
    	if (!$response)
    		return null;
    	
        $refundResponse = new RefundResponse();
        if (array_key_exists('returnCode', $response))
        	$refundResponse->setReturnCode($response['returnCode']);
        if (array_key_exists('returnMessage', $response))
        	$refundResponse->setReturnMessage($response['returnMessage']);
        if (array_key_exists('refundId', $response))
        	$refundResponse->setRefundId($response['refundId']);
        if (array_key_exists('tid', $response))
        	$refundResponse->setTid($response['tid']);
        if (array_key_exists('nsu', $response))
        	$refundResponse->setNsu($response['nsu']);
        if (array_key_exists('refundDateTime', $response))
        	$refundResponse->setRefundDateTime($response['refundDateTime']);
        if (array_key_exists('cancelId', $response))
        	$refundResponse->setCancelId($response['cancelId']);
        if (array_key_exists('amount', $response))
        	$refundResponse->setAmount($response['amount']);
        if (array_key_exists('status', $response))
        	$refundResponse->setStatus($response['status']);
        
        // Status History
        if (array_key_exists('statusHistory', $response))
        {
        	$statusHistoryResponse = $response['statusHistory'];
        	if (is_array($statusHistoryResponse) && !empty($statusHistoryResponse)){
        		$listRefundHistory = array();
        		foreach ($statusHistoryResponse as $statusHistory)
        		{
        			if (is_array($statusHistory) && !empty($statusHistory)){
		        		$refundHistory = new RefundHistoryResponse();
		        		if (array_key_exists('status', $statusHistory))
		        			$refundHistory->setStatus($statusHistory['status']);
		        		if (array_key_exists('dateTime', $statusHistory))
		        			$refundHistory->setDateTime($statusHistory['dateTime']);
		        		$listRefundHistory[] = $refundHistory;
        			}
        		}
        		$refundResponse->setStatusHistory($listRefundHistory);
        	}
        }
        
        //HATEOAS Links
        if (array_key_exists('links', $response))
        	$refundResponse->setLinks(self::mapLinksFromArray($response['links']));

        return $refundResponse;
    }
}
