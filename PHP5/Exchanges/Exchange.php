<?php

namespace CryptoTraderHub\Exchanges;

Interface ExchangeInterface{
	
	// Request
	//private function request($url, $method, $data, $auth_required);
	
	// Tests
	public function testPublic();	
	public function testPrivate();	
	
	// Public (no auth)
	public function ticker();
	public function orderBook();
	public function transactions($timeframe);
	
	// Private (auth required)
	public function balance();
	public function userTransactions($limit, $offset);
	public function openOrders();
	public function cancelOrder($id);
	public function buy($amount, $price);
	public function sell($amount, $price);
	public function buyFee();
	public function sellFee();
	public function withdraw($amount, $address);
	
}

Class Exchange{
	
	protected $settings;
	
	public function __construct($exchange_ini) {		
		// Start Database object
		\CryptoTraderHub\Core\Database::initialize(DATABASE_INI);
		
		/*
		 * Check if the INI file exists and there is a value set for each field.
		 * This won't stop really bad errors, but it will give hints to users trying 
		 * to debug common errors
		 */ 
		if (is_readable($exchange_ini)){			
			$this->settings = parse_ini_file($exchange_ini);			
			foreach($this->settings as $key => $value){
				if(strlen($value) == 0){
					throw new \Exception($key.' field missing in ini file.');
				}
			}	
		}else{
			throw new \Exception($exchange_ini . ' not found.');
		}
		
	}
	
}
