<?php

include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'PHP5'.DIRECTORY_SEPARATOR.'autoload.php';
include dirname(__FILE__).'/Configs/config.php';

// Start Database object (if you need this, uncomment it)
//\CryptoTraderHub\Core\Database::initialize(dirname(__FILE__).'/Configs/database.ini');

// Get Bitstamp historical market data and insert into the DB
$bitcoin_charts_obj = new \CryptoTraderHub\DataServices\Bitcoincharts();
$filename = $bitcoin_charts_obj -> completeHistory('bitstampUSD');

// APP_TMP . '/'.

