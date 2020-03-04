<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class WC_Erede_Status
{
    const OnHold = "on-hold";  //Aguardando
    const Processing = "processing"; //Processando
    const Refunded = "refunded"; //Reembolsado
    const Error = "Error"; //Falhada
    const Pending = "pending"; //Pendente de pagamento
    const Completed = "completed"; //Completo
    const Failed = "failed"; //Failed
    const Cancelled = "cancelled"; //Cancelled

    public static function getTranslatedStatus($status) {
        switch($status) {
            case WC_Erede_Status::OnHold :
                return "Aguardando";
            case WC_Erede_Status::Processing : 
                return "Processando";
            case WC_Erede_Status::Refunded :
                return "Reembolsado";
            case WC_Erede_Status::Error :
                return "Falhada";
            default: 
                return $status;
        }
    }


}

?> 