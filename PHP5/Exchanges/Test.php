<?php 

namespace CryptoTraderHub\Exchanges;

// This class is for running your AI/algorithms against
class Test implements \CryptoTraderHub\Exchanges\Exchange{
	
	private $database;	
	private $table;
	
	private $market_data;
	
	private $time_start;
	private $time_end;
	private $time_index;
	
	private $balance_usd;	
	private $balance_btc;

	private $transaction_arr;
	
	// Constructor
	public function __construct($test_ini) {
		$settings 				= parse_ini_file($test_ini);
		$this->database			= $settings['database'];
		$this->table 			= $settings['table'];
		$this->time_start 		= $settings['time_start'];
		$this->time_end			= $settings['time_end'];	
		$this->balance_usd 		= $settings['balance_usd'];	
		$this->balance_btc 		= $settings['balance_btc'];	
		$this->time_index 		= 0;	
		$this->transaction_arr 	= Array();		
	
		$market_data = \CryptoTraderHub\Core\Database::getArray("SELECT * FROM {$this->database}.{$this->table} WHERE time > {$this->time_start} AND time < {$this->time_end};");	
	}
	
	// Request
	private function request($url, $method, $data, $auth_required){}
	
	// Tests
	public function testPublic(){}	
	public function testPrivate(){}	
	
	// Public (no auth)
	public function ticker(){
		return $this->market_data[$this->time_index];
	}
	public function orderBook(){}
	
	public function transactions($timeframe){
		//return $this->transaction_arr;
	}
	
	// Private (auth required)
	public function balance(){
		return Array('usd' => $this->balance_usd,'btc' => $this->balance_btc);
	}
	public function userTransactions($limit, $offset){
		//return $this->transaction_arr;		
	}
	public function openOrders(){}
	public function cancelOrder($id){}
	public function buy($amount, $price){}
	public function sell($amount, $price){}
	public function withdraw($amount, $address){}
	
	// Step the algorithm through the market data, 1 step at a time
	public function step(){
		if (isset($this->market_data[++$this->time_index])){
			return $this->market_data[$this->time_index];
		}else{
			throw new \Exception( 'End of data');	
		}
	}
	
}
