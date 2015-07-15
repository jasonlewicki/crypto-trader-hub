<?php

include dirname(__FILE__).'/Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Create Bitstamp object using bitstamp credentials
$bitstamp_obj = new \CryptoTraderHub\Exchanges\Bitstamp(APP_ROOT.'/Configs/exchange_bitstamp.ini');


// Get Bitstamp historical market data and insert into the DB
\CryptoTraderHub\Core\Database::initialize(DATABASE_INI);

$order_book = $bitstamp_obj->orderBook();

$timestamp = $order_book['timestamp'];

$sql = '';

foreach($order_book['bids'] as $bid){		
	$sql .= "('".date('Y-m-d H:i:s', $timestamp)."',{$bid[0]},{$bid[1]}),";					
}

\CryptoTraderHub\Core\Database::runQuery("INSERT INTO ".\CryptoTraderHub\Core\Database::getDB().".bitstamp_order_book (`timestamp`,`price`,`volume`) VALUES  " . rtrim($sql,','). ";");