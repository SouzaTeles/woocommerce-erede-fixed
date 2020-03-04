<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class WC_Erede_Log_Helper {

		private static $instance = null;

		private $_handles;

		public function __construct() {
	 		$this->_handles = array();			
	 	}

		/**
		* Get logger.
		*
		* @return WC_Logger instance.
		*/
		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new WC_Erede_Log_Helper();			
			}
			return self::$instance;
		}

		public function getHandle() {
			return "erede-" . date("Ymd");
		}	

		/**
		* Get a log file path.
		*
		* @param string $handle name.
		* @param string $mode
		*/		
		private function getLogFilePath( $handle ) {
			return trailingslashit( WC_LOG_DIR ) . $handle . '.log';			
		}			 

		/**
		* Destructor.
		*/
		public function __destruct() {
			foreach ( $this->_handles as $handle ) {
				if ( is_resource( $handle ) ) {
					fclose( $handle );
				}
			}
		}

		/**
		* Open log file for writing.
		*
		* @param string $handle
		* @param string $mode
		* @return bool success
		*/
		private function open( $handle, $mode = 'a' ) {
			if ( isset( $this->_handles[ $handle ] ) ) {
				return true;
			}

			$file = $this->getLogFilePath( $handle );

			if ( $this->_handles[ $handle ] = fopen( $file, $mode ) ) {
				return true;
			}

			return false;
		}

		public function close() {
			$this->closeResource($this->getHandle());
		}

		/**
		* Close a handle.
		*
		* @param string $handle
		* @return bool success
		*/
		private function closeResource( $handle ) {
			$result = false;

			if ( is_resource( $this->_handles[ $handle ] ) ) {
				$result = fclose( $this->_handles[ $handle ] );
				unset( $this->_handles[ $handle ] );
			}

			return $result;
		}

		/**
		* Add a log entry to chosen file.
		*
		* @param string $handle
		* @param string $message
		*
		* @return bool
		*/
		public function add( $message ) {
			$result = false;

			$handle = $this->getHandle();

			if ( $this->open( $handle ) && is_resource( $this->_handles[ $handle ] ) ) {
				$time   = date_i18n( 'm-d-Y @ H:i:s -' ); // Grab Time
				$result = fwrite( $this->_handles[ $handle ], $time . " " . $message . "\n" );
			}

			do_action( 'woocommerce_log_add', $handle, $message );

			return false !== $result;
		}

		/**
		* Clear entries from chosen file.
		*
		* @param string $handle
		*
		* @return bool
		*/
		public function clear() {
			$result = false;

			$handle = $this->getHandle();

			// Close the file if it's already open.
			$this->closeResource( $handle );

			/**
			* $this->open( $handle, 'w' ) == Open the file for writing only. Place the file pointer at the beginning of the file,
			* and truncate the file to zero length.
			*/
			if ( $this->open( $handle, 'w' ) && is_resource( $this->_handles[ $handle ] ) ) {
				$result = true;
			}

			do_action( 'woocommerce_log_clear', $handle );

			return $result;
		}							

	}