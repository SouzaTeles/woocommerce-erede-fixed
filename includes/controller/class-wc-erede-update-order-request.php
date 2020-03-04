<?php
include_once dirname ( __FILE__ ) . '/../controller/class-wc-erede-base-request.php';
class WC_Erede_Update_Order_Request extends WC_Erede_Base_Request {
	public function __construct() {
		parent::__construct ();
		$this->includes ();
	}
	private function includes() {
		include_once dirname ( __FILE__ ) . '/../model/class-wc-erede-find-request.php';
		include_once dirname ( __FILE__ ) . '/../model/class-wc-erede-find-response.php';
		include_once dirname ( __FILE__ ) . '/../service/class-wc-erede-api.php';
		include_once dirname ( __FILE__ ) . '/../service/class-wc-erede-api-find.php';
	}
	public function execute() {
		$post_id = $_POST [WC_Erede_Post_Data::OrderId];
		$order = new WC_Order ( $post_id );
		
		// Call API Find
		$erede_Find_Request = new WC_Erede_Find_Request( null, $order->get_id() );
		$erede_Api_Find = new WC_Erede_Api_Find();
		$response = $erede_Api_Find->callApi($order, $erede_Find_Request);
		
		if ($response->isSucess()) {
			$newStatus = WC_Erede_Functions::mapEredeStatusToWCStatus ( $response->getAuthorization ()->getStatus () );
			
			$_SESSION [WC_Erede_Sessions::OrderUpdated] = esc_html__ ( "Order updated.", 'messages' );

			if ($newStatus != $order->get_status ()) {
				$_SESSION [WC_Erede_Sessions::OrderIdUpdateFromApi . $post_id] = true;
				$order->update_status ( $newStatus, "" );
			}
		}
		
		parent::returnAjaxMessage ( $response );
	}
}

$request = new WC_Erede_Update_Order_Request();
$request->execute();

?>
