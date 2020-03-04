<?php

require(dirname(__FILE__).'/../Classloader.php');

use erede\model\EnvironmentType;
use erede\model\TransactionKind;
use erede\model\TransactionRequest;
use erede\model\IataRequest;
use erede\model\ThreeDSecureRequest;
use erede\model\UrlRequest;
use erede\model\UrlKind;
use erede\model\AvsRequest;
use erede\model\AddressRequest;
use erede\model\ThreeDSecureOnFailure;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$transactionRequest = new TransactionRequest();
$transactionRequest->setCapture('false');
$transactionRequest->setKind(TransactionKind::CREDIT);
$transactionRequest->setReference('abcd' . ((string)mt_rand(0, 99999999)));
$transactionRequest->setAmount('20000');
$transactionRequest->setInstallments('4');
$transactionRequest->setCardHolderName('Portador');
$transactionRequest->setCardNumber('4111111111111111');
$transactionRequest->setExpirationMonth('05');
$transactionRequest->setExpirationYear('18');
$transactionRequest->setSecurityCode('123');
$transactionRequest->setSoftDescriptor('Loja');
$transactionRequest->setSubscription('true');
$transactionRequest->setOrigin('01');
$transactionRequest->setDistributorAffiliation('12341088');

// $iataRequest = new IataRequest();
// $iataRequest->setCode('101010');
// $iataRequest->setDepartureTax('5000');
// $transactionRequest->setIata($iataRequest);

// $avsRequest = new AvsRequest();
// $avsRequest->setDocumentNumber('30376137185');
// $addressRequest = new AddressRequest();
// $addressRequest->setStreet('Rua rua rua');
// $addressRequest->setNumber('11');
// $addressRequest->setPostalCode('03030222');
// $addressRequest->setComplement('apto 111');
// $avsRequest->setAddress($addressRequest);
// $transactionRequest->setAvs($avsRequest);

// $threeDsRequest = new ThreeDSecureRequest();
// $threeDsRequest->setEmbedded('false');
// $threeDsRequest->setCavv('jF6hPiHFPmPwCBER3JmBBUMAAAA=');
// $threeDsRequest->setEci('05');
// $threeDsRequest->setXid('WEUxbk0xMDJ1VGxwSHdocHJwR2s=');
// // $threeDsRequest->setOnFailure(ThreeDSecureOnFailure::DECLINE);
// // $threeDsRequest->setUserAgent('Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405');
// $transactionRequest->setThreeDSecure($threeDsRequest);

// $urls = array();
// $urlCallback = new UrlRequest();
// $urlCallback->setUrlKind(UrlKind::THREE_D_SECURE_SUCCESS);
// $urlCallback->setUrl('https://redirecturl.com/3ds/success');
// $urls[] = $urlCallback;
// $urlFailure = new UrlRequest();
// $urlFailure->setUrlKind(UrlKind::THREE_D_SECURE_FAILURE);
// $urlFailure->setUrl('https://redirecturl.com/3ds/failure');
// $urls[] = $urlFailure;
// $transactionRequest->setUrls($urls);

$c = new Acquirer('37502603','4B56E46EE155CA2A0AC0241C69A1E949F964BB26', EnvironmentType::HOMOLOG);
echo "Request JSON: " . $transactionRequest->toJson() . "\n";
$v = $c->authorize($transactionRequest);
echo "Response: JSON: " . $v->toJson() . "\n";
echo "TID: " . $v->getTid();
