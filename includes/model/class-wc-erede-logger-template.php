<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class WC_Erede_Logger_Template {

// 		const GET_AUTHORIZED_CREDIT = "GetAuthorizedCredit";
// 		const CAPTURE = "ConfirmTxnTID";
// 		const VOID_TRANSACTION_TID = "VoidTransactionTID";
// 		const FIND = "Query";
		const AUTHORIZE = "Authorize";
		const CAPTURE = "Capture";
		const REFUND = "Refund";
		const QUERY = "Query";

		private $interface;

		private $affiliation;

		private $request;

		private $response;

		public function __construct($interface, $affiliation, $request, $response) {

			$this->interface = $interface;
			$this->affiliation = $affiliation;
			$this->request = $request;
			$this->response = $response; 
		}

		/**
		* @override function
		*/
		public function __toString() {
			return    " \n "
					. "==================================================================================="
					. " \n "
					. "--Interface--"
					. " \n\t "
					. $this->interface
					. " \n "
					. "--Date--"
					. " \n\t "
					. date_i18n( 'm-d-Y @ H:i:s -' )
					. " \n "
					. "--Affiliation--"
					. " \n\t "
					. $this->affiliation					
					. " \n "
					. '--Request--' . " \n " . json_encode($this->request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) 
					. " \n "					 
				    . '--Response--' . " \n " . json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
					. " \n "
				    . "==================================================================================="
					. "\n";
		}

	}