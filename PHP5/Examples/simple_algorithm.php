<?php

// Include config and autoloader files
include dirname(__FILE__).'/../Configs/config.php';
include CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.'autoload.php';

// Create a Test exchange with the Historical data that we just inserted into the DB
$test_exchange_obj = new \CryptoTraderHub\Exchanges\Test(APP_ROOT.'/Configs/historical_bitstampUSD.ini');

$last_60 			= Array();
$last_60_full 		= false;
$buy_amount 		= 1;
$sell_amount 		= 1;
$buy_fee 			= $test_exchange_obj->buyFee();
$sell_fee 			= $test_exchange_obj->sellFee();
$starting_value_btc	= null;
$starting_usd		= null;
$starting_btc		= null;

// Step through the market data
for($i = 0; ($slice = $test_exchange_obj->step()) !== false; ++$i){
	
	// Fill up the Array before we start calculations
	if(!$last_60_full){
		
		if(is_null($starting_value_btc)){
			$balance = $test_exchange_obj->balance();
			$starting_value_btc = $slice['price'];
			$starting_usd = $balance['usd'];
			$starting_btc = $balance['btc'];
		}
		
		array_push($last_60, $slice['price']);
		if(count($last_60) >= 60){
			$last_60_full = true;
		}
	}else{
		array_push($last_60, $slice['price']);		
		array_shift($last_60);	
	}
	
	$mean 				= \CryptoTraderHub\Core\Statistics::mean($last_60);
	$standard_deviation = \CryptoTraderHub\Core\Statistics::standardDeviation($last_60);
			
	if($slice['price'] > $mean + $standard_deviation*2){		
		$balance = $test_exchange_obj->balance();
		
		if($balance['btc'] > $sell_amount){
			$test_exchange_obj->sell($sell_amount, $slice['price']);
		}			
	}else if($slice['price'] < $mean - $standard_deviation*2){				
		$balance = $test_exchange_obj->balance();
		
		if($balance['usd'] >  ($buy_amount*$slice['price'] + ($buy_fee*$buy_amount*$slice['price']))){
			$test_exchange_obj->buy($buy_amount, $slice['price']);
		}		
	}
	
	// Every 1000 ticks, show the status
	if($i % 1000 == 0){
		// The magic number is if you purchased BTC and held the entire time;		
		$balance 		= $test_exchange_obj->balance();
		$total_value 	= $balance['usd']+($balance['btc']*$slice['price']);
		$magic_number	= (($starting_usd/$starting_value_btc) + $starting_btc)	* $slice['price'];
		echo "USD=".$balance['usd']."\tBTC=".$balance['btc']."\tVAL=".$total_value."\tPRICE=".$slice['price']."\tMAGIC#=".floor($magic_number)."\n";
	}
	
}