<?php

if ( ! defined( 'ABSPATH' ) ) {
		exit;
}

include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-sessions.php';

class WC_Erede_Admin_Notice {
    
    /**
	 * Show confirmation message that order status changed for number of orders.
	 */
	public static function showMessages() {

		if(!empty($_SESSION[WC_Erede_Sessions::OrderUpdated]))
		{
			$div = '<div id="message" class="updated notice notice-success"><p>' . $_SESSION[WC_Erede_Sessions::OrderUpdated] . '</p></div>';

			echo '<script type="text/javascript">
					jQuery( document ).ready(function() {
						(function(){
							if(!jQuery("#message.updated.notice.notice-success").length)
							{
								var element = \'' . $div . '\';
								jQuery(element).insertBefore("#post");
							}
						})();
					});
					</script>';
		}
				
		if(!self::isSessionOrderFailed()){
			echo '<div class="error"><p>'
				 .self::getMessageSessionOrderFailed().' '
				 .self::getIdSessionOrderFailed() 
				 .'</p></div>'
				 .'<script type="text/javascript">
					var cleanOrderUpdatedMessageOnApiError = function () { 
						var elem = document.getElementsByClassName("updated notice notice-success is-dismissible")[0];
						elem.remove();
					} 
					try {
						window.onload = function () { 
							cleanOrderUpdatedMessageOnApiError();
						}
					} catch(e) {}
				   </script>';


		} elseif(!self::isSessionUpdateOrderStatusFromApiFailed()) {
			echo '<div class="error"><p>' . sprintf( __('Could not update transaction', 'messages')) . '</p></div>' 
			    .'<script type="text/javascript">
			 		var cleanOrderUpdatedMessageOnApiError = function () { 
				  		var elem = document.getElementsByClassName("updated notice notice-success is-dismissible")[0];
    			  		elem.remove();
				    } 
				 </script>';		
		}

		//Reset Session
		self::resetSessionOrderFailed();
	}

    private static function isSessionOrderFailed() {
		return empty($_SESSION[WC_Erede_Sessions::UpdateStatus]);
	}

    private static function isSessionUpdateOrderStatusFromApiFailed() {
		return empty($_SESSION[WC_Erede_Sessions::OrderIdUpdateFromApiError]);
	}

    private static function getIdSessionOrderFailed() {
		$erede_update_status = $_SESSION[WC_Erede_Sessions::UpdateStatus];
		if(isset($erede_update_status)){
			return $erede_update_status['ID'];
		}
	}

    private static function getMessageSessionOrderFailed() {

		$erede_update_status = $_SESSION[WC_Erede_Sessions::UpdateStatus];
		if(!isset($erede_update_status)){
			return '';
		}
		
		switch ( $erede_update_status['status'] ) {
			case 'completed' :
				return esc_html__( 'e.Rede order not completed. Confirm order before completion', 'messages' );
				break;
			case 'processing' :
				return esc_html__( 'An error occurred to confirm the order', 'messages' );
				break;
			case 'refunded' :
				return esc_html__( "Could not refund transaction:", 'messages' );
				break;
			default : 
				return esc_html__( "It's not allowed change status of e.Rede order to selected status:", 'messages' );
				break;
		}
	}

    private static function resetSessionOrderFailed() {
		unset($_SESSION[WC_Erede_Sessions::OrderUpdated]);
		unset($_SESSION[WC_Erede_Sessions::UpdateStatus]);
		unset($_SESSION[WC_Erede_Sessions::OrderIdUpdateFromApiError]);
	}
}

?>