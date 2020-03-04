<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WC_Erede_List_Log extends WP_List_Table {

	static $instance;

    /** Class constructor */
    public function __construct() {
		include_once dirname( __FILE__ ) .  '/../model/class-wc-erede-log-filter.php';
    	include_once dirname( __FILE__ ) .  '/../dao/class-wc-erede-dao-factory.php';
		include_once dirname( __FILE__ ) .  '/../model/class-wc-erede-log-filter.php';
		include_once dirname( __FILE__ ) .  '/../model/class-wc-erede-log.php';
		include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-config-date-format.php';
		include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-date-format.php';
		include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-actions.php';
		include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-environment.php';
		include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-sessions.php';
		include_once dirname( __FILE__ ) .  '/../utils/constants/class-wc-erede-post-data.php';

        parent::__construct( [
            'singular' => __( 'Transaction Log', 'messages' ), 
            'plural'   => __( 'Transaction Logs', 'messages' ), 
            'ajax'     => false //does this table support ajax?
        ] );
	}

	/**
	* @override function
	*/
    protected function extra_tablenav($which) {
		$logFilterSession = $this->getLogFilterFromSession();

		if($logFilterSession == null) {
			$logFilterSession =  new WC_Erede_Log_Filter;
		}			

        echo '<div class="alignleft actions">';

        if ('top' === $which && !is_singular()) {

            echo '<input type="text" size="7" name="search_id_log" id="search_id_log" value="' . $logFilterSession->getLogId() . '" placeholder="' . esc_html__("Log ID", 'messages') . '" />';
            echo '<input type="text" size="15" name="search_order_id" id="search_order_id" value="' . $logFilterSession->getOrderId() . '" placeholder="' . esc_html__("Order ID", 'messages') . '"  />';

			echo '<input type="text" size="9" placeholder="'. $this->getDateFormatForPlaceholder() .'"  value="' . $this->formatDateFromDB($logFilterSession->getStartDate()) . '" name="search_start_date" id="search_start_date"  />';
			echo '<input type="text" size="9" placeholder="'. $this->getDateFormatForPlaceholder() .'"  value="' . $this->formatDateFromDB($logFilterSession->getEndDate())   . '" name="search_end_date" id="search_end_date"  />';

			echo '<select name="search_message" id="search_message">';
			echo '  <option value="all">' . esc_html__("Message(All)", 'messages') . '</option>';
			echo '  <option ' . ($logFilterSession->getMessage() == 'sucess' ? 'selected ' : '') . '  value="sucess">' . esc_html__("Success", 'messages') . '</option>';
			echo '  <option ' . ($logFilterSession->getMessage() == 'error' ? 'selected ' : '') . ' value="error">' . esc_html__("Error", 'messages') . '</option>';
			echo '</select>';

            echo '<select name="search_environment" id="search_environment">';
            echo '  <option value="all">' . esc_html__("Environment(All)", 'messages') . '</option>';
            echo '  <option ' . ($logFilterSession->getEnvironment() == WC_Erede_Environment::Test ? 'selected ' : '')       . ' value="'.WC_Erede_Environment::Test.'">' . esc_html__("Test", 'messages') . '</option>';
            echo '  <option ' . ($logFilterSession->getEnvironment() == WC_Erede_Environment::Production ? 'selected ' : '') . ' value="'.WC_Erede_Environment::Production.'">' . esc_html__("Production", 'messages') . '</option>';
            echo '</select>';

		    echo '<select id="search_stage" name="search_stage">';
            echo '  <option value="all">' . esc_html__("Stage(All)", 'messages') . '</option>';
            echo '  <option ' . ($logFilterSession->getStage() == WC_Erede_Actions::Checkout ? 'selected ' : '') .  ' value="'. WC_Erede_Actions::Checkout .'">' . esc_html__("Checkout", 'messages') . '</option>';
            echo '  <option ' . ($logFilterSession->getStage() == WC_Erede_Actions::Capture ? 'selected ' : '')   . ' value="'. WC_Erede_Actions::Capture .'">' . esc_html__("Capture", 'messages') . '</option>';
            echo '  <option ' . ($logFilterSession->getStage() == WC_Erede_Actions::Update ? 'selected ' : '') 	  . ' value="'. WC_Erede_Actions::Update .'">' . esc_html__("Update", 'messages') . '</option>';
            echo '  <option ' . ($logFilterSession->getStage() == WC_Erede_Actions::Refund ? 'selected ' : '') 	  . ' value="'. WC_Erede_Actions::Refund .'">' .esc_html__("Refund", 'messages') . '</option>';
            echo '</select>';

			echo '<script> 
			 		jQuery(document).ready(function() { 
  						jQuery("#search_id_log").mask("999999999999"); 
						jQuery("#search_order_id").mask("999999999999999"); 
					}); 
				  </script>';

            submit_button(__('Filter', 'messages'), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
        }

        if ( $this->is_trash && current_user_can( get_post_type_object( $this->screen->post_type )->cap->edit_others_posts ) ) {
            submit_button( __('Empty Trash', 'messages'), 'apply', 'delete_all', false );
        }

		echo '</div>';

		//// Log Detail Content
		include_once dirname( __FILE__ ) .  '/../../templates/class-wc-erede-log-detail-template.php';
		include_once dirname( __FILE__ ) .  '/../../templates/class-wc-erede-log-detail-ajax.php';
		////
    }

	private function getDateFormatForPlaceholder() {
		$format = get_option('date_format');

		switch ($format) {
			case WC_Erede_Config_Date_Format::YMD:
				return __("yyyy-mm-dd", "messages");
			case WC_Erede_Config_Date_Format::MDY:
				return __("mm/dd/yyyy", "messages");
			case WC_Erede_Config_Date_Format::FJY:
				return __("mm/dd/yyyy", "messages");
			case WC_Erede_Config_Date_Format::DMY :
				return __("dd/mm/yyyy", "messages");
			default:
				return __("dd/mm/yyyy", "messages");		
		}

	}

	private function formatdDateToDB($date) {

		$format = get_option('date_format');

		if(!empty($date)) {
			switch ($format) {
				case WC_Erede_Config_Date_Format::YMD:
					return $date;
				case WC_Erede_Config_Date_Format::MDY:
					$dateExploded = explode("/", $date);
					return $dateExploded[2] . '-' . $dateExploded[0] . '-' . $dateExploded[1];
				case WC_Erede_Config_Date_Format::FJY:
					$dateExploded = explode("/", $date);
					return $dateExploded[2] . '-' . $dateExploded[0] . '-' . $dateExploded[1];
				case WC_Erede_Config_Date_Format::DMY :
					$dateExploded = explode("/", $date);
					return $dateExploded[2] . '-' . $dateExploded[1] . '-' . $dateExploded[0];
				default:
					$dateExploded = explode("/", $date);
					return $dateExploded[2] . '-' . $dateExploded[1] . '-' . $dateExploded[0];		
			}
		}
	}

	private function formatDateFromDB($date) {
		if(!empty($date)) {
			$format = get_option('date_format');
			if($format != WC_Erede_Config_Date_Format::YMD) {
				$date = str_replace("-","/",$date);
			}
			switch ($format) {
				case WC_Erede_Config_Date_Format::YMD:
					return $date;
				case WC_Erede_Config_Date_Format::MDY:
					$dateExploded = explode("/", $date);
					return $dateExploded[1] . '/' . $dateExploded[2] . '/' . $dateExploded[0];
				case WC_Erede_Config_Date_Format::FJY:
					$dateExploded = explode("/", $date);
					return $dateExploded[1] . '/' . $dateExploded[2] . '/' . $dateExploded[0];
				case WC_Erede_Config_Date_Format::DMY :
					$dateExploded = explode("/", $date);
					return $dateExploded[2] . '/' . $dateExploded[1] . '/' . $dateExploded[0];
				default:
					$dateExploded = explode("/", $date);
					return $dateExploded[2] . '/' . $dateExploded[1] . '/' . $dateExploded[0];		
			}			
		}
	}

	private function saveIntoSessionLogFilterData() {
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$_SESSION[WC_Erede_Sessions::FilterLogFields] = serialize($this->extractLogFilterFromRequest());
		} else if($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['paged']) && !isset($_GET['order'])) {
			unset($_SESSION[WC_Erede_Sessions::FilterLogFields]);
		}
	}

	public function savePageDataIntoSession($per_page, $page_number) {
		$logFilter = $this->getLogFilterFromSession();

		if($logFilter == null) {
			$logFilter = new WC_Erede_Log_Filter;	
		}

		$logFilter->setPerPage($per_page);
		$logFilter->setPageNumber($page_number);  
		$logFilter->setOrderBy(isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : null);
		$logFilter->setOrder(isset($_REQUEST['order']) ? $_REQUEST['order'] : null);

		$_SESSION[WC_Erede_Sessions::FilterLogFields] = serialize($logFilter);
	}

	private function extractLogFilterFromRequest() {
		extract($_POST, EXTR_PREFIX_SAME, "wddx");
		$eredeLogFilter = new WC_Erede_Log_Filter();

		$eredeLogFilter->setLogId($search_id_log);
		$eredeLogFilter->setOrderId($search_order_id);
		$eredeLogFilter->setMessage($search_message);
		$eredeLogFilter->setEnvironment($search_environment);
		$eredeLogFilter->setStage($search_stage);
		$eredeLogFilter->setStartDate($this->formatdDateToDB($search_start_date));
		$eredeLogFilter->setEndDate($this->formatdDateToDB($search_end_date));

		return $eredeLogFilter;
	}

	private function getLogFilterFromSession() {
		if(isset($_SESSION[WC_Erede_Sessions::FilterLogFields])) {
			return unserialize($_SESSION[WC_Erede_Sessions::FilterLogFields]);
		} else {
			return null;
		}
	}		

	/**
	 * Retrieve resumed logs from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public function getLogs($per_page = 5, $page_number = 1) {
		$this->saveIntoSessionLogFilterData();

		$this->savePageDataIntoSession($per_page, $page_number);

		$erede_Log_Filter = $this->getLogFilterFromSession();

		$daoFactory = new WC_Erede_Dao_Factory();
		$logDao = $daoFactory->getEredeLogDao(); 
		$logresult = $logDao->selectByFilter($erede_Log_Filter);

		return $logresult;
	}

	/**
	 * Delete a log.
	 *
	 * @param int $id log ID
	 */
	public static function deleteLog($id) {
		$daoFactory = new WC_Erede_Dao_Factory();
		$logDao = $daoFactory->getEredeLogDao(); 
		$logresult = $logDao->deleteById($id);
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function getLogsAmount() {
		$daoFactory = new WC_Erede_Dao_Factory();
		$logDao = $daoFactory->getEredeLogDao(); 

		return $logDao->selectCountByFilter($this->getLogFilterFromSession());
	}


	/** Text displayed when no customer data is available 
	* @override function
	*/
	public function no_items() {
		_e( 'No transaction avaliable.', 'messages' );
	}


	/**
     * @override function
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default($item, $column_name) {
		
		switch ($column_name) {
			case 'erede_log_id':					
				 return '<a href="#" onclick="getLogContent(' . $item->getLogId() . ')" >' . $item->getLogId() . '</a>';
			case 'erede_log_date' :
				 return $item->getLogDate();
			case 'erede_order_id' :
				 return $item->getOrderId();
			case 'erede_message_result' :
				 return $item->getMessageResult();
			case 'erede_environment' :
				 return $item->getEnvironment();
			case 'erede_action' :
				 return $item->getAction(); 
		}
	}

	//http://localhost:8080/wp-admin/admin.php?page=class-wc-erede-list-table-log
	/**
     * @override function
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb($item) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item->getLogId()
		);
	}


	/**
     * @override function
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name($item) {

		$delete_nonce = wp_create_nonce('sp_delete_customer');

		$title = '<strong>' . $item['name'] . '</strong>';

		$actions = [
			'delete' => sprintf('<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint( $item['erede_log_id']), $delete_nonce)
		];

		return $title . $this->row_actions($actions);
	}

	/**
     *  @override function
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" /> ',
			'erede_log_id'    => __('Log ID', 'messages'),			
			'erede_log_date'    => __('Log Date', 'messages'),
			'erede_order_id'    => __('Order ID', 'messages'),
			'erede_message_result' => __('Message', 'messages'),
			'erede_environment'    => __( 'Environment', 'messages' ),
			'erede_action'    => __('Stage', 'messages')

		];

		return $columns;
	}


	/**
     * @override function
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'erede_log_id' => array( 'erede_log_id', true ),
			'erede_order_id' => array( 'erede_order_id', true ),
			'erede_log_date' => array( 'erede_log_date', true ),
			'erede_message_result' => array( 'erede_message_result', true ),
			'erede_environment' => array( 'erede_environment', true ),
			'erede_action' => array( 'erede_action', true ),
		);

		return $sortable_columns;
	}

	/**
     * @override function
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			WC_Erede_Post_Data::BulkDelete => esc_html__('Delete', 'messages')
		];

		return $actions;
	}


	/**
	* Handles data query and filter, sorting, and pagination.
	*/
	public function getDataLogs() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->processBulkAction();

		$per_page     = $this->get_items_per_page( 'trasaction_per_page', 25 );
		$current_page = $this->get_pagenum();

		$this->items = $this->getLogs($per_page, $current_page);

		$total_items  = $this->getLogsAmount();

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page 
		] );
	}

	public function processBulkAction() {
		if ('delete' === $this->current_action()) {

			$nonce = esc_attr($_REQUEST['_wpnonce']);

			if (!wp_verify_nonce($nonce, 'sp_delete_customer')) {
				die('Go get a life script kiddies');
			}
			else {
				self::deleteLog(absint($_GET['customer']));
				echo '<div class="updated"><p> 1 '. esc_html__('log excluded', 'messages') .'</p></div>';
			}
		}

		if (( isset($_POST[WC_Erede_Post_Data::Action]) && $_POST[WC_Erede_Post_Data::Action] == WC_Erede_Post_Data::BulkDelete)
		     || (isset( $_POST[WC_Erede_Post_Data::Action2]) && $_POST[WC_Erede_Post_Data::Action2] == WC_Erede_Post_Data::BulkDelete)) {
			if(isset($_POST[WC_Erede_Post_Data::BulkDelete])) {
				$delete_ids = esc_sql($_POST[WC_Erede_Post_Data::BulkDelete]);

				if(!empty($delete_ids)) {
					foreach ($delete_ids as $id) {
						self::deleteLog($id);
					}
					
					if(count($delete_ids) > 1) {
						echo '<div class="updated"><p>' . count($delete_ids) . ' ' . esc_html__('logs excluded', 'messages') .'</p></div>';
					} else {
						echo '<div class="updated"><p> 1 '. esc_html__('log excluded', 'messages') .'</p></div>';
					}
				}
			}
		}
	}

	public static function getInstance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	/**
	 * Plugin settings page
	 */
	public function pluginSettingsPage() {
		add_thickbox();
		$this->registerScripts();
		?>
		<style>
		.search-input {
		max-width: 100px;
		min-height: 28px;
		}
		</style>
		<div class="wrap">
			<h2><?php echo esc_html__("Transactions Log e.Rede", 'messages') ?></h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-1">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->getDataLogs();
								$this->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
		<script>
			function mascaraData(campoData) {
				
				formatData = $('#formatoData').val();
				
				switch("<?php echo get_option('date_format') ?>") {
					case "<?php echo WC_Erede_Config_Date_Format::YMD ?>" :
						$('#' + campoData).mask("<?php echo WC_Erede_Date_Format::YMD ?>");		
						break;
					case "<?php echo WC_Erede_Config_Date_Format::MDY ?>" :
						$('#' + campoData).mask("<?php echo WC_Erede_Date_Format::MDY ?>");		
						break;	
					case "<?php echo WC_Erede_Config_Date_Format::FJY ?>" :
						$('#' + campoData).mask("<?php echo WC_Erede_Date_Format::FJY ?>");
						break;
					case "<?php echo WC_Erede_Config_Date_Format::DMY ?>" :
						$('#' + campoData).mask("<?php echo WC_Erede_Date_Format::DMY ?>");
						break;
					default:
						$('#' + campoData).mask("<?php echo WC_Erede_Date_Format::DMY ?>");
				}
         	}
			window.onload = function() {
				mascaraData('search_start_date');
				mascaraData('search_end_date');
			}			
		</script>				
	<?php
	}

	public function registerScripts() {
		wp_register_script( 'woocommerce-erede-jquery', plugins_url( 'woocommerce-erede/assets/js/jquery.js' ), array(), NULL );
		wp_enqueue_script( 'woocommerce-erede-jquery' );

		wp_register_script( 'woocommerce-erede-jquery-mask', plugins_url( 'woocommerce-erede/assets/js/jquery.mask.min.js' ), array(), NULL, true );
		wp_enqueue_script( 'woocommerce-erede-jquery-mask' );
	}

	 /**
	 * Screen options
	 */
	public function screenOption() {

		$option = 'per_page';
		$args   = [			
			'label'   => __('Number of items per page:', 'messages'),
			'default' => 25,
			'option'  => 'trasaction_per_page'
		];

		add_screen_option( $option, $args );
	}
}

?>    

