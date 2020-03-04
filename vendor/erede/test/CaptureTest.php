<?php

require(dirname(__FILE__).'/../Classloader.php');

use erede\model\EnvironmentType;
use erede\model\TransactionRequest;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tid = '7406181828144785595';
$transactionRequest = new TransactionRequest();
$transactionRequest->setAmount('10000');

$c = new Acquirer('37502603','4B56E46EE155CA2A0AC0241C69A1E949F964BB26', EnvironmentType::HOMOLOG);
echo "Request JSON: " . $transactionRequest->toJson() . "\n";
$v = $c->capture($tid, $transactionRequest);
echo "Response JSON: " . $v->toJson() . "\n";
