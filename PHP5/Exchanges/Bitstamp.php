<?php 

namespace CryptoTraderHub\Exchanges;


class Bitstamp extends \CryptoTraderHub\Exchanges\Exchange implements \CryptoTraderHub\Exchanges\Exchange {
	
	private $client_id;
	private $api_key;
	private $api_secret;
	
	// Constructor
	public function __construct($exchange_ini) {
		
		parent::__construct();
		
		$settings 			= parse_ini_file($exchange_ini);
		$this->client_id 	= $settings['client_id'];
		$this->api_key 		= $settings['api_key'];
		$this->api_secret 	= $settings['api_secret'];
	}
	
	// Request
	private function request($url, $method, $data, $auth_required){
		
		if($auth_required === true){
			$nonce 				= str_replace('.', '', microtime(true));
			$message 			= $nonce . $this->client_id . $this->api_key;			
			$signature 			= base64_encode(hash_hmac('sha256', $message, $this->api_secret, true));
			
			// Add auth data
			$data['key'] 		= $this->api_key;
			$data['signature'] 	= $signature;
			$data['nonce'] 		= $nonce;
		}
		
		return \CryptoTraderHub\Core\Connection::request($url, $method, $data);	
	}
	
	// Tests
	public function testPublic(){}	
	public function testPrivate(){}	
	
	// Public (no auth)
	public function ticker(){}
	public function orderBook(){}
	public function transactions($timeframe){}
	
	// Private (auth required)
	public function balance(){}
	public function userTransactions($limit, $offset){}
	public function openOrders(){}
	public function cancelOrder($id){}
	public function buy($amount, $price){}
	public function sell($amount, $price){}
	public function withdraw($amount, $address){}
	
}
