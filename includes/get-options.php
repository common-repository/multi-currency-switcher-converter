<?php
/**
* Get Parameters
*
* Fetch options - if none exist set them.
*
* @package	Multi-currency-switcher-converter
* @since	1.0.4
*
* @return	string	Array of default options
*/

function mcsc_get_options() {

	// Get options. If old options exist, transfer them to new

	$options = get_option( 'multi_currency_converter' );
	$changed = false;


	// If array doesn't exist, set defaults

	if ( !is_array( $options ) ) {
		$options = array( 'id' => '', 'from' => 'USD', 'to' => 'EUR', 'round' => 'nearest', 'dp' => 'match', 'rates_cache' => 10, 'codes_cache' => 24, 'symbols_cache' => 24  );
		$changed = true;
	}

	// Update the options, if changed, and return the result

	if ( $changed ) { update_option( 'multi_currency_converter', $options ); }

	return $options;
}
?>
