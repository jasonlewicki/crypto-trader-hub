<?php

/**
 * Location and name of database INI file
 */
define('DATABASE_INI', '/var/.../application/root/configs/database_example.ini');

/**
 * Set the application root. This is the actual root of your application.
 */
define('APP_ROOT', '/var/.../application/root');

/**
 * Set the tmp working directory. This is used for downloading large CSVs and other data files.
 */
define('APP_TMP', APP_ROOT.'/tmp');

/**
 * Set the CryptoTraderHub root.
 */
define('CRYPTO_TRADER_HUB_ROOT', '/var/.../crypto-trader-hub');

/**
 * Error reporting. Comment out to remove error reporting.
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);