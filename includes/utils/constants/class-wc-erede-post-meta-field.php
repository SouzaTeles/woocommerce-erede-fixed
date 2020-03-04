<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class WC_Erede_Post_Meta_Field
{
    const Tid = "_tid";  
    const TransactionId = "_transaction_id"; 
    const Nsu = "_nsu"; 
    const Installments = "_installments"; 
    const CreditHolderName = "_credit_holder_name"; 
    const Bin = "_bin"; 
    const LastFourCreditCardDigits = "_last_four_credit_card_digits";
    const PaymentMethod = '_payment_method'; 
}

?>