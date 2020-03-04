<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Refund {

	public function __construct() {
		include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-post-meta-field.php';
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-cancel-request.php';
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-cancel-response.php';
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api.php';
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api-cancel.php';
	}

	/**
	* Realiza o processamento do reembolso
	* @param  int    $order_id
	* @param  float  $amount
	* @param  string $reason
	*/
	public function processRefund( $order_id, $amount = null, $reason = '' ) {

		$order = new WC_Order( $order_id );
		$tid = get_post_meta($order_id, WC_Erede_Post_Meta_Field::Tid, true );

		if ( ! $order || empty($tid) ) {
			return false;
		}

		//Only full refund
		if($order->get_total() > $amount){
			return new WP_Error( 'erede-error', esc_html__( 'Full refund is required.', 'messages' ) );	
		}

		// //Call API Cancel
		// $erede_Cancel_Request = new WC_Erede_Cancel_Request();
		// $erede_Cancel_Request->setAmount(intval($amount * 100));
		
		// $erede_Api_Cancel = new WC_Erede_Api_Cancel();
		// $response = $erede_Api_Cancel->callApi($order, $erede_Cancel_Request, get_post_meta($order->get_id(), WC_Erede_Post_Meta_Field::Tid, true ));

		// return $response->isSucess();

		return true;

	}

}
