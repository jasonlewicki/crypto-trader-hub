<?php

include dirname(__FILE__).'/Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Create Bitstamp object using bitstamp credentials
$bitstamp_obj = new \CryptoTraderHub\Exchanges\Bitstamp(APP_ROOT.'/Configs/exchange_bitstamp.ini');

echo "Testing bitstamp API\n----------------------\n";

// Bitstamp Tests for public access
try{
	$bitstamp_obj->testPublic();	
	echo "Passed public tests.\n";
}catch (\Exception $e) {
	echo "Public tests error.\n";
}

// Bitstamp Tests for private access
try{
	$bitstamp_obj->testPrivate();	
	echo "Passed private tests.\n";
}catch (\Exception $e) {
	echo "Private tests error.\n";
}
