<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Payment_Checkout {

	public function __construct() {
		$this->includes();
		
		add_filter('woocommerce_available_payment_gateways',array( $this,'filterGateways'),1);
	}

	/**
	* Includes.
	*/
	private function includes() {
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api.php';
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-payment-request.php';
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-payment-response.php';
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api-authorize-credit.php';
				

//		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-log.php';
//		include_once dirname( __FILE__ ) . '/../dao/class-wc-erede-dao-factory.php';
//
//		include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-actions.php';
//		include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-environment.php';
//		include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-status.php';
//		include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-post-meta-field.php';
//		include_once dirname( __FILE__ ) . '/../utils/class-wc-erede-functions.php';
//		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-config-static.php';
	}

	public function getCheckoutForm($orderTotal) {		
		wc_get_template(
			'payment-form.php',
			array(
				//'methods'      => $this->get_available_methods_options(),
				'installments' => $this->getInstallmentsHtml( $orderTotal ),
			),
			Woocommerce_Gateway_Erede::getTemplatesPath(),
			Woocommerce_Gateway_Erede::getTemplatesPath()
		);
	}	

	/**
	* Get installments HTML.
	*
	* @param  float  $orderTotal Order total.
	*
	* @return string
	*/
	private function getInstallmentsHtml( $orderTotal ) {

		$minimumInstallment = WC_Erede_Config_Static::getPluginConfig()->getMinimumInstallment(); 
		if(!is_numeric($minimumInstallment)) {
			$minimumInstallment = 0;
		}

		$minimumInstallmentValue = WC_Erede_Config_Static::getPluginConfig()->getMinimumInstallmentValue();
		if(!is_numeric($minimumInstallmentValue)) {
			$minimumInstallmentValue = 0;
		}

		$nrInstallments = WC_Erede_Config_Static::getPluginConfig()->getInstallments();
		if($orderTotal < $minimumInstallment) {
			$nrInstallments = 1;
		}

		if($minimumInstallment != 0 && $minimumInstallmentValue > $minimumInstallment) {
			$minimumInstallmentValue = $minimumInstallment;
		}

		$html = '';
		$installments = apply_filters( 'wc_erede_max_installments', $nrInstallments, $orderTotal );
		$optionsCount = 0;

		$html .= '<select id="erede-installments" name="erede_credit_installments" class="input-text" style="font-size: 1.5em; padding: 4px; width: 100%;">';
		for ( $i = 1; $i <= $installments; $i++ ) {
			$installmentValue = $this->getInstallmentValue($orderTotal / $i);
			
			if($installmentValue >= $minimumInstallmentValue) {
				$optionsCount++;
				
				$html .= '<option value="' . $i . '">' . 
							sprintf( __( '%sx of %s no interest.', 'messages' ), $i, sanitize_text_field( wc_price( $installmentValue ) )) . 
						'</option>';
			}
		}

		//// If no options til here ...
		if($optionsCount == 0)
		{
			$html .= '<option value="1">' . 
						sprintf( __( '%sx of %s no interest.', 'messages' ), 1, sanitize_text_field( wc_price( $orderTotal ) )) . 
					'</option>';
		}

		$html .= '</select>';

		return $html;
	}

	private function getInstallmentValue($value) {
 		$arrCreditTotal = explode('.', strval($value));
 		if(isset($arrCreditTotal[1]) && strlen($arrCreditTotal[1]) > 2 ) {
 			$newFraction = strval(intval(substr($arrCreditTotal[1], 0, 2)));
 			return  floatval($arrCreditTotal[0] . '.' . $newFraction) + 0.01;
 		}

 		return $value;
 	}
 
 	public function processPayment($order_id, $orderTotal) {
 		try{
 			extract ($_POST, EXTR_PREFIX_SAME, "wddx");

			$order = new WC_Order($order_id);
			
 			$valid = true;
			$erede_Payment_Request = new WC_Erede_Payment_Request($orderTotal,
																  $erede_credit_installments,
																  $order_id,
																  $erede_credit_number,
																  $erede_credit_cvc,
																  $erede_credit_expiry,
																  $erede_credit_holder_name,
																  WC_Erede_Config_Static::getPluginConfig()->getCardBrand($erede_credit_number));
		  	
			$valid &= WC_Erede_Config_Static::getPluginConfig()->verifyRequiredFields();
 			$valid &= $erede_Payment_Request->isValid(WC_Erede_Config_Static::getPluginConfig()); 			 			
			$valid &= $this->validateCreditCpf($erede_credit_cpf);
			
			if(!$valid) {
				return array(
					'result'   => 'fail',
					'redirect' => ''
				);	
			}
			$plugin = WC_Erede_Config_Static::getPluginConfig();
			
			$erede_Api_Authorize_Credit = new WC_Erede_Api_Authorize_Credit();
			$response = $erede_Api_Authorize_Credit->callApi($order, $erede_Payment_Request);
			if(!($response == null || empty($response)) &&  $response->isSucess()) {
				$status = $this->updateWoocommerceStatus($order, $response);

				$this->updateCustomFields($order_id, $erede_Payment_Request, $response, $erede_credit_cpf);
				return array(
					'result'   => 'success',
					'redirect' => $order->get_checkout_order_received_url()
				);
			} else {
				$status = $this->updateWoocommerceStatus($order, $response);
				return array(
					'result'   => 'fail',
					'redirect' => ''
				);
			}

 		   } catch (Exception $e) {
				wc_add_notice( 'error: ' . $e->getMessage(), 'error'); 
				return array(
					'result'   => 'fail',
					'redirect' => ''
				);
 		  } 
 	}


	/**
	* Atualiza o status do pedido no Woocommerce
	*/
	public function updateWoocommerceStatus($order, $response)
	{
		$status = $response->getStatus();
		if($status == WC_Erede_Status::OnHold)
		{
			$order->update_status(WC_Erede_Status::OnHold, "");
		
		}else if($status == WC_Erede_Status::Processing)
		{
			$order->update_status(WC_Erede_Status::Processing, "");
		}
		else if($status == 'Error'){
			$order->update_status(WC_Erede_Status::Cancelled, "");
		}
	}	

	 /**
	 * Atualiza o pedido com campos customizados.
	 */
	private function updateCustomFields( $order_id, $request, $response, $cpf) {
		
		if ( ! empty( $response->tid ) ) {
			update_post_meta( $order_id, WC_Erede_Post_Meta_Field::Tid, $response->getTid() );
			update_post_meta( $order_id, WC_Erede_Post_Meta_Field::TransactionId, 'TID: ' . $response->getTid() . ' / CPF: ' . WC_Erede_Functions::mask(preg_replace('/[^0-9]/', '', $cpf), '###.###.###-##'));
			
		}
		
		if ( ! empty( $response->nsu ) ) {
			update_post_meta( $order_id, WC_Erede_Post_Meta_Field::Nsu, $response->getNsu() );
			
		}
		
		if (!empty($request->installments)) {
			update_post_meta( $order_id, WC_Erede_Post_Meta_Field::Installments, $request->getInstallments());
			update_post_meta( $order_id, WC_Erede_Post_Meta_Field::CreditHolderName, $request->getCardHolderName());
		}
		
		
		if ( ! empty( $request->getCardNumber()) ) {
			$card_number = $request->getCardNumber();
			update_post_meta( $order_id, WC_Erede_Post_Meta_Field::Bin, substr( $card_number, 0, 6) );
			update_post_meta( $order_id, WC_Erede_Post_Meta_Field::LastFourCreditCardDigits, substr( $card_number, -4) );
		}
	}

	private function validateCreditCpf($cpf) {
		try{
			$str_error = __('Invalid CPF', 'messages');

			if(!isset($cpf) || empty($cpf)) {
				throw new Exception($str_error);
			}
			if(!WC_Erede_Functions::isValidCPF($cpf)) {
				throw new Exception($str_error);
			}
		} catch (Exception $e) {
			wc_add_notice($e->getMessage(), 'error');
			return false;
		}

		return true;
	} 	

	public function filterGateways($gateways){
		global $woocommerce;

		if(get_woocommerce_currency()!='BRL') {
			unset($gateways['erede']); 
		}

		return $gateways;
	}
 }
 ?>
