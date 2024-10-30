<?php
/*
Plugin Name: Multi-Currency Switcher Converter
Plugin URI: https://wordpress.org/plugins/multi-currency-switcher-converter/
Description: Convert currencies & cryptocurrencies through a switcher anywhere in your website.
Version: 1.0.4
Author: Webcitric
Author URI: https://webcitric.com
Text Domain: multi-currency-switcher-converter
*/

/**
* Multi-Currency Switcher Converter
*
* Main code - include various functions
*
* @package	Multi-Currency-Switcher-Converter
* @since	1.0.4
*/

define( 'multi_currency_converter_version', '1.0.4' );

/**
* Main Includes
*
* Include all the plugin's functions
*
* @since	1.0.4
*/

$functions_dir = plugin_dir_path( __FILE__ ) . 'includes/';

// Include all the various functions

include_once( $functions_dir . 'admin-config.php' );        	// Assorted admin configuration changes

include_once( $functions_dir . 'get-options.php' );             // Fetch/create default options

include_once( $functions_dir . 'shared-functions.php' );		// Shared functionality

include_once( $functions_dir . 'convert-currency.php' );		// Main code to perform currency conversion

include_once( $functions_dir . 'shortcodes.php' );	        	// Shortcodes

include_once( $functions_dir . 'functions.php' );	        	// PHP function calls
?>