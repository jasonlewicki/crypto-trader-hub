<?php

/**
 * The environment is used for the database configuration file. If you have no
 * database, this won't matter.
 */
define('ENVIRONMENT', 'development');

/**
 * Set the application root. This is the actual root of your application.
 */
define('APP_ROOT', '/var/.../application/root');

/**
 * Set the CryptoTraderHub root.
 */
define('CRYPTO_TRADER_HUB_ROOT', '/var/.../crypto-trader-hub');

/**
 * Error reporting. Comment out to remove error reporting.
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);