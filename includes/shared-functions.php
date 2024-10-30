<?php
/**
* Shared Functions Functions
*
* Various functions that are called from a number of sources
*
* @package	Multi-currency-switcher-converter
*/

/**
* Get exchange rates
*
* Extract exchange rates and convert from JSON to an array
*
* @since	1.0.4
*
* @param    string  $cache      Length of time to cache results (optional, default 10 minutes)
* @return	string				Array containing exchange rates
*/

function mcsc_get_rates( $cache = 10 ) {

    $rates = false;
    $cache_key = 'mcsc_rates';

    // Check if a cached version already exists - if so, return it

    if ( strtolower( $cache ) != 'no' ) { $rates = get_transient( $cache_key );
     }

    // If cache doesn't exist use CURL to get the exchange rates

    if ( !$rates ) {

        $options = mcsc_get_options();
        

        if ( !isset( $options[ 'id' ] ) or $options[ 'id' ] == '' ) {

            $rates = 'Setup API key';

        } else {
        

            $file = mcsc_get_file( 'https://bankersalgo.com/apirates2/' . $options[ 'id' ] . '/USD');

            if ( $file[ 'rc' ] != 0 ) {

                $rates = 'Could not fetch exchange rate information';

            } else {

                $json = $file[ 'file' ];
                
                // Decode the JSON output to an array

                $array = json_decode( $json, true );

                // Extract out just the rates element of the array

                if ( isset( $array[ 'rates' ] ) ) { 
                $rates = $array[ 'rates' ];
                $date = $array[ 'date' ];
                }

                // Check that something was returned

                if ( $rates == '' ) {

                    $rates = 'No exchange rate information returned';

                } else {

                    // Save to cache

                    if ( strtolower( $cache ) != 'no' ) { 
                    set_transient( $cache_key, $rates, 60 * $cache );
                    set_transient( 'mcsc_date', $date, 60 * $cache );

                     }
                }
            }
        }
    }
   
	
    return $rates;

}

/**
* Get currency code
*
* Extract currency codes and convert from JSON to an array
*
* @since	1.0.4
*
* @param    string  $cache      Length of time to cache results (optional, default 24 hours)
* @return	string				Array containing currency codes
*/

function mcsc_get_codes( $cache = 24 ) {


    $codes = false;
    $cache_key = 'mcsc_currencynames';

    // Check if a cached version already exists - if so, return it

    if ( strtolower( $cache ) != 'no' ) { $codes = get_transient( $cache_key ); }

    // If cache doesn't exist use CURL to get the currency codes

    if ( !$codes ) {
    


        $options = mcsc_get_options();


        if ( !isset( $options[ 'id' ] ) or $options[ 'id' ] == '' ) {

            $codes = '';

        } else {
        
        	$file = mcsc_get_file( 'https://bankersalgo.com/currencies_names_plugin/' . $options[ 'id' ] );

            if ( $file[ 'rc' ] != 0 ) {

                $codes = 'Could not fetch API code';

            } else {


                $json = $file[ 'file' ];

				// Decode the JSON output to an array

                $codes = json_decode( $json, true );
                

                // Extract out just the rates element of the array
				$currencies = '';
				
                if ( isset( $codes[ 'currencies' ] ) ) { $currencies = $codes[ 'currencies' ]; }

                // Check that something was returned

                if ( $currencies == '' ) {

                    $currencies = 'No exchange rate information returned';

                } else {

                    // Save to cache

                    if ( strtolower( $cache ) != 'no' ) { set_transient( $cache_key, $currencies, 3600 * $cache ); }
                }
                
            }
        }
    }

    return $codes;
}

/**
* Get currency code
*
* Extract currency codes and convert from JSON to an array
*
* @since	1.0.4
*
* @param    string  $cache      Length of time to cache results (optional, default 24 hours)
* @return	string				Array containing currency codes
*/

function mcsc_get_symbols( $cache = 24 ) {


    $codes = false;
    $cache_key = 'mcsc_currencysymbols';

    // Check if a cached version already exists - if so, return it

    if ( strtolower( $cache ) != 'no' ) { $codes = get_transient( $cache_key ); }

    // If cache doesn't exist use CURL to get the currency codes

    if ( !$codes ) {
    


        $options = mcsc_get_options();

		if ( isset( $options[ 'id' ] ) ) { $file = mcsc_get_file( 'https://bankersalgo.com/currencies_symbols_plugin/' . $options[ 'id' ] ); }

        if ( !isset( $options[ 'id' ] ) or $options[ 'id' ] == '' ) {

            $rates = 'Setup API key';

        } else {

            if ( $file[ 'rc' ] != 0 ) {

                $rates = 'Could not fetch API information';

            } else {


                $json = $file[ 'file' ];

				// Decode the JSON output to an array

                $codes = json_decode( $json, true );
                

                // Extract out just the rates element of the array
                
                $currencies = '';

                if ( isset( $codes[ 'currencies' ] ) ) { $currencies = $codes[ 'currencies' ]; }

                // Check that something was returned

                if ( $currencies == '' ) {

                    $currencies = 'No exchange rate information returned';

                } else {

                    // Save to cache

                    if ( strtolower( $cache ) != 'no' ) { set_transient( $cache_key, $currencies, 3600 * $cache ); }
                }
                
            }
        }
    }

    return $codes;
}

/**
* Check cache values
*
* Ensure cache values are valid. If not, correct
*
* @since	1.1
*
* @param	string	$cache_value	Cache value
* @param	string	$min_value		Minimum value
* @return	string				    Resulting cache value
*/

function mcsc_check_cache( $cache_value, $min_value ) {

    $cache_value = trim( $cache_value );

    if ( !is_numeric( $cache_value ) ) {
        $cache_value= $min_value;
    } else {
        if ( ( $cache_value < $min_value ) or ( $cache_value > 999 ) ) {
            $cache_value = $min_value;
        }
    }

    return $cache_value;
}

/**
* Fetch a file (1.6)
*
* Use WordPress API to fetch a file and check results
* RC is 0 to indicate success, -1 a failure
*
* @since	1.0.4
*
* @param	string	$filein		File name to fetch
* @param	string	$header		Only get headers?
* @return	string				Array containing file contents and response
*/

function mcsc_get_file( $filein, $header = false ) {

	$rc = 0;
	$error = '';
	if ( $header ) {
		$fileout = wp_remote_head( $filein );
		if ( is_wp_error( $fileout ) ) {
			$error = 'Header: ' . $fileout -> get_error_message();
			$rc = -1;
		}
	} else {
		$fileout = wp_remote_get( $filein );
		if ( is_wp_error( $fileout ) ) {
			$error = 'Body: ' . $fileout -> get_error_message();
			$rc = -1;
		} else {
			if ( isset( $fileout[ 'body' ] ) ) {
				$file_return[ 'file' ] = $fileout[ 'body' ];
			}
		}
	}

	$file_return[ 'error' ] = $error;
	$file_return[ 'rc' ] = $rc;
	if ( !is_wp_error( $fileout ) ) {
		if ( isset( $fileout[ 'response' ][ 'code' ] ) ) {
			$file_return[ 'response' ] = $fileout[ 'response' ][ 'code' ];
		}
	}

	return $file_return;
}

/**
* Extract Parameters (1.1)
*
* Function to extract parameters from an input string
*
* @since	1.0.4
*
* @param	$input	string	Input string
* @param	$para	string	Parameter to find
* @return			string	Parameter value
*/

function mcsc_get_parameters( $input, $para, $divider = '=', $seperator = '&' ) {

    $start = strpos( strtolower( $input ), $para . $divider);
    $content = '';
    if ( $start !== false ) {
        $start = $start + strlen( $para ) + 1;
        $end = strpos( strtolower( $input ), $seperator, $start );
        if ( $end !== false ) { $end = $end - 1; } else { $end = strlen( $input ); }
        $content = substr( $input, $start, $end - $start + 1 );
    }
    return $content;
}
?>