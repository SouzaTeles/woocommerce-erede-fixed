<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class WC_Erede_Sessions
{
    const UpdateStatus = "erede-update-status";
	const OrderIdUpdateFromApiError = "Erede-orderId-update-from-api-error";
	const FilterLogFields = "erede-filter-log-fields";
	const OrderIdAllReadyUpdatedFromApi = "Erede-orderId-all-ready-updated-from-api-";
	const OrderIdUpdateFromApi = "Erede-orderId-update-from-api-";
	const OrderUpdated = "Erede-order-upddated";
}

?>