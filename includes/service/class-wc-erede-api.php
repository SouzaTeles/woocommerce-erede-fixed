<?php

use erede\model\EnvironmentType;

if (! defined ( 'ABSPATH' )) {
	exit ();
}
abstract class WC_Erede_Api {
	protected $erede_log;
	protected $acquirer;
	protected $action;
	public function __construct() {
		$this->includes ();
		
		$this->erede_log = new WC_Erede_Log ();
		$this->acquirer = new \Acquirer ( WC_Erede_Config_Static::getPluginConfig ()->getAffiliation (), WC_Erede_Config_Static::getPluginConfig ()->getToken (), $this->getEnvironmentType ( WC_Erede_Config_Static::getPluginConfig ()->getEnvironment () ) );
		$this->query = new \Query ( WC_Erede_Config_Static::getPluginConfig ()->getAffiliation (), WC_Erede_Config_Static::getPluginConfig ()->getToken (), $this->getEnvironmentType ( WC_Erede_Config_Static::getPluginConfig ()->getEnvironment () ) );
		
		
	}
	private function includes() {
		
		require_once dirname ( __FILE__ ) . '/../../vendor/erede/Classloader.php';
		require_once dirname ( __FILE__ ) . '/../model/class-wc-erede-config-static.php';
		
		// // Log File
		require_once dirname ( __FILE__ ) . '/../utils/class-wc-erede-log-helper.php';
		require_once dirname ( __FILE__ ) . '/../model/class-wc-erede-logger-template.php';
		// // Log Database
		require_once dirname ( __FILE__ ) . '/../model/class-wc-erede-log.php';
		require_once dirname ( __FILE__ ) . '/../dao/class-wc-erede-dao-factory.php';
		require_once dirname ( __FILE__ ) . '/../utils/constants/class-wc-erede-actions.php';
		require_once dirname ( __FILE__ ) . '/../utils/constants/class-wc-erede-environment.php';
		require_once dirname ( __FILE__ ) . '/../utils/constants/class-wc-erede-status.php';
		require_once dirname ( __FILE__ ) . '/../utils/constants/class-wc-erede-post-meta-field.php';
		require_once dirname ( __FILE__ ) . '/../utils/class-wc-erede-functions.php';
	}
	private function getEnvironmentType($environment) {
		if ($environment == null || empty ( $environment )) {
			return null;
		}
		switch (strtoupper ( $environment )) {
			case 'TEST' :
				return EnvironmentType::HOMOLOG;
			case 'PRODUCTION' :
				return EnvironmentType::PRODUCTION;
			default :
				return null;
		}
	}
	protected abstract function overrideLogValues($order, $request, $response);
	
	protected function saveResumeLog($order, $request, $response) {
				
		$this->erede_log->setAction ( $this->action );
		$this->erede_log->setOrderId ( $order->get_id() );
		$this->erede_log->setIsTransactionOk ( $response->isSucess () );
		$this->erede_log->setEnvironment ( WC_Erede_Environment::getTranslatedEnvironment ( WC_Erede_Config_Static::getPluginConfig ()->getEnvironment () ) );
		$this->erede_log->setLogDate ( current_time ( 'mysql' ) );
		$this->erede_log->setAmount ( $order->get_total () );
		$this->erede_log->setCurrency ( get_woocommerce_currency () );
		
		if ($this->action != WC_Erede_Actions::Checkout) {
			$this->erede_log->setCreditHolderName ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::CreditHolderName, true ) );
			$this->erede_log->setInstallments ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::Installments, true ) );
			$this->erede_log->setCardBin ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::Bin, true ) );
			$this->erede_log->setLastCardDigits ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::LastFourCreditCardDigits, true ) );
		}
		
		$this->erede_log->setMessageResult ( $response->getReturnMessage () );
 		$this->erede_log->setTid ( $response->getTid () );
		$this->erede_log->setSqn ( $response->getNsu () );	
		$this->erede_log->setTransactionDate ( $response->getDateTime ()); 

		$this->overrideLogValues ( $order, $request, $response );		
		$this->saveLog ();
	}
	
	protected function saveResumeFindLog($order, $request, $response) {
		
		$this->erede_log->setAction ( $this->action );
		$this->erede_log->setOrderId ( $order->get_id() );
		$this->erede_log->setIsTransactionOk ( $response->isSucess () );
		$this->erede_log->setEnvironment ( WC_Erede_Environment::getTranslatedEnvironment ( WC_Erede_Config_Static::getPluginConfig ()->getEnvironment () ) );
		$this->erede_log->setLogDate ( current_time ( 'mysql' ) );
		$this->erede_log->setAmount ( $order->get_total () );
		$this->erede_log->setCurrency ( get_woocommerce_currency () );
		
		if ($this->action != WC_Erede_Actions::Checkout) {
			$this->erede_log->setCreditHolderName ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::CreditHolderName, true ) );
			$this->erede_log->setInstallments ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::Installments, true ) );
			$this->erede_log->setCardBin ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::Bin, true ) );
			$this->erede_log->setLastCardDigits ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::LastFourCreditCardDigits, true ) );
		}

		if(property_exists($response, "authorization")){
			$this->erede_log->setMessageResult ( $response->getAuthorization ()->getReturnMessage () );
 			$this->erede_log->setTid ( $response->getAuthorization ()->getTid () );
			$this->erede_log->setSqn ( $response->getAuthorization ()->getNsu () );	
			$this->erede_log->setTransactionDate ( $response->getAuthorization ()->getDateTime ()); 
		}
		
		if (property_exists($response, "refunds")) {
			if($response->getRefunds()){
				$this->erede_log->setTransactionDate($response->getRefunds()[0]->getRefundDateTime());
			}
		}
		elseif(property_exists($response, "datetime")) {
			$this->erede_log->setMessageResult ( $response->getReturnMessage ());
			$this->erede_log->setTransactionDate ( $response->getDateTime());
			$this->erede_log->setTid ( $response->getTid ());
			$this->erede_log->setSqn ( $response->getNsu ());
		}

		$this->overrideLogValues ( $order, $request, $response );		
		$this->saveLog ();
	}
	
	
	protected function saveResumeRefundLog($order, $request, $response){
		$this->erede_log->setAction ( $this->action );
		$this->erede_log->setOrderId ( $order->get_id() );
		$this->erede_log->setIsTransactionOk ( $response->isSucess () );
		$this->erede_log->setEnvironment ( WC_Erede_Environment::getTranslatedEnvironment ( WC_Erede_Config_Static::getPluginConfig ()->getEnvironment () ) );
		$this->erede_log->setLogDate ( current_time ( 'mysql' ) );
		$this->erede_log->setAmount ( $order->get_total () );
		$this->erede_log->setCurrency ( get_woocommerce_currency () );
		
		if ($this->action != WC_Erede_Actions::Checkout) {
			$this->erede_log->setCreditHolderName ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::CreditHolderName, true ) );
			$this->erede_log->setInstallments ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::Installments, true ) );
			$this->erede_log->setCardBin ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::Bin, true ) );
			$this->erede_log->setLastCardDigits ( get_post_meta ( $order->get_id(), WC_Erede_Post_Meta_Field::LastFourCreditCardDigits, true ) );
		}
		$this->erede_log->setTransactionDate ( $response->getRefundDateTime () );
		$this->erede_log->setMessageResult ( $response->getReturnMessage () );
		$this->erede_log->setTid ( $response->getTid () );
		$this->overrideLogValues ( $order, $request, $response );
		
		$this->saveLog ();
	}
	
	private function saveLog() {
		$logDao = WC_Erede_Dao_Factory::getFactory ()->getEredeLogDao ();
		$logDao->insertLog ( $this->erede_log );
	}
}
?>
