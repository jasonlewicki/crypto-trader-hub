<?php

include dirname(__FILE__).'/Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Create Bitstamp object using bitstamp credentials
$bitstamp_obj = new \CryptoTraderHub\Exchanges\Bitstamp(APP_ROOT.'/Configs/exchange_bitstamp.ini');

// Sell
$sell_amount = 20.00000000;
$sell_price = 322.00;
$bitstamp_obj->buy($sell_amount, $sell_price);