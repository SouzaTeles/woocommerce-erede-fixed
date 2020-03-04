<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface WC_Erede_Log_Interface
{
    public function insertLog(WC_Erede_Log $log);
    public function selectById($erede_log_id);
}

?>