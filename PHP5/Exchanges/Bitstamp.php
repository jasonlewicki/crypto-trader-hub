<?php 

namespace CryptoTraderHub\Exchanges;

class Bitstamp extends \CryptoTraderHub\Exchanges\Exchange implements \CryptoTraderHub\Exchanges\ExchangeInterface {
	
	private $customer_id;
	private $api_key;
	private $api_secret;
	
	// Fees applied to buys and sells
	private $buy_fee;
	private $sell_fee;
	
	// Constructor
	public function __construct($exchange_ini) {
		
		parent::__construct($exchange_ini);
		
		$this->customer_id 	= $this->settings['customer_id'];
		$this->api_key 		= $this->settings['api_key'];
		$this->api_secret 	= $this->settings['api_secret'];
		$this->buy_fee		= $this->settings['buy_fee'];		
		$this->sell_fee		= $this->settings['sell_fee'];
	}
	
	// Request
	private function request($url, $method, $data, $auth_required){
		
		if($auth_required === true){
			$nonce 				= str_replace('.', '', microtime(true));
			$message 			= $nonce . $this->customer_id . $this->api_key;			
			$signature 			= base64_encode(hash_hmac('sha256', $message, $this->api_secret, true));
			
			// Add auth data
			$data['key'] 		= $this->api_key;
			$data['signature'] 	= $signature;
			$data['nonce'] 		= $nonce;
		}
		
		return \CryptoTraderHub\Core\Connection::request($url, $method, $data);	
	}
	
	// Tests
	public function testPublic(){$this->ticker();}	
	public function testPrivate(){$this->balance();}	
	
	// Public (no auth)
	public function ticker(){return $this->request('https://www.bitstamp.net/api/ticker/', 'GET', Array(), false);}
	public function orderBook(){return $this->request('https://www.bitstamp.net/api/order_book/', 'GET', Array(), false);}
	public function transactions($timeframe){return $this->request('https://www.bitstamp.net/api/transactions/', 'GET', Array('time'=>$timeframe), false);}
	
	// Private (auth required)
	public function balance(){return $this->request('https://www.bitstamp.net/api/balance/', 'POST', Array(), true);}
	public function userTransactions($limit, $offset){return $this->request('https://www.bitstamp.net/api/user_transactions/', 'POST', Array('limit'=>$limit,'offset'=>$offset), true);}
	public function openOrders(){return $this->request('https://www.bitstamp.net/api/open_orders/', 'POST', Array(), true);}
	public function cancelOrder($id){return $this->request('https://www.bitstamp.net/api/cancel_order/', 'POST', Array('id'=>$id), true);}
	public function buy($amount, $price){return $this->request('https://www.bitstamp.net/api/buy/', 'POST', Array('amount'=>$amount,'price'=>$price), true);}
	public function sell($amount, $price){return $this->request('https://www.bitstamp.net/api/sell/', 'POST', Array('amount'=>$amount,'price'=>$price), true);}
	public function withdraw($amount, $address){return $this->request('https://www.bitstamp.net/api/bitcoin_withdrawal/', 'POST', Array('amount'=>$amount,'address'=>$address), true);}
		
	// Helper methods
	public function buyFee(){return $this->buy_fee;}
	public function sellFee(){return $this->sell_fee;}
	
}
