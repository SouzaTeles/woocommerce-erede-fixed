<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
 	* ERede gateway class
 	*/
	class WC_erede_payment_gateway extends WC_Payment_Gateway {

		private $eredeCheckout;

		private $eredeRefund;

		private $eredeOrderDetail;

		private $eredeSettings;

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id               	= 'erede';
			$this->icon = apply_filters( 'woocommerce_erede_icon', plugins_url( '../assets/images/rede.png' , __FILE__ ) );
			$this->has_fields       	= true;
			$this->method_title       	= 'e.Rede';
			$this->method_description	= 'eRede';

			$this->includes();

			// Load the settings
			// @override function
			$this->init_settings();

			$this->eredeSettings = new WC_Erede_Settings($this); 
			$this->eredeCheckout = new WC_Erede_Payment_Checkout();
			$this->eredeRefund = new WC_Erede_Refund();
			$this->eredeOrderDetail = new WC_Erede_Order_Detail();

			// Load the form fields
			$this->eredeSettings->initFormFields();

			// Init fields configuration
			$this->eredeSettings->initConfigFields();

			// Hooks
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		/**
		 * Includes.
		*/
		private function includes() {
			include_once dirname( __FILE__ ) . '/utils/class-wc-erede-log-helper.php';
			include_once dirname( __FILE__ ) . '/model/class-wc-erede-logger-template.php';
			
			include_once dirname( __FILE__ ) . '/controller/class-wc-erede-payment-checkout.php';
			include_once dirname( __FILE__ ) . '/controller/class-wc-erede-refund.php';
			include_once dirname( __FILE__ ) . '/controller/class-wc-erede-order-detail.php';
			include_once dirname( __FILE__ ) . '/controller/class-wc-erede-settings.php';

			include_once dirname( __FILE__ ) . '/model/class-wc-erede-config-static.php';
			include_once dirname( __FILE__ ) . '/utils/class-wc-erede-functions.php';

			include_once dirname( __FILE__ ) . '/service/class-wc-erede-api.php';
			include_once dirname( __FILE__ ) . '/model/class-wc-erede-cancel-request.php';
			include_once dirname( __FILE__ ) . '/model/class-wc-erede-cancel-response.php';
			include_once dirname( __FILE__ ) . '/service/class-wc-erede-api-cancel.php';
		}	

		/**
		 * Monta a tela de checkout (tela de pagamento)
		 * @override function
		 */
		public function payment_fields() {
			$this->eredeCheckout->getCheckoutForm($this->get_order_total());
		}

		/**
		* Process the payment and return the result.
		* @override function
		*
		* @param int $order_id Order ID.
		*
		* @return array           Redirect.
		*/
		public function process_payment( $order_id ) {
			return $this->eredeCheckout->processPayment( $order_id, $this->get_order_total() );
		}

		/**
		 * Admin Panel Options
         * see: https://docs.woocommerce.com/document/settings-api/
		 * @override function
		 */
		public function admin_options() {
			$this->eredeSettings->adminOptions();
		}
		
		/**
	 	 * Validate Transaction Type
		 * @override function validate
	 	 */
        public function validate_transaction_type_field( $key ) {
		
			if(isset($_POST["woocommerce_erede_transaction_type"]))
			{
				$value = $_POST["woocommerce_erede_transaction_type"];
				return $this->eredeSettings->validateTransactionTypeField( $key, $value );
			}
        }

		/**
	 	 * Validate Minimum Installment
		 * @override function validate
	 	 */        
		public function validate_minimum_installment_field( $key ) {

			if(isset($_POST["woocommerce_erede_minimum_installment"]))
			{
				$value = $_POST["woocommerce_erede_minimum_installment"];
				return $this->eredeSettings->validateMinimumInstallmentField( $key, $value );
			}
        }

		/**
	 	 * Validate Minimum Installment Value
		 * @override function validate
	 	 */
        public function validate_minimum_installment_value_field( $key ) {
			
			if(isset($_POST["woocommerce_erede_minimum_installment_value"]))
			{
				$value = $_POST["woocommerce_erede_minimum_installment_value"];
				return $this->eredeSettings->validateMinimumInstallmentValueField( $key, $value );
			}
        }

		/**
		* Process a refund in WooCommerce 2.2 or later.
		*
		* @param  int    $order_id
		* @param  float  $amount
		* @param  string $reason
		*
		* @return bool|WP_Error True or false based on success, or a WP_Error object.
		*
		* @override function
		*/
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			return $this->eredeRefund->processRefund($order_id, $amount, $reason);
		}

	}
?>
