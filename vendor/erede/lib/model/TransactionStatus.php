<?php

namespace erede\model;

/**
 * Status of transaction
 */
abstract class TransactionStatus
{
	const APPROVED       = 'Approved';
	const DENIED        = 'Denied';
	const CANCELED		= 'Canceled';
	const PENDING		= 'Pending';
}