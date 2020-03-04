<?php
/**
 * Plugin Name: e.Rede WooCommerce
 * Plugin URI: http://www.userede.com.br
 * Description: Descrição do Plugin 
 * Author: e.rede
 * Author URI: 
 * Version: 1.0
 * License: GPLv2 or later
 * Text Domain: erede-woocommerce
 * Domain Path: /languages/
 * Copyright: 
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	// Plugin name
	if ( ! defined( 'EREDE_NAME' ) ) {
		define( 'EREDE_NAME', 'eRede for Woocommerce' );
	}

	if ( ! class_exists( 'Woocommerce_Gateway_Erede' ) ) :

		class Woocommerce_Gateway_Erede {

			/**
			* Instance of this class.
			*
			* @var object
			*/
			protected static $instance = null;

			/**
			* Constructor
			**/
			private function __construct() {

				// Load plugin text domain.
				add_action( 'init', array( $this, 'loadPluginTextDomain' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'registerScripts' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

				$this->checkVersion();
				$this->initConfigs();
				$this->initOrderList();
			}

			/**
			* Load the plugin text domain for translation.
			*/
			public function loadPluginTextDomain() {
				load_plugin_textdomain( 'messages', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			}

			/**
			* Get an instance
			*/
			public static function getInstance() {
				// If the single instance hasn't been set, set it now.
				if ( null == self::$instance ) {
					self::$instance = new self;
				}

				include_once dirname( __FILE__ ) . '/includes/controller/class-wc-erede-menu.php';
				
				WC_Erede_Menu::getInstance();

				return self::$instance;
			}

			/**
			* Verifica as versões do PHP, WordPress e Woocommerce.
			**/
			private function checkVersion() {

				// Plugin requirements class.
				require_once 'includes/class-wc-erede-requirements.php';

				// Check plugin requirements before loading plugin.
				$this_plugin_checks = new WC_Erede_Requirements( EREDE_NAME, plugin_basename( __FILE__ ), array(
						'PHP'        => '5.3.3',
						'WordPress'  => '4.2',
						'Woocommerce' => '2.3.13',
						'Extensions' => array('soap'),
					) );
				if ( $this_plugin_checks->pass() === false ) {
					$this_plugin_checks->halt();

					return;
				}
			}

			/**
			* Initialize erede payment configs
			**/
			private function initConfigs() {
				include_once dirname( __FILE__ ) . '/includes/class-wc-erede-payment-gateway.php';
				include_once dirname( __FILE__ ) . '/includes/model/class-wc-erede-config-static.php';

				
				

				function addEredePaymentGateway( $methods ) {
					$methods[] = 'WC_erede_payment_gateway';
					return $methods;
				}

				add_filter( 'woocommerce_payment_gateways', 'addEredePaymentGateway' );
			}

			public function registerScripts() {
				// Styles.
				wp_register_style( 'woocommerce-erede-css', plugins_url( 'woocommerce-erede/assets/css/erede.css' ), array(), NULL );
				wp_enqueue_style( 'woocommerce-erede-css' );

				wp_register_style( 'woocommerce-erede-jquery-ui-css', plugins_url( 'woocommerce-erede/assets/css/jquery-ui.min.css' ), array(), NULL );
				wp_enqueue_style( 'woocommerce-erede-jquery-ui-css' );

				// JS
				//wp_register_script( 'woocommerce-erede-jquery', plugins_url( 'woocommerce-erede/assets/js/jquery.js' ), array(), NULL );
				//wp_enqueue_script( 'woocommerce-erede-jquery' );

				wp_register_script( 'woocommerce-erede-jquery-ui-js', plugins_url( 'woocommerce-erede/assets/js/jquery-ui.min.js' ), array(), NULL, true );
				wp_enqueue_script( 'woocommerce-erede-jquery-ui-js' );

				wp_register_script( 'woocommerce-erede-jquery-mask', plugins_url( 'woocommerce-erede/assets/js/jquery.mask.min.js' ), array('wc-credit-card-form'), NULL, true );
				wp_enqueue_script( 'woocommerce-erede-jquery-mask' );

				// $version_compare = version_compare( WC_Erede_Functions::getWoocommerceVersion(), '2.6.8' );
				// if($version_compare <= 0) {	
					wp_register_script( 'woocommerce-erede-checkout-js', plugins_url( 'woocommerce-erede/assets/js/erede.js' ), array(), NULL, true );
					wp_enqueue_script( 'woocommerce-erede-checkout-js' );
				//}
			}

			/**
			* Add the gateway to WooCommerce.
			*
			* @param   array $methods WooCommerce payment methods.
			*
			* @return  array          Payment methods with eRede.
			*/
			public function add_gateway( $methods ) {
				array_push( $methods, 'WC_ERede_Credit_Gateway');
				return $methods;
			}

			/**
			* Get templates path.
			*
			* @return string
			*/
			public static function getTemplatesPath() {
				return plugin_dir_path( __FILE__ ) . 'templates/';
			}

			/**
			* Initialize erede database
			**/
			public static function initDatabase(){
				// Database functions
				require_once 'includes/dao/class-wc-erede-database.php';

				register_activation_hook( __FILE__, 'install' );
			}

			/**
			* Initialize erede order list
			**/
			public static function initOrderList(){

				session_start(); 

				include_once  'includes/controller/class-wc-erede-admin-notice.php';
				include_once  'includes/controller/class-wc-erede-order-status.php';
				include_once  'includes/class-wc-erede-payment-gateway.php';
				add_action('woocommerce_order_status_changed', array('WC_Erede_Order_Status', 'orderStatusChanged'),10 ,3);
				add_action('admin_notices', array( 'WC_Erede_Admin_Notice', 'showMessages' ) ,10);
			}

			/**
			* Adds plugin action links
			*
			* @since 1.0.0
			*/
			public function plugin_action_links( $links ) {
				$setting_link = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_erede_payment_gateway');

				$plugin_links = array(
					'<a href="' . $setting_link . '">' . __( 'Settings', 'messages' ) . '</a>',
				);
				return array_merge( $plugin_links, $links );
			}
		}

		Woocommerce_Gateway_Erede::initDatabase();
		add_action( 'plugins_loaded', array( 'Woocommerce_Gateway_Erede', 'getInstance' ), 0 );

	endif;
}