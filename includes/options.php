<?php
/**
* Set Default Options
*
* Allow the user to change the default options
*
* @package	Multi-currency-switcher-converter
* @since	1.0.4
*
* @uses	mcsc_get_options Get the options
*/
?>
<div class="wrap">
<h1><?php _e( 'Multi-Currency Switcher Converter', 'multi-currency-switcher-converter' ); ?></h1>
<?php

// If options have been updated on screen, update the database

$fetch_options = true;
if ( ( !empty( $_POST ) ) && ( check_admin_referer( 'options' , 'multi_currency_converter_options_nonce' ) ) ) {

    // Check validity of App ID

    $error = 'Invalid API key.';
	if ( isset( $_POST[ 'mcsc_app_id' ] ) ) {
			$file = mcsc_get_file( 'https://bankersalgo.com/apirates2/' . sanitize_text_field( $_POST[ 'mcsc_app_id' ] ) . '/USD');
			if ( $file[ 'rc' ] != 0 ) {
				$error = __( 'Could not validate your API key. Please try again. If the problem persist please check your <a href="https://bankersalgo.com/" target="_blank">Bankersalgo</a> account (1,000 free API requests per month).', 'multi-currency-switcher-converter' );
				$fetch_options = false;
			} else {
				if ( strpos( $file[ 'file' ], 'invalid_app_id' ) !== false ) { $fetch_options = false; }
			}
	}

    // Update the options array from the form fields.

    if ( $fetch_options ) { $options[ 'id' ] = $_POST[ 'mcsc_app_id' ]; }

	if ( isset( $_POST[ 'mcsc_to' ] ) ) { $options[ 'to' ] = sanitize_text_field( $_POST[ 'mcsc_to' ] ); }
	if ( isset( $_POST[ 'mcsc_dp' ] ) ) { $options[ 'dp' ] = sanitize_text_field( $_POST[ 'mcsc_dp' ] ); }

    // Check caches and ensure they are valid

    if ( isset( $_POST[ 'mcsc_rates_cache' ] ) ) { $options[ 'rates_cache' ] = mcsc_check_cache( sanitize_text_field( $_POST[ 'mcsc_rates_cache' ] ), 10 ); }
	$options[ 'codes_cache' ] = mcsc_check_cache( 24, 1 ); 
	$options[ 'symbols_cache' ] = mcsc_check_cache( 24, 1 ); 

    // Update the options

    update_option( 'multi_currency_converter', $options );

    if ( $fetch_options ) {

        echo '<div class="updated fade"><p><strong>' . __( 'Done.', 'multi-currency-switcher-converter' ) . "</strong></p></div>\n";

    } else {

        echo '<div class="error fade"><p><strong>' . $error . "</strong></p></div>\n";
    }
}

// Fetch options and rates into an array

$options = mcsc_get_options();
if ( $fetch_options && isset( $_POST[ 'mcsc_app_id' ] ) ) { $options[ 'key' ] = $_POST[ 'mcsc_app_id' ]; }
$rates_array = mcsc_get_rates( $options[ 'rates_cache' ] );
$codes_array = mcsc_get_codes( $options[ 'codes_cache' ] );
$symbols_array = mcsc_get_symbols( $options[ 'symbols_cache' ] );


if ( !isset( $options[ 'id' ] ) or $options[ 'id' ] == '' ) {
	echo '<div class="error fade"><p><strong>' . __( 'Please specify an API key.', 'multi-currency-switcher-converter' ) . '</strong></p></div>' . "\n";
}


?>

<form method="post" action="<?php echo get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=options' ?>">

<table class="form-table">


<tr>
<th scope="row">API key</th>
<td><label for="mcsc_app_id"><input type="text" size="50" maxlength="50" name="mcsc_app_id" value="<?php if ( isset( $options[ 'id' ] ) ) { echo esc_html( $options[ 'id' ] ); } ?>"/></label> 
<p><strong>Get your FREE API key on <a href="https://bankersalgo.com/" target="_blank">https://bankersalgo.com</a></strong> (API for foreign exchange rates and currency conversion)</p></td>
</tr>

<tr>
<th scope="row"><?php _e( 'Rates update frequency', 'multi-currency-switcher-converter' ); ?></th>
<td><label for="mcsc_rates_cache"><?php _e( 'Every', 'multi-currency-switcher-converter' ); ?> <input type="text" size="3" maxlength="3" name="mcsc_rates_cache" value="<?php echo esc_html( $options[ 'rates_cache' ] ); ?>"/><?php _e( " minutes.", 'multi-currency-switcher' ); ?></label></td>
</tr>
<tr>
<th scope="row"><?php _e( 'Decimal places', 'multi-currency-switcher-converter' ); ?></th>
<td><label for="mcsc_dp"><select name="mcsc_dp">
<option value="0"<?php if ( $options[ 'dp' ] == '0' ) { echo " selected='selected'"; } ?>>0</option>
<option value="1"<?php if ( $options[ 'dp' ] == '1' ) { echo " selected='selected'"; } ?>>1</option>
<option value="2"<?php if ( $options[ 'dp' ] == '2' ) { echo " selected='selected'"; } ?>>2</option>
<option value="3"<?php if ( $options[ 'dp' ] == '3' ) { echo " selected='selected'"; } ?>>3</option>
<option value="4"<?php if ( $options[ 'dp' ] == '4' ) { echo " selected='selected'"; } ?>>4</option>
<option value="5"<?php if ( $options[ 'dp' ] == '5' ) { echo " selected='selected'"; } ?>>5</option>
<option value="6"<?php if ( $options[ 'dp' ] == '6' ) { echo " selected='selected'"; } ?>>6</option>

</select></label>
</td>
</tr>




</table>

<?php wp_nonce_field( 'options', 'multi_currency_converter_options_nonce', true, true ); ?>

<br/><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Initialize', 'multi-currency-switcher-converter' ); ?>"/>

</form>

</div>
<div style="background-color:#FFF;padding:20px;margin-right:20px;margin-top:30px;border: 1px solid #CCC">
<p><strong><u><?php _e( 'Add the following shortcodes in your Posts, Pages or Custom html widgets / or PHP code anywhere in your theme :', 'multi-currency-switcher-converter' ); ?></u></strong></p>
<br />
<table>
<tr>
<td width="20%" align="center" valign="top"><p><u><strong><?php _e( 'Switcher', 'multi-currency-switcher-converter' ); ?></strong></u></p></td>
<td>
<?php _e( 'Shortcode example', 'multi-currency-switcher-converter' ); ?>: <strong>[switcher currencies="USD,AUD,CAD,CHF,EUR,GBP,CRYPTO_BTC,CRYPTO_ETH" type=1]</strong><br />
<?php _e( 'PHP example', 'multi-currency-switcher-converter' ); ?>: <strong>html_switcher( 'USD,AUD,CAD,CHF,EUR,GBP,CRYPTO_BTC,CRYPTO_ETH', 1 );</strong>
<br />
<?php _e( '- currencies: currencies to add in the switcher, first one should be your main currency', 'multi-currency-switcher-converter' ); ?><br />
<?php _e( '- type: currency name format (0: code - currency name - symbol) (1: code) (2: code symbol) (3: currency name - symbol)', 'multi-currency-switcher-converter' ); ?><br />

<br />
<br /><br /></td>
</tr>
<tr>
<td width="20%" align="center" valign="top"><p><u><strong>Prices</strong></u></p></td>
<td>
<?php _e( 'Shortcode example', 'multi-currency-switcher-converter' ); ?>: <strong>[price number=99.90 currency="USD"]</strong><br />
<?php _e( 'PHP example', 'multi-currency-switcher-converter' ); ?>: <strong>echo get_price( 'number=99.90&amp;currency=usd' );</strong>
<br />
<?php _e( '- number: price', 'multi-currency-switcher-converter' ); ?>
<br /><?php _e( '- currency: initial currency code', 'multi-currency-switcher-converter' ); ?>
<br /><br /></td>
</tr>
<tr>
<td width="20%" align="center" valign="top"><p><u><strong>Symbols</strong></u></p></td>
<td>
<?php _e( 'Shortcode example', 'multi-currency-switcher-converter' ); ?>: <strong>[symbol currency="USD"]</strong><br />
<?php _e( 'PHP example', 'multi-currency-switcher-converter' ); ?>: <strong>echo get_symbol( 'currency=usd' );</strong>
<br /><?php _e( '- currency: initial currency code', 'multi-currency-switcher-converter' ); ?>
<br /><br /></td>
</tr>
</table>
</div>
<?php 

if ( isset( $options[ 'id' ] ) && $options[ 'id' ] != '' ) { 

    $date = get_transient( 'mcsc_date' );
    
	echo '<center><p><i>';
	_e( 'Last rates update: ', 'multi-currency-switcher-converter' );
	echo '<br />' . $date . ' (Paris Time zone GMT+1)</i></p>';
    echo '<table border="1px"><tr><td><center><strong>Currency Code</strong></center></td><td><strong><center>Currency Name</center></strong></td><td><strong><center>Rate</center></strong></td></tr>';

	reset ($rates_array);

	foreach ( $rates_array as $cc => $exchange_rate ) {
  	  echo '<tr><td width="100px"><center>' . $cc . '</center></td><td width="300px">' . $codes_array[ $cc ] . '</td><td>' . $exchange_rate . '</td>';
  	  next ( $rates_array );
  	  echo "</tr>\n";
	}
    
    echo '</table></center>';

}


?>
