<?php

require(dirname(__FILE__).'/../Classloader.php');

use erede\model\EnvironmentType;
use erede\model\RefundRequest;
use erede\model\UrlRequest;
use erede\model\UrlKind;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$tid = '7406181828144785595';
$refundRequest = new RefundRequest();
$refundRequest->setAmount('10000');
$urls = array();
$urlCallback = new UrlRequest();
$urlCallback->setUrlKind(UrlKind::CALLBACK);
$urlCallback->setUrl('https://callback.com');
$urls[] = $urlCallback;
$refundRequest->setUrls($urls);

$c = new Acquirer('37502603','4B56E46EE155CA2A0AC0241C69A1E949F964BB26', EnvironmentType::HOMOLOG);
echo "Request JSON: " . $refundRequest->toJson() . "\n";
$v = $c->refund($tid, $refundRequest);
echo "Response JSON: " . $v->toJson() . "\n";
echo "RefundId: " . $v->getRefundId();
