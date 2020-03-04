<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WC_Erede_Menu {

	static $instance;
	public $trasaction_log_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'setScreen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'addMenu' ] );
  		include_once dirname( __FILE__ ) .  '/../controller/class-wc-erede-list-log.php';
		
	}

	public static function setScreen( $status, $option, $value ) {
		return $value;
	}

	public function addMenu() {
		$hook = add_submenu_page( 'woocommerce', __( 'WooCommerce e.Rede', 'woocommerce' ),  __( 'e.Rede logs ', 'woocommerce' ) , 'manage_options', 'class-wc-erede-list-table-log', [ $this, 'pluginSettingsPage' ] );
		add_action( "load-$hook", [ $this, 'screenOption' ] );
	}	

	/** Singleton instance */
	public static function getInstance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	 /**
	 * Screen options
	 */
	public function screenOption() {
		$this->trasaction_log_obj = new WC_Erede_List_Log();
		$this->trasaction_log_obj->screenOption();
	}

	public function pluginSettingsPage() {
		$this->trasaction_log_obj->pluginSettingsPage();
	}
}

?>  