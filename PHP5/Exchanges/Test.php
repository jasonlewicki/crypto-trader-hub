<?php 

namespace CryptoTraderHub\Exchanges;

// This class is for running your AI/algorithms against
class Test extends \CryptoTraderHub\Exchanges\Exchange implements \CryptoTraderHub\Exchanges\ExchangeInterface{
	
	// RDS DB and Table
	private $database;	
	private $table;
	
	// Holds the market data that was pulled from the DB
	private $market_data;
	
	// Current unique id for market data
	private $market_data_index;
		
	// Bounds and index of the market data
	private $time_start;
	private $time_end;
		
	// Balance of the user's fake account
	private $balance_usd;	
	private $balance_btc;

	// Fees applied to buys and sells
	private $buy_fee;
	private $sell_fee;

	// List of the user's transactions
	private $transaction_arr;
	
	// Current unique id for transactions
	private $transaction_index;
	
	// Constructor
	public function __construct($test_ini) {
		
		parent::__construct($test_ini);
		
		$this->database			= $this->settings['database'];
		$this->table 			= $this->settings['table'];
		$this->time_start 		= $this->settings['time_start'];
		$this->time_end			= $this->settings['time_end'];	
		$this->balance_usd 		= $this->settings['balance_usd'];	
		$this->balance_btc 		= $this->settings['balance_btc'];	
		$this->buy_fee		 	= $this->settings['buy_fee'];		
		$this->sell_fee		 	= $this->settings['sell_fee'];
		$this->transaction_arr 	= Array();			
		$this->transaction_index= 1;		
		$this->market_data		= Array();
		$this->market_data_index= 0;	
				
		$this->market_data = \CryptoTraderHub\Core\Database::getArray("SELECT * FROM {$this->database}.{$this->table} WHERE date >= '{$this->time_start}' AND date <= '{$this->time_end}' AND {$this->table}_id > {$this->market_data_index} LIMIT 5000;");
		
	}
	
	// Request
	private function request($url, $method, $data, $auth_required){}
	
	// Tests
	public function testPublic(){return true;}	
	public function testPrivate(){return true;}	
	
	// Public (no auth)
	public function ticker(){return current($this->market_data);}
	public function orderBook(){return Array();}
	
	// Return a list of transactions withing the requested timeframe ()
	public function transactions($timeframe){
		$subset_arr = Array();
		foreach($this->transaction_arr as $key => $value){
			if(strtotime($value['date']) >= (time() - strtotime($timeframe))){
				$subset_arr[] = $transaction;					
			}
		}
		return $subset_arr;
	}
	
	// Private (auth required)
	public function balance(){
		return Array('usd' => $this->balance_usd,'btc' => $this->balance_btc);
	}
	
	// List of user transactions with limit and offset
	public function userTransactions($limit, $offset){
		$subset_arr = Array();
		$transaction_index = count($this->transaction_arr) - 1 - $offset;
		$transaction_limit = $transaction_index - $limit;
		for($transaction_index; $transaction_index > $transaction_limit && $transaction_index >= 0; $transaction_index--){
			$subset_arr[] = $transaction;
		}		
		return $subset_arr;		
	}
	
	// List of open orders
	public function openOrders(){
		$subset_arr = Array();
		foreach($this->transaction_arr as $key => $value){
			if($value['order_status'] == "open"){
				$subset_arr[] = $transaction;					
			}
		}
		return $subset_arr;
	}
	
	// Cancel an order
	public function cancelOrder($id){
		foreach($this->transaction_arr as $key => $value){
			if($value['id'] == $id){
				if($this->transaction_arr[$key]['status'] == "open"){
					if($this->transaction_arr[$key]['type'] == "buy"){
						$this->balance_usd = floor(($this->balance_usd + (($this->transaction_arr[$key]['amount'] - $this->transaction_arr[$key]['fulfilled']) * $this->transaction_arr[$key]['price']))*100)/100;							
					}else if($this->transaction_arr[$key]['type'] == "sell"){
						$this->balance_btc = floor(($this->balance_btc + ($this->transaction_arr[$key]['amount'] - $this->transaction_arr[$key]['fulfilled']))*100000000)/100000000;
					}
				}
				$this->transaction_arr[$key]['status'] = 'cancelled';
				return true;					
			}
		}
		return false;
	}
	
	// Purchase
	public function buy($amount, $price){
		if($amount*$price > $this->balance_usd){
			throw new \Exception( 'Balance is: $'.$this->balance_usd . " Tried to buy: $".$amount*$price);
		}
		
		$new_transaction = Array(
			'id' => $this->transaction_index++, 
			'status' => 'open', 
			'type' => 'buy',
			'amount' => $amount,
			'price' => $price,
			'fulfilled' => 0.0 
		);
		
		$this->transaction_arr[] = $new_transaction;
		$this->balance_usd = floor(($this->balance_usd - $amount*$price)*100)/100;
				
		return $new_transaction;
	}
	
	// Sell
	public function sell($amount, $price){
		if($amount > $this->balance_btc){
			throw new \Exception( 'Balance is: B'.$this->balance_btc . " Tried to sell: B".$amount);
		}
		
		$new_transaction = Array(
			'id' => $this->transaction_index++, 
			'status' => 'open', 
			'type' => 'sell',
			'amount' => $amount,
			'price' => $price,
			'fulfilled' => 0.0 
		);
		
		$this->transaction_arr[] = $new_transaction;
		$this->balance_btc = floor(($this->balance_btc - $amount)*100000000)/100000000;
				
		return $new_transaction;		
	}
	
	// Not implemented
	public function withdraw($amount, $address){return true;}
	
	// Step the algorithm through the market data, 1 step at a time (check limit orders)
	public function step(){
		
		if (current($this->market_data) === false) {
            $this->market_data = \CryptoTraderHub\Core\Database::getArray("SELECT * FROM {$this->database}.{$this->table} WHERE date > '{$this->time_start}' AND date < '{$this->time_end}' AND {$this->table}_id > {$this->market_data_index} LIMIT 5000;");
			if (current($this->market_data) === false) {
				//throw new \Exception( 'End of data');
				return false;
			}
        } else {
        	
			$market_data_current = current($this->market_data);
			
			// Perform Exchange operations
			foreach($this->transaction_arr as $key => $value){
				if($this->transaction_arr[$key]['status'] == "open"){					
					if($this->transaction_arr[$key]['type'] == "buy"){
						if ($this->transaction_arr[$key]['price'] >= $market_data_current['price']){
							$this->balance_btc = floor(($this->balance_btc + ($this->transaction_arr[$key]['amount'] - $this->transaction_arr[$key]['fulfilled']))*100000000)/100000000;
							$this->transaction_arr[$key]['fulfilled'] = $this->transaction_arr[$key]['amount'];
							$this->transaction_arr[$key]['status'] = 'fulfilled';							
						}
					}else if($this->transaction_arr[$key]['type'] == "sell"){
						if ($this->transaction_arr[$key]['price'] <= $market_data_current['price']){
							$this->balance_us = floor(($this->balance_usd + (($this->transaction_arr[$key]['amount'] - $this->transaction_arr[$key]['fulfilled']) * $this->transaction_arr[$key]['price']))*100)/100;
							$this->balance_btc = floor(($this->balance_btc + ($this->transaction_arr[$key]['amount'] - $this->transaction_arr[$key]['fulfilled']))*100000000)/100000000;
							$this->transaction_arr[$key]['fulfilled'] = $this->transaction_arr[$key]['amount'];
							$this->transaction_arr[$key]['status'] = 'fulfilled';
						}
					}
				}					
			}			
	
			$this->market_data_index = $market_data_current[$this->table.'_id'];
			next($this->market_data);

			return $market_data_current;
        }		
		
	}
	
}
