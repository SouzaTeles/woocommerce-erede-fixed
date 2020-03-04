<?php

namespace erede\model;

/**
 * Class IataResponse
 *
 * This class is filled with transaction iata information from query.
 */
class IataResponse
{
	private $code;
	private $departureTax;
	
	/**
	 * @return string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getDepartureTax()
	{
		return $this->departureTax;
	}

	/**
	 * @param string $departureTax
	 */
	public function setDepartureTax($departureTax)
	{
		$this->departureTax = $departureTax;
	}
}
