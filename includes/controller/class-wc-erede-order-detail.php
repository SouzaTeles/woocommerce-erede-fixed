<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Order_Detail {

	public function __construct() {
		$this->includes();
		add_action( 'woocommerce_admin_order_data_after_order_details', array( $this,'actionWoocommerceOrderItemAddActionButtons'), 10, 1);
	}

	private function includes() {
		include_once dirname( __FILE__ ) . '/../utils/class-wc-erede-functions.php';
		
		include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-post-meta-field.php';
		include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-post-data.php';
		include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-status.php';
	}	

	function actionWoocommerceOrderItemAddActionButtons( $order )
	{
		$status = $order->get_status();

		$possible_status = array(WC_Erede_Status::Refunded, WC_Erede_Status::Cancelled, WC_Erede_Status::Completed, WC_Erede_Status::Pending);
		$order_html = "";

		if(get_post_meta($order->get_id(), WC_Erede_Post_Meta_Field::PaymentMethod, true) != 'erede' ||
			empty(get_post_meta($order->get_id(), WC_Erede_Post_Meta_Field::Tid, true ))){
			return "";
		}
	
		if(!in_array($status, $possible_status)){
			$order_html .= '<div class="form-field form-field-wide wc-order-erede">';
			$order_html .= '<p class="form-field form-field-wide">';
			$order_html .= '<button type="button" onclick="updateEredeStatus(' . $order->get_id() . ');" class="button button-primary generate-items">' . __( 'Synchronize', 'messages' ) . '</button>';
			$order_html .= '</p>';
			$order_html .= '</div>';
			$order_html .= '<script type="text/javascript">';
			$order_html .= 'window.onload = function () {
								try {
									cleanOrderUpdatedMessageOnApiError();
								} catch(e) {}  
							}';	
			$order_html .= '</script>';

			//// update status ajax function
			include_once dirname( __FILE__ ) .  '/../../templates/class-wc-erede-order-update-ajax.php';
		}
		
		echo $order_html;
	}
}