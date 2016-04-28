<?php

include dirname(__FILE__).'/Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Create Bitstamp object using bitstamp credentials
$bitstamp_obj = new \CryptoTraderHub\Exchanges\Bitstamp(APP_ROOT.'/Configs/exchange_bitstamp.ini');

// Get Bitstamp historical market data and insert into the DB
\CryptoTraderHub\Core\Database::initialize(DATABASE_INI);

// Get the order book
$order_book = $bitstamp_obj->orderBook();

// Create SQL statement from order book.
$timestamp = $order_book['timestamp'];
$bids_sql = '';
foreach($order_book['bids'] as $bid){		
	$bids_sql .= "('".date('Y-m-d H:i:s', $timestamp)."','bid',{$bid[0]},{$bid[1]}),";					
}
$asks_sql = '';
foreach($order_book['asks'] as $ask){		
	$asks_sql .= "('".date('Y-m-d H:i:s', $timestamp)."','ask',{$ask[0]},{$ask[1]}),";					
}

// Insert the order book into the database
\CryptoTraderHub\Core\Database::runQuery("INSERT INTO ".\CryptoTraderHub\Core\Database::getDB().".bitstamp_order_book (`timestamp`,`type`,`price`,`volume`) VALUES  " . rtrim($bids_sql,','). ";");

// Insert the order book into the database
\CryptoTraderHub\Core\Database::runQuery("INSERT INTO ".\CryptoTraderHub\Core\Database::getDB().".bitstamp_order_book (`timestamp`,`type`,`price`,`volume`) VALUES  " . rtrim($asks_sql,','). ";");