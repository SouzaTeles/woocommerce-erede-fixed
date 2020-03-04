<?php

if (!defined('ABSPATH')) {
	exit;
}

class WC_Erede_Payment_Request implements JsonSerializable
{

	private $amount;
	private $installments;
	private $reference;
	private $cardNumber;
	private $securityCode;
	private $creditExpiry;
	private $cardHolderName;
	private $cardBrand;

	public function __construct(
		$amount,
		$installments,
		$reference,
		$cardNumber,
		$securityCode,
		$creditExpiry,
		$cardHolderName,
		$cardBrand
	) {
		$this->amount = intval(round($amount * 100));
		$this->installments = $installments;
		$this->reference = $reference;
		$this->cardNumber = str_replace(' ', '', $cardNumber);
		$this->securityCode = $securityCode;
		$this->creditExpiry = $creditExpiry;
		$this->cardHolderName = $cardHolderName;
		$this->cardBrand = $cardBrand;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function getInstallments()
	{
		return $this->installments;
	}

	public function setInstallments($installments)
	{
		return $this->installments = $installments;
	}

	public function getReference()
	{
		return $this->reference;
	}

	public function getcardNumber()
	{
		return $this->cardNumber;
	}

	public function getSecurityCode()
	{
		return $this->securityCode;
	}

	public function getCreditExpiry()
	{
		return $this->creditExpiry;
	}

	public function getCardHolderName()
	{
		return $this->cardHolderName;
	}

	public function setCardNumber($cardNumber)
	{
		return $this->cardNumber = $cardNumber;
	}

	public function getCardBrand()
	{
		return $this->cardBrand;
	}

	public function isValid($config)
	{
		$isValid = true;
		$isValid &= $this->validatecardNumber($this->cardNumber);
		if ($isValid == true) {
			$isValid &= $this->validateCreditBrand($this->cardBrand, $this->cardNumber);
		}
		$isValid &= $this->validatecardHolderName($this->cardHolderName);
		$isValid &= $this->validateCreditExpiry($this->creditExpiry);
		$isValid &= $this->validatesecurityCode($this->securityCode);
		$isValid &= $this->validateCreditInstallments(
			$this->installments,
			$config->getInstallments()
		);
		return $isValid;
	}

	private function validatecardHolderName($credit_holder_name)
	{
		try {
			$str_error = __('Invalid name. Fill in with first and last name', 'messages');

			if (!isset($credit_holder_name) || empty($credit_holder_name)) {
				throw new Exception($str_error);
			}

			if (!WC_Erede_Functions::isCompositeName($credit_holder_name)) {
				throw new Exception($str_error);
			}
		} catch (Exception $e) {
			wc_add_notice($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

	private function validatecardNumber($credit_number)
	{
		try {
			$str_error = __('Invalid Card Number', 'messages');

			if (!isset($credit_number) || empty($credit_number)) {
				throw new Exception($str_error);
			}

			if (!WC_Erede_Functions::isValidCardNumber($credit_number)) {
				throw new Exception($str_error);
			}
		} catch (Exception $e) {
			wc_add_notice($e->getMessage(), 'error');
			return false;
		}
		return true;
	}

	private function validateCreditBrand($cardBrand, $cardNumber)
	{
		try {
			$str_error = __('Invalid Card Brand', 'messages');
			if (!isset($cardBrand) || empty($cardBrand)) {
				throw new Exception($str_error);
			}
		} catch (Exception $e) {
			wc_add_notice($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

	private function validateCreditExpiry($credit_expiry)
	{
		try {
			$str_error = __('Expiration must be greater or equal to the current month/year', 'messages');

			if (!isset($credit_expiry) || empty($credit_expiry)) {
				throw new Exception($str_error);
			}

			$expiry = explode("/", $credit_expiry);

			if (count($expiry) <= 1) {
				throw new Exception($str_error);
			}

			$expiry_month = intval($expiry[0]);
			$expiry_year = intval($expiry[1]);

			if ($expiry_month <= 0 || $expiry_year <= 0) {
				throw new Exception($str_error);
			}

			$current_date = new DateTime('now');
			$current_month = intval($current_date->format('m'));
			$current_year = intval($current_date->format('y'));

			if ($current_year > $expiry_year || ($current_year == $expiry_year && $current_month > $expiry_month)) {
				throw new Exception($str_error);
			}
		} catch (Exception $e) {
			wc_add_notice($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

	private function validatesecurityCode($credit_cvv)
	{
		try {
			$str_error = __('CSC code must be 3 digits', 'messages');

			if (!isset($credit_cvv) || empty($credit_cvv) || strlen($credit_cvv) < 3) {
				throw new Exception($str_error);
			}
		} catch (Exception $e) {
			wc_add_notice($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

	private function validateCreditInstallments($credit_installments, $maxInstallments)
	{
		try {
			$str_error = __('Invalid Installments', 'messages');

			if (
				!isset($credit_installments)      ||
				empty($credit_installments)       ||
				intval($credit_installments) == 0 ||
				(intval($credit_installments) > $maxInstallments)
			) {
				throw new Exception($str_error);
			}
		} catch (Exception $e) {
			wc_add_notice($e->getMessage(), 'error');
			return false;
		}

		return true;
	}

	public function jsonSerialize()
	{
		$arrayJson = get_object_vars($this);

		unset($arrayJson['securityCode']);
		unset($arrayJson['creditExpiry']);

		$arrayJson['reference'] = $this->reference;
		unset($arrayJson['reference']);

		$arrayJson['cardNumber'] = $this->cardNumber;
		unset($arrayJson['cardNumber']);

		$arrayJson['cardHolderName'] = $this->cardHolderName;
		unset($arrayJson['cardHolderName']);

		$arrayJson['softdescriptor'] = WC_Erede_Config_Static::getPluginConfig()->getInvoiceName();

		return $arrayJson;
	}
}
