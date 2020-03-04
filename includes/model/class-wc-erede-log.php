<?php
if (! defined ( 'ABSPATH' )) {
	exit ();

}
class WC_Erede_Log implements JsonSerializable {
	private $logId;
	private $isTransactionOk;
	private $orderId;
	private $creditHolderName;
	private $messageResult;
	private $environment;
	private $status;
	private $action;
	private $logDate;
	private $tid;
	private $sqn;
	private $transactionDate;
	private $amount;
	private $installments;
	private $currency;
	private $cardBin;
	private $lastCardDigits;
	
	public function getLogId() {
		return $this->logId;
	}
	public function setLogId($logId) {
		$this->logId = $logId;
	}
	public function getIsTransactionOk() {
		return $this->isTransactionOk;
	}
	public function setIsTransactionOk($isTransactionOk) {
		$this->isTransactionOk = $isTransactionOk;
	}
	public function getOrderId() {
		return $this->orderId;
	}
	public function setOrderId($orderId) {
		$this->orderId = $orderId != null ? $orderId : "";
	}
	public function getCreditHolderName() {
		return $this->creditHolderName;
	}
	public function setCreditHolderName($creditHolderName) {
		$this->creditHolderName = $creditHolderName != null ? $creditHolderName : "";
	}
	public function getMessageResult() {
		return $this->messageResult;
	}
	public function setMessageResult($messageResult) {
		$this->messageResult = $messageResult != null ? $messageResult : "";
	}
	public function getEnvironment() {
		return $this->environment;
	}
	public function setEnvironment($environment) {
		$this->environment = $environment != null ? $environment : "";
	}
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status != null ? $status : "";
	}
	public function getAction() {
		return $this->action;
	}
	public function setAction($action) {
		$this->action = $action != null ? $action : "";
	}
	public function getLogDate() {
		return $this->logDate;
	}
	public function setLogDate($logDate) {
		$this->logDate = $logDate != null ? $logDate : current_time ( 'mysql' );
	}
	public function getTid() {
		return $this->tid;
	}
	public function setTid($tid) {
		$this->tid = $tid != null ? $tid : 0;
	}
	public function getSqn() {
		return $this->sqn;
	}
	public function setSqn($sqn) {
		$this->sqn = $sqn != null ? $sqn : 0;
	}
	public function getTransactionDate() {
		return $this->transactionDate;
	}
	public function setTransactionDate($transactionDate) {
		$this->transactionDate = $transactionDate != null ? $transactionDate : current_time ( 'mysql' );
	}
	public function getAmount() {
		return $this->amount;
	}
	public function setAmount($amount) {
		$this->amount = $amount != null ? $amount : 0;
	}
	public function getInstallments() {
		return $this->installments;
	}
	public function setInstallments($installments) {
		$this->installments = $installments != null ? $installments : 0;
	}
	public function getCurrency() {
		return $this->currency;
	}
	public function setCurrency($currency) {
		$this->currency = $currency != null ? $currency : "";
	}
	public function getCardBin() {
		return $this->cardBin;
	}
	public function setCardBin($cardBin) {
		$this->cardBin = $cardBin != null ? $cardBin : "";
	}
	public function getLastCardDigits() {
		return $this->lastCardDigits;
	}
	public function setLastCardDigits($lastCardDigits) {
		$this->lastCardDigits = $lastCardDigits != null ? $lastCardDigits : "";
	}
	public function jsonSerialize() {
		return get_object_vars ( $this );
	}
}
?>  