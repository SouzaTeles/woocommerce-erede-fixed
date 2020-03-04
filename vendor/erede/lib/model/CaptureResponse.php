<?php

namespace erede\model;

/**
 * Class CaptureResponse
 *
 * This class is filled with transaction capture information from query.
 */
class CaptureResponse
{
	private $dateTime;
	private $nsu;
	private $amount;
	
	/**
	 * @return string
	 */
	public function getDateTime()
	{
		return $this->dateTime;
	}

	/**
	 * @param string $dateTime
	 */
	public function setDateTime($dateTime)
	{
		$this->dateTime = $dateTime;
	}
	
	/**
	 * @return string
	 */
	public function getNsu()
	{
		return $this->nsu;
	}
	
	/**
	 * @param string $nsu
	 */
	public function setNsu($nsu)
	{
		$this->nsu = $nsu;
	}
	
	/**
	 * @return string
	 */
	public function getAmount()
	{
		return $this->amount;
	}
	
	/**
	 * @param string $amount
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}
}
