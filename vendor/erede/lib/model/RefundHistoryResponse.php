<?php

namespace erede\model;

use erede\common\BaseModel;

/**
 * Class RefundHistoryResponse
 *
 * This class is filled with refund status history information.
 */
class RefundHistoryResponse extends BaseModel
{
	private $dateTime;
	private $status;

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
	public function getStatus(){
		return $this->status;
	}

	/**
	 * @param string $status
	 */
	public function setStatus($status){
		$this->status = $status;
	}
}
