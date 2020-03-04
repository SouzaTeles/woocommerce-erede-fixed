<?php

namespace erede\model;

use erede\common\BaseModel;

/**
 * Class Link
 * 
 * This class contains the links for HATEOAS.
 */
class Link extends BaseModel
{
	private $method;
	private $rel;
	private $href;

	/**
	 * @return string
	 */
	public function getMethod(){
		return $this->method;
	}
	
	/**
	 * @param string $method
	 */
	public function setMethod($method){
		$this->method = $method;
	}
	
	/**
	 * @return string
	 */
	public function getRel(){
		return $this->rel;
	}
	
	/**
	 * @param string $rel
	 */
	public function setRel($rel){
		$this->rel = $rel;
	}
	
	/**
	 * @return string
	 */
	public function getHref(){
		return $this->href;
	}
	
	/**
	 * @param string $href
	 */
	public function setHref($href){
		$this->href = $href;
	}
}