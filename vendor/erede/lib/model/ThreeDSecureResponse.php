<?php

namespace erede\model;

use erede\model\BaseResponse;

/**
 * Class ThreeDSecureResponse
 *
 * This class is filled with 3DS information.
 * Response object from the server.
 */
class ThreeDSecureResponse extends BaseResponse
{
	private $embedded;
	private $url;
	private $eci;
	private $cavv;
	private $xid;
	
	/**
	 * @return string
	 */
	public function getEmbedded(){
		return $this->embedded;
	}
	
	/**
	 * @param string $embedded
	 */
	public function setEmbedded($embedded){
		$this->embedded = $embedded;
	}
	
	/**
	 * @return string
	 */
	public function getUrl(){
		return $this->url;
	}
	
	/**
	 * @param string $url
	 */
	public function setUrl($url){
		$this->url = $url;
	}
	

	/**
	 * @return string
	 */
	public function getEci(){
		return $this->eci;
	}
	
	/**
	 * @param string $eci
	 */
	public function setEci($eci){
		$this->eci = $eci;
	}
	
	/**
	 * @return string
	 */
	public function getCavv(){
		return $this->cavv;
	}
	
	/**
	 * @param string $cavv
	 */
	public function setCavv($cavv){
		$this->cavv = $cavv;
	}
	
	/**
	 * @return string
	 */
	public function getXid(){
		return $this->xid;
	}
	
	/**
	 * @param string $xid
	 */
	public function setXid($xid){
		$this->xid = $xid;
	}
}