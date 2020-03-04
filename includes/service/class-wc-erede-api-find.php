<?php
if (! defined ( 'ABSPATH' )) {
	exit ();
}
class WC_Erede_Api_Find extends WC_Erede_Api {
	public function __construct() {
		parent::__construct ();
		$this->includes ();
	}
	private function includes() {
		require_once dirname ( __FILE__ ) . '/../../vendor/erede/Classloader.php';
	}
	public function callApi($order, $request) {
		
		$response = new WC_Erede_Find_Response ( $this->query->getTransactionByReference ( $request->getReference () ) );
		
		WC_Erede_Log_Helper::getInstance ()->add ( new WC_erede_logger_template ( WC_erede_logger_template::QUERY, WC_Erede_Config_Static::getPluginConfig ()->getAffiliation (), $request, $response ) );
		$this->action = WC_Erede_Actions::Update;
		
		$this->saveResumeFindLog ( $order, $request, $response );
		
		return $response;
	}
	private function mapRequest($object) {
		$mappedRequest = new FindRequest ();
		$mappedRequest->setTid ( $object->getTid () );
		$mappedRequest->setReference ( $object->getReference () );
		
		return $mappedRequest;
	}
	public function overrideLogValues($order, $request, $response) {
		if (!empty ( $response->getAuthorization () ))
			$this->erede_log->setStatus ( WC_Erede_Status::getTranslatedStatus ( WC_Erede_Functions::mapEredeStatusToWCStatus ( $response->getAuthorization ()->getStatus () ) ) );
		else
			$this->erede_log->setStatus ( WC_Erede_Status::getTranslatedStatus ( WC_Erede_Functions::mapEredeStatusToWCStatus ( $response->getStatus () ) ) );
	}
}

?>
