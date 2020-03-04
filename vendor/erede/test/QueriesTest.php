<?php

require(dirname(__FILE__).'/../Classloader.php');

use erede\model\EnvironmentType;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$c = new Query('37502603','4B56E46EE155CA2A0AC0241C69A1E949F964BB26', EnvironmentType::HOMOLOG);

// Transaction
$tid = '7406181828144785595';
$v = $c->getTransactionByTid($tid);
echo "Transaction by Tid $tid:\n" . $v->toJson() . "\n\n";

// $reference = 'abcd1234';
// $v = $c->getTransactionByReference($reference);
// echo 'Transaction by Reference: ';
// var_dump($v);

// Refund
$refundId = '08433dd7-6f0c-4dd1-9a88-4bb7802934b5';
$v = $c->getRefund($tid, $refundId);
echo "Refund by id $refundId:\n" . $v->toJson() . "\n\n";

// List of refunds
$tid = '7406181828144785595';
$v = $c->getRefunds($tid);
echo "List of refunds by Tid $tid:\n" . $v->toJson() . "\n\n";
