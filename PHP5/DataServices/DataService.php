<?php

namespace CryptoTraderHub\DataServices;

Interface DataServiceInterface{
	
	// TODO: Incomplete/Neccessary?
	
}

Class DataService{

	public function __construct() {
		// Start Database object
		\CryptoTraderHub\Core\Database::initialize(DATABASE_INI);
	}

}
