<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include("class-wc-erede-log-interface.php");

class WC_Erede_Log_Dao implements WC_Erede_Log_Interface
{
    public function insertLog(WC_Erede_log $log)
    {    	
    	global $wpdb;
        $table_name = $wpdb->prefix . 'ered_log';
        
	    $wpdb->insert( 
		    $table_name, 
		    array( 
			    'erede_order_id' => $log->getOrderId(),  
			    'erede_card_holder_name' => $log->getCreditHolderName(), 
			    'erede_message_result' => $log->getMessageResult(),
			    'erede_environment' => $log->getEnvironment(),
			    'erede_status' => $log->getStatus(),
			    'erede_action' => $log->getAction(),
			    'erede_log_date' => $log->getLogDate(),
			    'erede_tid' => $log->getTid(),
			    'erede_sqn' => !empty($log->getSqn())? $log->getSqn() : '',
			    'erede_transaction_date' => $log->getTransactionDate(),
			    'erede_total' => $log->getAmount(),
			    'erede_installments' => $log->getInstallments(),
			    'erede_currency' => $log->getCurrency(),
				'erede_card_bin' => $log->getCardBin(),
				'erede_last_card_digits' => $log->getLastCardDigits(),
				'erede_is_transaction_ok' => $log->getIsTransactionOk()
		    ) 
	    );
    }

	public function deleteById($id) {
		global $wpdb;
		
		$wpdb->delete(
			"{$wpdb->prefix}ered_log",
			[ 'erede_log_id' => $id ],
			[ '%d' ]
		);
	}

	public function selectCountByFilter($eredeLogFilter) {
		global $wpdb;
		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}ered_log";

		$sql = $this->mountQueryFilter($eredeLogFilter, $sql);

		return $wpdb->get_var($sql);
	}

	public function selectByFilter($eredeLogFilter) {
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}ered_log";

		$sql = $this->mountQueryFilter($eredeLogFilter, $sql);

		$sql .= ' LIMIT ' .  $eredeLogFilter->getPerPage();
		$sql .= ' OFFSET ' . ( $eredeLogFilter->getPageNumber() - 1 ) * $eredeLogFilter->getPerPage();
		
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $this->mapSqlArrayResultToEredeLog($result);
	}

	public function selectById($erede_log_id) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'ered_log';
		
		$sql = "SELECT 
					erede_log_id,
					erede_order_id,
					erede_card_holder_name,
					erede_message_result,
					erede_environment,
					erede_environment,
					erede_status,
					erede_action,
					erede_log_date,
					erede_tid,
					erede_sqn,
					erede_transaction_date,
					erede_total,
					erede_installments,
					erede_currency,
					erede_card_bin,
					erede_last_card_digits,
					erede_is_transaction_ok
				FROM $table_name 
				WHERE erede_log_id=%d";
		
		$result = $wpdb->get_results($wpdb->prepare($sql, $erede_log_id ), 'ARRAY_A');

		return $this->mapSqlArrayResultToEredeLog($result)[0];
	}


  	public function mapSqlArrayResultToEredeLog($sqlResultArray) {
	  $arrayResult = array();
	  
	  foreach ($sqlResultArray as $key => $row) {
		  array_push($arrayResult, $this->mapSqlResultToEredeLog($row));
	  }

	  return $arrayResult;
  	}


  	private function mapSqlResultToEredeLog($sqlResult) {
	  
	  $eredeLog = new WC_Erede_Log();

	  $eredeLog->setLogId(isset($sqlResult['erede_log_id']) ? $sqlResult['erede_log_id'] : null);
	  $eredeLog->setOrderId(isset($sqlResult['erede_order_id']) ? $sqlResult['erede_order_id'] : null);
      $eredeLog->setCreditHolderName(isset($sqlResult['erede_card_holder_name']) ? $sqlResult['erede_card_holder_name'] : null);
      $eredeLog->setMessageResult(isset($sqlResult['erede_message_result']) ? $sqlResult['erede_message_result'] : null);
      $eredeLog->setEnvironment(isset($sqlResult['erede_environment']) ? $sqlResult['erede_environment'] : null);
      $eredeLog->setStatus(isset($sqlResult['erede_status']) ? $sqlResult['erede_status'] : null);
      $eredeLog->setAction(isset($sqlResult['erede_action']) ? $sqlResult['erede_action'] : null);
      $eredeLog->setLogDate(isset($sqlResult['erede_log_date']) ? $sqlResult['erede_log_date'] : null);
      $eredeLog->setTid(isset($sqlResult['erede_tid']) ? $sqlResult['erede_tid'] : null);
      $eredeLog->setSqn(isset($sqlResult['erede_sqn']) ? $sqlResult['erede_sqn'] : null);
      $eredeLog->setTransactionDate(isset($sqlResult['erede_transaction_date']) ? $sqlResult['erede_transaction_date'] : null);
      $eredeLog->setAmount(isset($sqlResult['erede_total']) ? $sqlResult['erede_total'] : null);
      $eredeLog->setInstallments(isset($sqlResult['erede_installments']) ? $sqlResult['erede_installments'] : null);
      $eredeLog->setCurrency(isset($sqlResult['erede_currency']) ? $sqlResult['erede_currency'] : null);
	  $eredeLog->setCardBin(isset($sqlResult['erede_card_bin']) ? $sqlResult['erede_card_bin'] : null);
	  $eredeLog->setLastCardDigits(isset($sqlResult['erede_last_card_digits']) ? $sqlResult['erede_last_card_digits'] : null);
	  $eredeLog->setIsTransactionOk(isset($sqlResult['erede_is_transaction_ok']) ? $sqlResult['erede_is_transaction_ok'] : null);

	  return $eredeLog;
  	}

  	private function mountQueryFilter($eredeLogFilter, $sql)
  	{
		$sqlWhere = "";

		if(!empty($eredeLogFilter->getMessage()) && $eredeLogFilter->getMessage() != 'all') {
			$sqlWhere .= " erede_is_transaction_ok = " . ($eredeLogFilter->getMessage() == 'sucess' ? 1 : 0);
		}

		if(!empty($eredeLogFilter->getEnvironment()) && $eredeLogFilter->getEnvironment() != 'all') {
			if(!empty($sqlWhere)) {
				$sqlWhere .= " AND ";
			}

			$sqlWhere .= " erede_environment = " . '"' . esc_sql($eredeLogFilter->getEnvironment()) . '"';
		}

		if(!empty($eredeLogFilter->getStage()) && $eredeLogFilter->getStage() != 'all') {
			if(!empty($sqlWhere)) {
				$sqlWhere .= " AND ";
			}

			$sqlWhere .= " erede_action = " . '"' . esc_sql($eredeLogFilter->getStage()) . '"';
		}

		if(!empty($eredeLogFilter->getLogId())) {
			if(!empty($sqlWhere)) {
				$sqlWhere .= " AND ";
			}

			$sqlWhere .= " erede_log_id = " . esc_sql($eredeLogFilter->getLogId());
		}

		if(!empty($eredeLogFilter->getOrderId())) {
			if(!empty($sqlWhere)) {
				$sqlWhere .= " AND ";
			}

			$sqlWhere .= " erede_order_id = " . esc_sql($eredeLogFilter->getOrderId());
		}

		if(!empty($eredeLogFilter->getStartDate())) {
			if(!empty($sqlWhere)) {
				$sqlWhere .= " AND ";
			}

			$sqlWhere .= " erede_log_date >= " . '"' . esc_sql($eredeLogFilter->getStartDate()) . '"';
		}

		if(!empty($eredeLogFilter->getEndDate())) {
			if(!empty($sqlWhere)) {
				$sqlWhere .= " AND ";
			}

			$sqlWhere .= " erede_log_date <= " . '"' . esc_sql($eredeLogFilter->getEndDate()) . ' 23:59:59' . '"';
		}

		if(!empty($sqlWhere)) {
			$sql .=  " where " . $sqlWhere;
		}

		if(!empty($eredeLogFilter->getOrderBy()) && !empty($eredeLogFilter->getOrder())) {
			$sql .= ' order by ' . $eredeLogFilter->getOrderBy() . ' ' . $eredeLogFilter->getOrder();
		} else {
			$sql .= ' order by erede_log_date desc'; 
		}

		return $sql;
  	}
}

?>
