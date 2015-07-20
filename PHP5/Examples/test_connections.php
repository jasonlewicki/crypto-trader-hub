<?php

include dirname(__FILE__).'/Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Create Bitstamp object using bitstamp credentials
$bitstamp_obj = new \CryptoTraderHub\Exchanges\Bitstamp(APP_ROOT.'/Configs/exchange_bitstamp.ini');

try{
	$bitstamp_obj->testPublic();
	$bitstamp_obj->testPrivate();	
	echo "passed\n";
}catch (\Exception $e) {
	echo "error\n";
}
