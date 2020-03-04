<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Settings {

	private $wcEredePaymentGateway;

	public function __construct($wcEredePaymentGateway) {
		$this->wcEredePaymentGateway = $wcEredePaymentGateway;
	}

	/**
	* Get Plugin Configurations
	*/
	public function initConfigFields() {
		$this->wcEredePaymentGateway->supports  = array('products');

		if(version_compare(WC_Erede_Functions::getWoocommerceVersion(), '2.4.13') > 0) {
			$this->wcEredePaymentGateway->supports[]  = 'refunds';
		}

		$this->wcEredePaymentGateway->enabled = $this->wcEredePaymentGateway->get_option('enabled');
		$this->wcEredePaymentGateway->affiliation = $this->wcEredePaymentGateway->get_option('affiliation');
		$this->wcEredePaymentGateway->token = $this->wcEredePaymentGateway->get_option('token');
		$this->wcEredePaymentGateway->invoice_name = $this->wcEredePaymentGateway->get_option('invoice_name');
		$this->wcEredePaymentGateway->title = $this->wcEredePaymentGateway->get_option('title');
		$this->wcEredePaymentGateway->environment = $this->wcEredePaymentGateway->get_option('environment');
		$this->wcEredePaymentGateway->transaction_type = $this->wcEredePaymentGateway->get_option('transaction_type');
		$this->wcEredePaymentGateway->installments = $this->wcEredePaymentGateway->get_option('installments');
		$this->wcEredePaymentGateway->minimum_installment = $this->wcEredePaymentGateway->get_option('minimum_installment');
		$this->wcEredePaymentGateway->minimum_installment_value = $this->wcEredePaymentGateway->get_option('minimum_installment_value');
	}

	/**
		* Initialise Gateway Settings Form Fields
		*/
	public function initFormFields() {
		$this->wcEredePaymentGateway->form_fields = array(
		'enabled' => array(
			'title'   => __( 'Enable', 'messages' ),
			'type'    => 'checkbox',
			'label'   => __( 'Enables module in your store', 'messages' ),
			'default' => 'yes',
		),
		'affiliation' => array(
			'title'       => __( 'Affiliation number', 'messages' ),
			'type'        => 'text',
			'description' => __( 'Rede Affiliation Number', 'messages' ),
			'desc_tip'    => true
		),
		'token' => array(
			'title'       => __( 'Token', 'messages' ),
			'type'        => 'text',
			'description' => __( 'Access password', 'messages' ),
			'desc_tip'    => true
		),
		'invoice_name' => array(
			'title'       => __( 'Invoice Number', 'messages' ),
			'type'        => 'text',
			'description' => __( 'The parameter consists of 22 characters. The first 8 characters identify the name of the establishment that will be displayed in the cardholder bill. Rede inserts a hyphen after 8 characters and offers 13 characters to be dynamically sent per transaction.
Example: StoreName-ProductName
To use this feature, visit the Rede website and access the menu item e.Rede > Identificação na fatura or contact the Call Center.
The service is only enabled with the registered name. Once enabled, the feature is available within 24 hours', 'messages' ),
			'desc_tip'    => true
		),
		'title' => array(
			'title'       => __( 'Title', 'messages' ),
			'type'        => 'text',
			'description' => __( 'Name that will be displayed for this payment method at the checkout', 'messages' ),
			'desc_tip'    => true
		),
		'environment' => array(
			'title'       => __( 'Environment', 'messages' ),
			'type'        => 'select',
			'class'       => 'wc-select',
			'description' => __( 'Sets the environment', 'messages' ),
			'desc_tip'    => true,
			'default'     => 'test',
			'options'     => array(
				'test'       => __( 'Test', 'messages' ),
				'production' => __( 'Production', 'messages' ),
			),
		),
		'transaction_type' => array(
			'title'       => __( 'Transaction Type', 'messages' ),
			'type'        => 'select',
			'class'       => 'wc-select',
			'description' => __( 'Transaction type that will be sent to Rede', 'messages' ),
			'desc_tip'    => true,
			'default'     => 'automatic_capture',
			'options'     => array(
				'automatic_capture' => __( 'Automatic Capture', 'messages' ),
				'later_capture'     => __( 'Later Capture', 'messages' ),
			),
		),
		'installments' => array(
			'title'       => __( 'Quota', 'messages' ),
			'type'        => 'select',
			'class'       => 'wc-select',
			'description' => __( 'Sets up how many quota your the store provide', 'messages' ),
			'desc_tip'    => true,
			'default'     => '12',
			'options'     => array(
				'1'       => __( '1', 'messages' ),
				'2'       => __( '2', 'messages' ),
				'3'       => __( '3', 'messages' ),
				'4'       => __( '4', 'messages' ),
				'5'       => __( '5', 'messages' ),
				'6'       => __( '6', 'messages' ),
				'7'       => __( '7', 'messages' ),
				'8'       => __( '8', 'messages' ),
				'9'       => __( '9', 'messages' ),
				'10'      => __( '10', 'messages' ),
				'11'      => __( '11', 'messages' ),
				'12'      => __( '12', 'messages' )
			),
		),
		'minimum_installment' => array(
			'title'       => __( 'Minimum installment amount', 'messages' ),
			'type'        => 'text',
			'description' => __( 'Sets a minimum installment amount. Ex: 99', 'messages' ) . wc_get_price_decimal_separator() . '99',
			'desc_tip'    => false,
			'default'	  => '0',
			'placeholder' => _x('99' . wc_get_price_decimal_separator() . '99', 'placeholder', 'messages'),
		),
		'minimum_installment_value' => array(
			'title'       => __( 'Minimum quota amount', 'messages' ),
			'type'        => 'text',
			'description' => __( 'Sets a specific minimum quota amount. Ex: 99', 'messages' ) . wc_get_price_decimal_separator() . '99',
			'desc_tip'    => false,
			'default'	  => '0',
			'placeholder' => _x('99' . wc_get_price_decimal_separator() . '99', 'placeholder', 'messages'),
		),
		);
	}	

	public function adminOptions() {
		?>
			<h2><?php _e('e.Rede','messages'); ?></h2>
			<table class="form-table">
			<?php $this->wcEredePaymentGateway->generate_settings_html(); ?>
			</table> 

			<script type='text/javascript'>
				document.getElementById("woocommerce_erede_invoice_name").maxLength = 13;
			</script>
		<?php
	}

	public function validateTransactionTypeField( $key, $value ) {
		if (isset( $value ) && $value == "later_capture" ) {
			WC_Admin_Settings::add_message( esc_html__( 'Credz card does not accept this kind of capture. Automatic capture will be used on checkout for this card.', 'messages' ) );
		}

		return $value;
	}

	public function validateMinimumInstallmentField( $key, $value ) {
		if($this->isWoocomerceNumericValidField($value) == false)
		{	
			WC_Admin_Settings::add_error( esc_html__( 'Minimum installment invalid.', 'messages' ) );
			return $this->wcEredePaymentGateway->minimum_installment;
		}

		return $value;
	}

	public function validateMinimumInstallmentValueField( $key, $value ) {
		if($this->isWoocomerceNumericValidField($value) == false)
		{	
			WC_Admin_Settings::add_error( esc_html__( 'Minimum installment value invalid.', 'messages' ) );
			return $this->wcEredePaymentGateway->minimum_installment_value;
		}

			return $value;
	}

	/**
		* Check if is a valid woocomerce numeric number. 
		*/
	private function isWoocomerceNumericValidField($value)
	{
		$decimal_separator = preg_quote(wc_get_price_decimal_separator());
		$thousand_separator = preg_quote(wc_get_price_thousand_separator());
		
		$pattern = "/^([0-9]*" . $thousand_separator . "[0-9]{3}". $decimal_separator ."[0-9]{1,2}|[0-9]*". $decimal_separator ."[0-9]{1,2}|[0-9]+)$/";

		$result_match = preg_match($pattern, $value);

		return $result_match === 1;
	}	

}