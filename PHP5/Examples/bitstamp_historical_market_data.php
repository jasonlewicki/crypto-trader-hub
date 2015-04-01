<?php

include dirname(__FILE__).'/Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Get Bitstamp historical market data and insert into the DB
//$bitcoin_charts_obj = new \CryptoTraderHub\DataServices\Bitcoincharts();
//$filename = $bitcoin_charts_obj -> completeHistory('bitstampUSD', true);

// Create a Test exchange with the Historical data that we just inserted into the DB
$test_exchange_obj = new \CryptoTraderHub\Exchanges\Test(APP_ROOT.'/Configs/exchange_test.ini');
$i = 0;
while(($slice = $test_exchange_obj->step()) !== false){
	echo ++$i ." ".$slice['price']."\n";
}

