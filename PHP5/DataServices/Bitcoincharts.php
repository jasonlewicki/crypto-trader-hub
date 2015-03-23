<?php 

namespace CryptoTraderHub\DataServices;


class Bitcoincharts implements \CryptoTraderHub\DataServices\DataService{
	
	private $client_id;
	private $api_key;
	private $api_secret;
	
	// Constructor
	public function __construct($exchange_ini) {
		$settings 			= parse_ini_file($exchange_ini);;
		$this->client_id 	= $settings['client_id'];
		$this->api_key 		= $settings['api_key'];
		$this->api_secret 	= $settings['api_secret'];
	}
	
	/* 
	 * You can use this to price goods and services in Bitcoins. 
	 * This will yield much lower fluctuations than using a single market's latest price.
	 * Weighted prices are calculated for the last 24 hours, 7 days and 30 days. If 
	 * there are no trades during an interval (like no trade within 24 hours) no value will 
	 * be returned. Prepare your code to handle this cases!	The returned JSON is dictionary
	 * with elements for each currency. Each currency has up to three key-value pairs: 24h, 7d and 30d. 
	*/
	public function weightedPrices(){	
		
		$url 	= 'http://http://api.bitcoincharts.com/v1/weighted_prices.json';
		$method = 'GET';
		$data 	= Array();
		
		return \CryptoTraderHub\Core\Connection::request($url, $method, $data);	
	}	

	/* 
	 * This will return an array with elements for each market. Returned fields per market are:
	 * symbol 			- short name for market
	 * currency 		- base currency of the market (USD, EUR, RUB, JPY, ...)
	 * bid 				- highest bid price
	 * ask 				- lowest ask price
	 * latest_trade 	- unixtime of latest trade. Following fields relate to the day of this field (UTC)!
	 * n_trades 		- number of trades
	 * high 			- highest trade during day
	 * low 				- lowest trade during day
	 * close 			- latest trade
	 * previous_close 	- latest trade of previous day
	 * volume 			- total trade volume of day in BTC
	 * currency_volume	- total trade volume of day in currency
	 */
	public function marketsData(){
	
		$url 	= 'http://api.bitcoincharts.com/v1/markets.json';
		$method = 'GET';
		$data 	= Array();
	
		return \CryptoTraderHub\Core\Connection::request($url, $method, $data);
	}		

	/* 
	 * Trade data is available as CSV, delayed by approx. 15 minutes. It will return the 2000 most recent trades.
	 * http://api.bitcoincharts.com/v1/trades.csv?symbol=bitstampUSD[&start=UNIXTIME]
	 */
	public function historicData($symbol, $start = null){
	
		$url 	= 'http://api.bitcoincharts.com/v1/trades.csv';
		$method = 'GET';
		$data 	= Array('symbol' => $symbol, 'start' => $start);
	
		return \CryptoTraderHub\Core\Connection::request($url, $method, $data);
	}	
	
	/* 
	 * Complete history. This will require a lot of memory. Consider manually downloading 
	 * these files via: http://api.bitcoincharts.com/v1/csv
	 */ 
	public function completeHistory($symbol){
		
		$url 	= 'http://api.bitcoincharts.com/v1/csv/'.$symbol.'csv.gz';
		$method = 'GET';
		$data 	= Array();
	
		return \CryptoTraderHub\Core\Connection::request($url, $method, $data, Array('CURLOPT_ENCODING' => ''));
	}
	
}
