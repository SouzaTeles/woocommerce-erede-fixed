<?php

namespace erede\model;

use erede\model\BaseResponse;
use erede\model\RefundResponse;

/**
 * Class RefundListResponse
 *
 * This class is filled with list of refunds information.
 */
class RefundListResponse extends BaseResponse
{
	private $refunds;

	/**
	 * @return string
	 */
	public function getRefunds(){
		return $this->refunds;
	}

	/**
	 * @param string $refunds
	 */
	public function setRefunds($refunds){
		$this->refunds = $refunds;
	}

	/**
	 * @param string $json
	 * @return RefundResponse
	 */
	public static function mapFromJson($json){
		$response = json_decode($json, true);
		if (!$response)
			return null;
			 
		$refundList = new RefundListResponse();
		if (array_key_exists('returnCode', $response))
			$refundList->setReturnCode($response['returnCode']);
		if (array_key_exists('returnMessage', $response))
			$refundList->setReturnMessage($response['returnMessage']);		

		// Refunds
		if (array_key_exists('refunds', $response))
		{
			$refundsResponse = $response['refunds'];
			if (is_array($refundsResponse) && !empty($refundsResponse)){
				$listRefunds = array();
				foreach ($refundsResponse as $refundResponse)
        		{
        			if (is_array($refundResponse) && !empty($refundResponse)){
		        		$refund = new RefundResponse();
		        		if (array_key_exists('refundId', $refundResponse))
        					$refund->setRefundId($refundResponse['refundId']);
        				if (array_key_exists('refundDateTime', $refundResponse))
        					$refund->setRefundDateTime($refundResponse['refundDateTime']);
        				if (array_key_exists('cancelId', $refundResponse))
        					$refund->setCancelId($refundResponse['cancelId']);
        				if (array_key_exists('amount', $refundResponse))
        					$refund->setAmount($refundResponse['amount']);
        				if (array_key_exists('status', $refundResponse))
        					$refund->setStatus($refundResponse['status']);
		        		$listRefunds[] = $refund;
        			}
        		}
        		$refundList->setRefunds($listRefunds);
			}
		}
		
		//HATEOAS Links
		if (array_key_exists('links', $response))
			$refundList->setLinks(self::mapLinksFromArray($response['links']));

		return $refundList;
	}
}
