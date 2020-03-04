<?php
	
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Erede_Log_Filter implements JsonSerializable {

    private $orderBy;
    private $order;
    private $perPage;
    private $pageNumber;
    private $logId;
    private $orderId;
    private $message;
    private $environment;
    private $stage;
    private $startDate;
    private $endDate;

    public function getOrderBy() {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function getPerPage() {
        return $this->perPage;
    }

    public function setPerPage($perPage) {
        $this->perPage = $perPage;
    }

    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function setPageNumber($pageNumber) {
        $this->pageNumber = $pageNumber;
    }

    public function getLogId() {
        return $this->logId;
    }

    public function setLogId($logId) {
        $this->logId = $logId;
    }
    
    public function getOrderId() {
        return $this->orderId;
    }

    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getEnvironment() {
        return $this->environment;
    }

    public function setEnvironment($environment) {
        return $this->environment = $environment;
    }

    public function getStage() {
        return $this->stage;
    }

    public function setStage($stage) {
        $this->stage = $stage;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function jsonSerialize() {
		return get_object_vars($this);
	}
}
?>