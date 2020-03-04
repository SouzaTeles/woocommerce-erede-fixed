<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class WC_Erede_Actions
{
    const Checkout = "Finalização";  //Finalização do Pedido
    const Capture = "Captura"; //Captura
    const Update = "Sincronizar"; //Atualização
    const Refund = "Reembolso"; //Reembolso
}

?>