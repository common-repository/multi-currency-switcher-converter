<?php
/**
* Uninstaller
*
* Uninstall the plugin by removing any options from the database
*
* @package	Multi-Currency-Switcher-Converter
* @since	1.0.4
*/

// If the uninstall was not called by WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Remove options
delete_option( 'multi_currency_converter' ); // Old version
?>