<?php

namespace CryptoTraderHub\Exchange;

Interface ExchangeInterface{
	
	// Request
	private function request($url, $method, $data, $auth_required);
	
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
	public function withdraw($amount, $address);
	
}

Class Exchange{
	
	public function __construct() {		
		// Start Database object
		\CryptoTraderHub\Core\Database::initialize(DATABASE_INI);
	}
	
}
