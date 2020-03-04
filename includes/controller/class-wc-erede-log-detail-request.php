<?php

include_once dirname( __FILE__ ) .  '/../controller/class-wc-erede-base-request.php';

class WC_Erede_Log_Detail_Request extends WC_Erede_Base_Request {

	public function __construct() {
        parent::__construct();
        $this->includes();        
	}

    private function includes()
    {
        include_once dirname( __FILE__ ) .  '/../dao/class-wc-erede-dao-factory.php';
        include_once dirname( __FILE__ ) .  '/../model/class-wc-erede-log.php';
    }

    public function execute()
    {
        $erede_log_id = isset($_POST[WC_Erede_Post_Data::LogId]) ? $_POST[WC_Erede_Post_Data::LogId] : 1;
        $daoFactory = new WC_Erede_Dao_Factory();
        $logDao = $daoFactory->getEredeLogDao(); 
        $logresult = $logDao->selectById($erede_log_id);

        parent::returnAjaxMessage($logresult);
    }
}

$request = new WC_Erede_Log_Detail_Request();
$request->execute();

?>