<?php

namespace CryptoTraderHub\Core;

// Class to interact with databases
class Database {
	
	private static $host;
	private static $username;
	private static $password;
	private static $database;
	private static $port;
	
	private static $db_conn;
	private static $db;

	public static function initialize($database_ini) {
		// If the connection has not been established, establish one		
		if(!isset(self::$db_conn)){
			// Parse the database ini file.
			$settings 			= parse_ini_file($database_ini);;
			self::$host 		= $settings['host'];
			self::$username 	= $settings['username'];
			self::$password 	= $settings['password'];
			self::$database 	= $settings['database'];
			self::$port 		= $settings['port'];
			
			self::$db_conn = new \mysqli(self::$host, self::$username, self::$password, self::$database, self::$port);
		}
	}
	
	// Get a single row.
	public static function getRow($sql_statement) {
		return self::executeQuery($sql_statement, 'single');
	}
	
	// Get a multiple rows.
	public static function getArray($sql_statement) {
		return self::executeQuery($sql_statement, 'multi');
	}

	// Run the query
	public static function runQuery($sql_statement) {
		return self::executeQuery($sql_statement, 'none');
	}

	// Get the character set
	public static function getCharSet() {
		return self::$db_conn->character_set_name();
	}

	// Set the character set
	public static function setCharSet($passed_string) {
		return self::$db_conn->set_charset($passed_string);
	}

	public static function escapeString($passed_string) {
		return self::$db_conn->escape_string($passed_string);
	}

	public static function lastInsertId() {
		return self::$db_conn->insert_id;
	}

	private static function executeQuery($sql_statement, $return_type) {
		if (self::$db_conn->multi_query($sql_statement)) {
			if ($return_type == 'single') {
				$row = array();
				if ($result = self::$db_conn->store_result()) {
					$row = $result->fetch_assoc();
					$result->free();
				}

				while (self::$db_conn->more_results() && self::$db_conn->next_result()) {
					$extraResult = self::$db_conn->use_result();
					if ($extraResult instanceof mysqli_result) {
						$extraResult->free();
					}
				}

				return $row;
			} elseif ($return_type == 'multi') {
				$result_arr = array();
				if ($result = self::$db_conn->store_result()) {
					while ($row = $result->fetch_assoc()) {
						$result_arr[] = $row;
					}
					$result->free();
				}

				while (self::$db_conn->more_results() && self::$db_conn->next_result()) {
					$extraResult = self::$db_conn->use_result();
					if ($extraResult instanceof mysqli_result) {
						$extraResult->free();
					}
				}

				return $result_arr;
			} elseif ($return_type == 'none') {
				return true;
			}
		} else {
			error_log("SQL ERROR:" . self::$db_conn->error, 0);
			error_log("SQL QUERY:" . $sql_statement, 0);
			return false;
		}
	}
	
	// Close the connection.
	public static function closeConnection() {
		return self::$db_conn->close();
	}
}