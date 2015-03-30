<?php
// Specify the extensions that may be loaded
spl_autoload_extensions('.php');

/**
 * The Autoloader function
 *
 * @param string $class_name The Class Name
 *
 * @access public
 */
spl_autoload_register(function ($class_name) {
	// Only try to load classes with the WAID namepsace prefix
	if(strpos($class_name, 'CryptoTraderHub\\') === 0){
		// Replace namespace separator with directory separator (prolly not required)
		$class_name = str_replace('\\', DIRECTORY_SEPARATOR, substr($class_name, 16));
	
		// Get full name of file containing the required class
		$file = CRYPTO_TRADER_HUB_ROOT.DIRECTORY_SEPARATOR.$class_name.".php";	
		
		// Load file if it exists
		if (is_readable($file)){
			require_once $file;		
		}
	}
});