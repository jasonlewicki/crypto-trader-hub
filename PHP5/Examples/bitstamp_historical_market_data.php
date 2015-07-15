<?php

include dirname(__FILE__).'/Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Create Bitstamp object using bitstamp credentials
$bitstamp_obj = new \CryptoTraderHub\Exchanges\Bitstamp(APP_ROOT.'/Configs/exchange_bitstamp.ini');


// Get Bitstamp historical market data and insert into the DB
$order_book = $bitstamp_obj->orderBook();

// Create a Test exchange with the Historical data that we just inserted into the DB
$database_obj = new \CryptoTraderHub\Core\Database(APP_ROOT.'/Configs/database.ini');

// Step through the market data
/*for($i = 0; ($slice = $test_exchange_obj->step()) !== false; ++$i){
	echo $i ." ".$slice['price']."\n";
}*/