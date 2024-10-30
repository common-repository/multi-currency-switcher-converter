<?php
/**
* Exchange rates
*
* View exchange rate information
*
* @package	Multi-currency-switcher-converter
* @since	1.0.4
*
* @uses	ace_get_embed_paras	Get the options
*/

// Fetch information from external functions

$options = mcsc_get_options();
$rates_array = mcsc_get_rates( $options[ 'rates_cache' ] );
$codes_array = mcsc_get_codes( $options[ 'codes_cache' ] );
$symbols_array = mcsc_get_symbols( $options[ 'symbols_cache' ] );



// If a currency conversion has been requested, work out the result

if ( ( !empty( $_POST ) ) && ( check_admin_referer( 'rates' , 'multi_currency_converter_nonce' ) ) ) {
	$from = sanitize_text_field( $_POST[ 'mcsc_from' ] );
	$to = sanitize_text_field( $_POST[ 'mcsc_to' ] );
	$number = sanitize_text_field( $_POST[ 'mcsc_number' ] );
    $result = $from . ' ' . $number . ' = ' . $to . ' ' . mcsc_perform_conversion( $number, $from, $to, '2' );
} else {
    $result = false;
}
?>

<div class="wrap">

<?php if ( isset( $options[ 'id' ] ) && $options[ 'id' ] != '' ) { ?>

<table>
<tr><td colspan="4">&nbsp;</td></tr>
<?php
reset ($rates_array);
foreach ( $rates_array as $cc => $exchange_rate ) {
    echo '<tr><td width="100px">' . $cc . '</td><td width="300px">' . $codes_array[ $cc ] . '</td><td>' . $exchange_rate . '</td>';
    next ( $rates_array );
    echo "</tr>\n";
}
?>
</table>

<?php } ?>
</div>
