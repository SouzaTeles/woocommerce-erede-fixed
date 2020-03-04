<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

include_once dirname( __FILE__ ) . '/../utils/constants/class-wc-erede-status.php';

class WC_Erede_Config_Static {
    
	private static $pluginConfig;

	public static function getStatusTransitions($oldStatus, $newStatus) {
		return array(
			WC_Erede_Status::OnHold => array(WC_Erede_Status::Processing, WC_Erede_Status::Refunded, WC_Erede_Status::Cancelled),
			WC_Erede_Status::Processing => array(WC_Erede_Status::Refunded, WC_Erede_Status::Completed),
			WC_Erede_Status::Refunded => array(WC_Erede_Status::Completed),
			WC_Erede_Status::Completed => array(WC_Erede_Status::Refunded)
		);
	}

	public static function setPluginConfig($pluginConfig) {
		self::$pluginConfig = $pluginConfig;
	}

	public static function getPluginConfig() {
		include_once dirname( __FILE__ ) . '/class-wc-erede-config.php';			
		
		return new WC_Erede_Config();
	}
}
