<?php

if ( ! defined( 'ABSPATH' ) ) {
		exit;
}

include_once dirname( __FILE__ ) . "/../utils/class-wc-erede-functions.php";
include_once dirname( __FILE__ ) . "/../utils/constants/class-wc-erede-sessions.php";
include_once dirname( __FILE__ ) . "/../utils/constants/class-wc-erede-post-meta-field.php";
include_once dirname( __FILE__ ) . "/../utils/constants/class-wc-erede-status.php";

class WC_Erede_Order_Status {

	/**
	* @override function.
	*/
	public static function orderStatusChanged($order_id, $old_status, $new_status) {
		if($_SESSION[WC_Erede_Sessions::OrderIdUpdateFromApi . $order_id]) {
			unset($_SESSION[WC_Erede_Sessions::OrderIdUpdateFromApi . $order_id]);
			return;
		}

		global $woocommerce;
		
		$order = new WC_Order($order_id);	
		
		//Process only Erede with TID and no pending
		if(get_post_meta($order_id, WC_Erede_Post_Meta_Field::PaymentMethod, true) != 'erede' ||
		   empty(get_post_meta($order_id, WC_Erede_Post_Meta_Field::Tid, true )) ||
		   $old_status=="pending"){
			return;
		}
		
		if($old_status == $new_status){			 
			return;
		}

		//Check Machine State
		if(!WC_Erede_Functions::isValidStatusTransition($old_status,$new_status)){
			self::resetOrder($order, $old_status);

			if($new_status == "refunded") {
				
				self::resetRefund($order);
			}

			if($old_status != "on-hold" && $new_status == "completed") {
				
				$new_status = 'default';
			}

			if($old_status != "on-hold"  && $new_status == "processing") {
				
				$new_status = 'default';
			}

			self::addSessionOrderFailed($order->get_id(), $new_status);
			return;
		}

		//Call Capture if is processing
		if($new_status == "processing"){
			if (!self::captureOrder($order)){
				self::resetOrder($order, $old_status);
				self::addSessionOrderFailed($order->get_id(), $new_status);
				return;
			}
		}

		//Call Refund if is refund
		if($new_status == "refunded") {
			if (!self::cancelOrder($order)){
				self::resetRefund($order);
				self::resetOrder($order, $old_status);
				self::addSessionOrderFailed($order->get_id(), $new_status);
				return;
			}
			else
			{
				
				$_SESSION[WC_Erede_Sessions::OrderUpdated] = esc_html__( "Order updated.", 'messages' );
			}
		}
	}
	
	private function captureOrder($order) {
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-capture-request.php';
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-capture-response.php';
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api.php';
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api-capture.php';

		$captureRequest = new WC_Erede_Capture_Request(get_post_meta($order->get_id(), WC_Erede_Post_Meta_Field::Tid, true ), 
													   $order->get_total());

		$captureService = new WC_Erede_Api_Capture();
		$response = $captureService->callApi($order, $captureRequest);

		return $response->isSucess();
	}

	private function cancelOrder($order) {
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-cancel-request.php';
		include_once dirname( __FILE__ ) . '/../model/class-wc-erede-cancel-response.php';
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api.php';
		include_once dirname( __FILE__ ) . '/../service/class-wc-erede-api-cancel.php';

		$refunds = $order->get_refunds();
		$refunds_length = count($refunds);

		for ($i = 0; $i < $refunds_length; $i++) {
			$refund_amount = $refunds[$i]->get_amount();

			if($refund_amount > 0 && $refund_amount != $order->get_total())
			{
				return false;
			}
		} 

		//Call API Cancel
		$erede_Cancel_Request = new WC_Erede_Cancel_Request();
		$erede_Cancel_Request->setAmount(intval(round($refund_amount*100)));
		
		$erede_Api_Cancel = new WC_Erede_Api_Cancel();
		$response = $erede_Api_Cancel->callApi($order, $erede_Cancel_Request, get_post_meta($order->get_id(), WC_Erede_Post_Meta_Field::Tid, true ));

		return $response->isSucess();
	}

	private function resetRefund($order) {		
		$refunds = $order->get_refunds();
				
		wc_delete_shop_order_transients( $order->get_id() );
		wp_delete_post( $refunds[0]->get_id() );
		do_action( 'woocommerce_refund_deleted', $refunds[0]->get_id(), $order->get_id() );
	}

	private static function resetOrder($order, $old_status){
		global $wpdb;

		WC_Erede_Functions::updateStatus($order, $old_status);
		
		//Reset Comments
		$comment_id = $wpdb->get_var( $wpdb->prepare( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID = %d ORDER BY comment_date_gmt DESC LIMIT 2", $order->get_id() ) );
		
		if ( $comment_id ) {
			wp_delete_comment( $comment_id, true );
		}
	}

	private static function addSessionOrderFailed( $order_id , $status){
		$erede_update_status = $_SESSION[WC_Erede_Sessions::UpdateStatus];
		$erede_update_status = array(
			'ID'     => $erede_update_status['ID'].'#'.$order_id.' ',
			'status' => $status,
		);

		$_SESSION[WC_Erede_Sessions::UpdateStatus]=$erede_update_status;
	}
}
