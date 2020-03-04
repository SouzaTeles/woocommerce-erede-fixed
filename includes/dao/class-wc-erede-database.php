<?php

if ( ! defined( 'ABSPATH' ) ) {
		exit;
}

function install() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'ered_log';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		erede_log_id BIGINT NOT NULL AUTO_INCREMENT,
		erede_order_id VARCHAR(100) NOT NULL,
		erede_is_transaction_ok BOOLEAN NOT NULL,
		erede_card_holder_name VARCHAR(100) NULL,
		erede_message_result VARCHAR(255) NULL,
		erede_environment VARCHAR(20) NULL,
		erede_status VARCHAR(20) NULL,
		erede_action VARCHAR(20) NULL,
		erede_log_date datetime NULL,
		erede_tid BIGINT NULL,
		erede_sqn INT NULL,
		erede_transaction_date datetime NULL,
		erede_total DECIMAL(10,2) NULL,
		erede_installments SMALLINT NULL,
		erede_currency VARCHAR(20) NULL,
		erede_card_bin VARCHAR(6) NULL,
		erede_last_card_digits VARCHAR(4) NULL,	 
		PRIMARY KEY  (erede_log_id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

?>