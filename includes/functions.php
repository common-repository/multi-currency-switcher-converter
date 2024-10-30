<?php
/**
* Functions
*
* Functions calls
*
* @package	Multi-currency-switcher-converter
*/

/**
* Conversion Function
*
* Function call to perform and return conversion
*
* @since	1.0.4
*
* @param	string	$paras		Parameters
* @return	string				Output
*/

function get_price( $paras = '' ) {

    // Extra parameters

    $number = mcsc_get_parameters( $paras, 'number' );
    $from = mcsc_get_parameters( $paras, 'currency' );
    $to = '';
    if(empty($to)){
    $to = $from;
	}
    if ( isset( $_POST["currency-switcher"] ) ) {
    $to = sanitize_text_field( $_POST["currency-switcher"] );
    }
    $dp = '';
    
    $to = strtoupper($to);
    $from = strtoupper($from);

    // Perform currency conversion using supplied parameters

    return mcsc_convert_currency( $number, $from, $to, $dp, '' );
}

/**
* Symbol Function
*
* Function call to return symbol
*
* @since	1.0.4
*
* @param	string	$paras		Parameters
* @return	string				Output
*/

function get_symbol( $paras = '' ) {

    // Extra parameters

    $currency = mcsc_get_parameters( $paras, 'currency' );
    
    if ( isset( $_POST["currency-switcher"] ) ) {
    $currency = sanitize_text_field( $_POST["currency-switcher"] );
    }
    $currency = strtoupper($currency);

    // Return symbol
    
	$symbols_array = mcsc_get_symbols();

    return $symbols_array[$currency];
}
?>