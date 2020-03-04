<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Erede_Functions{

	public static function getWoocommerceVersion() {
		if ( ! function_exists( 'get_plugins' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
			$plugin_folder = get_plugins( '/' . 'woocommerce' );
			$plugin_file = 'woocommerce.php';
			
			if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
				return $plugin_folder[$plugin_file]['Version'];
			} else {
				return NULL;
		}
	}

	public static function getWoocommerceVersionAsNumber() {
		$version = self::getWoocommerceVersion();
		if($version == null) {
			return 0;
		}

		return intval(str_replace('.', '',$version));
	}

	public static function isValidCPF($cpf = null){
		if(empty($cpf)) {
			return false;
		}

		$cpf = preg_replace('/[^0-9]/s', '', $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

		if (strlen($cpf) != 11) {
			return false;
		}
		else if ($cpf == '00000000000' ||
				$cpf == '11111111111' ||
				$cpf == '22222222222' ||
				$cpf == '33333333333' ||
				$cpf == '44444444444' ||
				$cpf == '55555555555' ||
				$cpf == '66666666666' ||
				$cpf == '77777777777' ||
				$cpf == '88888888888' ||
				$cpf == '99999999999') {
					return false;
				} else {

					for ($t = 9; $t < 11; $t++) {

						for ($d = 0, $c = 0; $c < $t; $c++) {
							$d += $cpf{$c} * (($t + 1) - $c);
						}
						$d = ((10 * $d) % 11) % 10;
						if ($cpf{$c} != $d) {
							return false;
						}
					}

					return true;
				}
	}

	public static function isValidCardNumber($number) {
		$pattern = "/[^0-9]/s";
		$number = str_replace(' ', '', $number);
		if ($number == null || preg_match($pattern, $number)) {
			return false;
		}

		$s1 = 0;
		$s2 = 0;
		$number = array_reverse(str_split($number));

		for ($i=0; $i < count($number); $i++) {
			$digit = $number[$i];

			if($i %2 == 0) {
				$s1 += $digit;
			} else{
				$s2 += 2* $digit;
				if($digit >= 5){
					$s2 -= 9;
				}
			}
		}

		return ($s1 + $s2) % 10 == 0;
	}

	public static function isCompositeName($fullname){
		return preg_match('[\w\s\w]', $fullname);
	}

	public static function startsWithValue($pattern, $value) {
		$ptt = '/^' . $pattern . '/';
		return preg_match($ptt, $value);
	}

	public static function isValidStatusTransition($oldStatus, $newStatus) {
		$statusTransitions = WC_Erede_Config_Static::getStatusTransitions($oldStatus, $newStatus);
		
		if(!array_key_exists($oldStatus, $statusTransitions)) {
			return false;
		}
		
		$oldStatusTransition = $statusTransitions[$oldStatus];

		return in_array($newStatus, $oldStatusTransition);		
	}

	public static function updateStatus($order, $new_status){
		$order->post_status = 'wc-' . $new_status;
		$update_post_data  = array(
		'ID'          => $order->get_id(),
		'post_status' => $order->post_status,
		);

		if ( 'pending' === $old_status) {
		$update_post_data[ 'post_date' ]     = current_time( 'mysql', 0 );
		$update_post_data[ 'post_date_gmt' ] = current_time( 'mysql', 1 );
		}

		wp_update_post( $update_post_data );
	}

	public static function mapEredeStatusToWCStatus($eredeStatus) {
		include_once dirname( __FILE__ ) . '/constants/class-wc-erede-status.php';
		switch ($eredeStatus) {
			case "Approved":
				return WC_Erede_Status::Processing;
				break;
			case "Denied": 
				return WC_Erede_Status::Failed;
				break;
			case "Canceled":
				return WC_Erede_Status::Refunded;
				break;
			case "Pending":
				return WC_Erede_Status::OnHold;
				break;
			default:
				return null;				
				break;
		}
	}

	/**
	 * Process the order status.
	 *
	 * @param WC_Order $Order  Order data.
	 */
	public static function getCheckoutStatus($order, $request, $response) {
			$status = "";
			$isCredz = WC_Erede_Config_Static::getPluginConfig()->getCardBrand($request->getCardNumber()) == "Credz" ? true : false;
			$isCapture = WC_Erede_Config_Static::getPluginConfig()->getCaptureFlag();


			if(!($response == null || empty($response)) &&  $response->isSucess()) {

				if(!$isCapture && !$isCredz){
					$status = WC_Erede_Status::OnHold;
				}
				else {
					$status = WC_Erede_Status::Processing;
				} 
			}
			else{
				$status = WC_Erede_Status::Error;
			}
			
			return $status;
	}

	public static function mask($val, $mask) {
 		$maskared = '';
 		$k = 0;
		for($i = 0; $i<=strlen($mask)-1; $i++) {
 			if($mask[$i] == '#') {
 				if(isset($val[$k]))
 					$maskared .= $val[$k++];
		    } else {
 				if(isset($mask[$i]))
 					$maskared .= $mask[$i];
			}
 		}

 		return $maskared;
	}
}
?>