<?php
/**
 * WP Requirements
 *
 * Utility to check current PHP version, WordPress version and PHP extensions.
 *
 * Modified class name to include in eRede.
 *
 * @package WC_Erede_Requirements
 * @version 
 * @author  
 * @link    
 * @license GPL2+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 

if ( ! class_exists( 'WC_Erede_Requirements' ) ) {

	class WC_Erede_Requirements {

		/**
		 * Plugin name.
		 *
		 * @access private
		 * @var string
		 */
		private $name = '';

		/**
		 * Plugin main file.
		 *
		 * plugin_basename( __FILE__ )
		 *
		 * @access private
		 * @var string
		 */
		private $plugin = '';

		/**
		 * WordPress.
		 *
		 * @access private
		 * @var bool
		 */
		private $wp = true;

		/**
		 * PHP.
		 *
		 * @access private
		 * @var bool
		 */
		private $php = true;

		/**
		* Woocommerce
		*
		* @access private
		* @var bool
		*/
		private $wooCommerce = true;

		/**
		 * PHP Extensions.
		 *
		 * @access private
		 * @var bool
		 */
		private $extensions = true;

		/**
		 * Requirements to check.
		 *
		 * @access private
		 * @var array
		 */
		private $requirements = array();

		/**
		 * Results failures.
		 *
		 * Associative array with requirements results.
		 *
		 * @access private
		 * @var array
		 */
		private $failures = array();

		/**
		 * Admin notice.
		 *
		 * @access private
		 * @var string
		 */
		private $notice = '';

		/**
		 * Run checks.
		 *
		 * @param string $name         The plugin name.
		 * @param string $plugin       Output of `plugin_basename( __FILE__ )`.
		 * @param array  $requirements Associative array with requirements.
		 */
		public function __construct( $name, $plugin, $requirements ) {

			$this->name = htmlspecialchars( strip_tags( $name ) );
			$this->plugin = $plugin;
			$this->requirements = $requirements;

			if ( ! empty( $requirements ) && is_array( $requirements ) ) {

				$failures = $extensions = array();

				$requirements = array_merge(
					array(
						'WordPress'  => '',
						'PHP'        => '',
						'Woocommerce' => '',
						'Extensions' => '',
					), $requirements
				);

				// Check for WordPress version.
				if ( $requirements['WordPress'] && is_string( $requirements['WordPress'] ) ) {
					if ( function_exists( 'get_bloginfo' ) ) {
						$wp_version = get_bloginfo( 'version' );
						if ( version_compare( $wp_version, $requirements['WordPress'] ) === - 1 ) {
							$failures['WordPress'] = $wp_version;
							$this->wp = false;
						}
					}
				}

				// Check fo PHP version.
				if ( $requirements['PHP'] && is_string( $requirements['PHP'] ) ) {
					if ( version_compare( PHP_VERSION, $requirements['PHP'] ) === -1 ) {
						$failures['PHP'] = PHP_VERSION;
						$this->php = false;
					}
				}

				// Check Woocommerce version.				
				if ( $requirements['Woocommerce'] && is_string( $requirements['Woocommerce'] ) ) {					
					$wooCommerceVersion = $this->getWoocommerceVersion();
					if($wooCommerceVersion == NULL || version_compare( $wooCommerceVersion, $requirements['Woocommerce'] ) === -1 ) {
						$failures['Woocommerce'] = $wooCommerceVersion;
						$this->wooCommerce = false;
					}
				}

				// Check fo PHP Extensions.
				if ( $requirements['Extensions'] && is_array( $requirements['Extensions'] ) ) {
					foreach ( $requirements['Extensions'] as $extension ) {
						if ( $extension && is_string( $extension ) ) {
							$extensions[ $extension ] = extension_loaded( $extension );
						}
					}
					if ( in_array( false, $extensions ) ) {
						foreach ( $extensions as $extension_name => $found  ) {
							if ( $found === false ) {
								$failures['Extensions'][ $extension_name ] = $extension_name;
							}
						}
						$this->extensions = false;
					}
				}

				$this->failures = $failures;

			} else {

				trigger_error( 'WP Requirements: the requirements are invalid.', E_USER_ERROR );

			}
		}

		/**
		 * Get woocommerce version
		 *
		 */
		private function getWoocommerceVersion() {
			
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


		/**
		 * Get requirements results.
		 *
		 * @return array
		 */
		public function failures() {
			return $this->failures;
		}

		/**
		 * Check if versions check pass.
		 *
		 * @return bool
		 */
		public function pass() {
			if ( in_array( false, array(
				$this->wp,
				$this->php,
				$this->extensions,
				$this->wooCommerce
			) ) ) {
				return false;
			}
			return true;
		}

		/**
		 * Notice message.
		 *
		 * @param  string $message An additional message.
		 *
		 * @return string
		 */
		public function getNotice( $message = '' ) {

			$notice   = '';
			$name     = $this->name;
			$failures = $this->failures;

			if ( ! empty( $failures ) && is_array( $failures ) ) {

				$notice  = '<div class="error">' . "\n";
				$notice .= "\t" . '<p>' . "\n";
				$notice .= '<strong>' . sprintf( '%s could not be activated.', $name ) . '</strong><br>';

				foreach ( $failures as $requirement => $found ) {

					$required = $this->requirements[ $requirement ];

					if ( 'Extensions' == $requirement ) {
						if ( is_array( $found ) ) {
							$notice .= sprintf( 
									'Required PHP Extension(s) not found: %s.', 
									join( ', ', $found ) 
								) . '<br>';
						}
					} else {
						$notice .= sprintf( 
								'Required %1$s version: %2$s - Version found: %3$s',
								$requirement, 
								$required, 
								$found 
							) . '<br>';
					}

				}

				$notice .= '<em>' . sprintf( 'Please update to meet %s requirements.', $name ) . '</em>' . "\n";
				$notice .= "\t" . '</p>' . "\n";
				if ( $message ) {
					$notice .= $message;
				}
				$notice .= '</div>';
			}

			return $notice;
		}

		/**
		 * Print notice.
		 */
		public function printNotice() {
			echo $this->notice;
		}

		/**
		 * Deactivate plugin.
		 */
		public function deactivatePlugin() {
			if ( function_exists( 'deactivate_plugins' ) && function_exists( 'plugin_basename' ) ) {
				deactivate_plugins( $this->plugin );
			}
		}

		/**
		 * Deactivate plugin and display admin notice.
		 *
		 * @param string $message An additional message in notice.
		 */
		public function halt( $message = '' ) {

			$this->notice = $this->getNotice( $message );

			if ( $this->notice && function_exists( 'add_action' ) ) {

				add_action( 'admin_notices', array( $this, 'printNotice' ) );
				add_action( 'admin_init', array( $this, 'deactivatePlugin' ) );

				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}
		}

	}

}
