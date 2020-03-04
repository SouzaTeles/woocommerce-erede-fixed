<?php
if (! defined ( 'ABSPATH' )) {
	exit ();
}
class WC_Erede_Find_Response implements JsonSerializable {
	private $requestDateTime;
	private $authorization;
	private $capture;
	private $iata;
	private $threeDSecure;
	private $refunds;
	private $returnCode;
	private $returnMessage;
	
	public function __construct($response) {
		$this->requestDateTime = $response->getRequestDateTime();
		$this->authorization = $response->getAuthorization ();
		$this->capture = $response->getCapture ();
		$this->iata = $response->getIata ();
		$this->threeDSecure = $response->getThreeDSecure ();
		$this->refunds = $response->getRefunds ();
		$this->returnCode = $response->getReturnCode ();
		$this->returnMessage = $response->getReturnMessage ();
	}
	
	/**
	 *
	 * @return string
	 */
	public function getReturnCode() {
		return $this->returnCode;
	}
	
	/**
	 *
	 * @param string $returnCode
	 */
	public function setReturnCode($returnCode) {
		$this->returnCode = $returnCode;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getReturnMessage() {
		return $this->returnMessage;
	}
	
	/**
	 *
	 * @param string $returnMessage
	 */
	public function setReturnMessage($returnMessage) {
		$this->returnMessage = $returnMessage;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getRequestDateTime() {
		return $this->requestDateTime;
	}
	
	/**
	 *
	 * @param string $requestDate        	
	 */
	public function setRequestDateTime($requestDateTime) {
		$this->requestDateTime = $requestDateTime;
	}
	
	/**
	 *
	 * @return AuthorizationResponse
	 */
	public function getAuthorization() {
		return $this->authorization;
	}
	
	/**
	 *
	 * @param AuthorizationResponse $authorization        	
	 */
	public function setAuthorization(AuthorizationResponse $authorization) {
		return $this->authorization = $authorization;
	}
	
	/**
	 *
	 * @return CaptureResponse
	 */
	public function getCapture() {
		return $this->capture;
	}
	
	/**
	 *
	 * @param CaptureResponse $capture        	
	 */
	public function setCapture(CaptureResponse $capture) {
		$this->capture = $capture;
	}
	
	/**
	 *
	 * @return IataResponse
	 */
	public function getIata() {
		return $this->iata;
	}
	
	/**
	 *
	 * @param IataResponse $iata        	
	 */
	public function setIata($iata) {
		$this->iata = $iata;
	}
	
	/**
	 *
	 * @return ThreeDSecureResponse
	 */
	public function getThreeDSecure() {
		return $this->threeDSecure;
	}
	
	/**
	 *
	 * @param ThreeDSecureResponse $threeDSecure        	
	 */
	public function setThreeDSecure(ThreeDSecureResponse $threeDSecure) {
		$this->threeDSecure = $threeDSecure;
	}
	
	/**
	 *
	 * @return RefundResponse[]
	 */
	public function getRefunds() {
		return $this->refunds;
	}
	
	/**
	 *
	 * @param RefundResponse[] $refunds        	
	 */
	public function setRefunds($refunds) {
		$this->refunds = $refunds;
	}
	
	public function jsonSerialize() {
		return get_object_vars($this);
	}
	
	public function isSucess() {
		if(!empty($this->getReturnCode())){
			return false;
		}
		else{
			if((!empty($this->getAuthorization())) && $this->getAuthorization()->getReturnCode() == '00'){
				return true;
			}
			elseif((!empty($this->getRefunds())) && ($this->getRefunds()->getReturnCode() == '00' || $this->getRefunds()->getReturnCode() == '359' || $this->getRefunds()->getReturnCode() == '360')){
				return  true;
			}
			else{
				return false;
			}
			
		}
		
		
	}
	
}

?>
