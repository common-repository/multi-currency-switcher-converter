<?php
/**
* Shortcodes
*
* Shortcode functions
*
* @package	Multi-currency-switcher-converter
*/

/**
* Conversion Shortcode
*
* Shortcode to perform and output conversion
*
* @since	1.0.4
*
* @param	string	$paras		Shortcode parameters
* @param	string	$content	Optional template
* @return	string				Output
*/


function price( $paras = '', $content = '' ) {

	extract( shortcode_atts( array( 'number' => '', 'currency' => '', 'to' => '', 'dp' => '', 'template' => '' ), $paras ) );

    // If content provided, assume this to be the template

    if ( $content != '' ) { $template = $content; }

    // Perform currency conversion using supplied parameters
    
	if(empty($to)){
    $to = $currency;
	}
	
    if ( isset( $_POST["currency-switcher"] ) ) {
    $to = sanitize_text_field( $_POST["currency-switcher"] );
    }
    $to = strtoupper($to);


    return mcsc_convert_currency( $number, $currency, $to, $dp, $template );

}

// Switcher

function html_switcher( $currencies = '', $type = '' ) {
		
	$html = '';
	
		if ($type == 1){

    $arr = explode(",",$currencies);

    $html.= '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
    $html.= '<select name="currency-switcher" onchange="if(this.value != 0) { this.form.submit(); }">';
    
    if ( isset( $_POST["currency-switcher"] ) ) {
    $selected = sanitize_text_field( $_POST["currency-switcher"] );
    $html.= '<option value="' . $selected . '"';
	$html.= '>' . $selected . '</option>';
    }
    
    if ( is_array( $arr) ) {
	foreach( $arr as $cc ){
		if ( isset( $_POST["currency-switcher"] ) ) {
   		 $selected = sanitize_text_field( $_POST["currency-switcher"] );
   		 if ( $cc != $selected ) {
   		 $html.= '<option value="' . $cc . '"';
		 $html.= '>' . $cc . '</option>';
   		 }
   		} 
   		else {
		$html.= '<option value="' . $cc . '"';
		$html.= '>' . $cc . '</option>';
		next( $arr );
		}
	}}
	$html.= '</select>';
    $html.= '</form>';
	}
	
	else if ($type == 2){
	$codes_array = mcsc_get_symbols();

    $arr = explode(",",$currencies);

    $html.= '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    $html.= '<select name="currency-switcher" onchange="if(this.value != 0) { this.form.submit(); }">';
    
    if ( isset( $_POST["currency-switcher"] ) ) {
    $selected = sanitize_text_field( $_POST["currency-switcher"] );
    $html.= '<option value="' . $selected . '"';
	$html.= '>' . $selected . ' ' . $codes_array[$selected] . '</option>';
    }
    
    if ( is_array( $arr) ) {
	foreach( $arr as $cc ){
		if ( isset( $_POST["currency-switcher"] ) ) {
   		 $selected = sanitize_text_field( $_POST["currency-switcher"] );
   		 if ( $cc != $selected ) {
   		 $html.= '<option value="' . $cc . '"';
		 $html.= '>' . $cc . ' ' . $codes_array[$cc] . '</option>';
   		 }
   		} 
   		else {
		$html.= '<option value="' . $cc . '"';
		$html.= '>' . $cc . ' ' . $codes_array[$cc] . '</option>';
		next( $arr );
		}
	}}
	$html.= '</select>';
    $html.= '</form>';
	}
	
	else if ($type == 3){
	$codes_array = mcsc_get_codes();

    $arr = explode(",",$currencies);

    $html.= '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    $html.= '<select name="currency-switcher" onchange="if(this.value != 0) { this.form.submit(); }">';
    
    if ( isset( $_POST["currency-switcher"] ) ) {
    $selected = sanitize_text_field( $_POST["currency-switcher"] );
    $html.= '<option value="' . $selected . '"';
	$html.= '>' . $codes_array[$selected] . '</option>';
    }
    
    if ( is_array( $arr) ) {
	foreach( $arr as $cc ){
		if ( isset( $_POST["currency-switcher"] ) ) {
   		 $selected = sanitize_text_field( $_POST["currency-switcher"] );
   		 if ( $cc != $selected ) {
   		 $html.= '<option value="' . $cc . '"';
		 $html.= '>' . $codes_array[$cc] . '</option>';
   		 }
   		} 
   		else {
		$html.= '<option value="' . $cc . '"';
		$html.= '>' . $codes_array[$cc] . '</option>';
		next( $arr );
		}
	}}
	$html.= '</select>';
    $html.= '</form>';
	}	
	
	else {
	$codes_array = mcsc_get_codes();

    $arr = explode(",",$currencies);

    $html.= '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    $html.= '<select name="currency-switcher" onchange="if(this.value != 0) { this.form.submit(); }">';
    
    if ( isset( $_POST["currency-switcher"] ) ) {
    $selected = sanitize_text_field( $_POST["currency-switcher"] );
    $html.= '<option value="' . $selected . '"';
	$html.= '>' . $selected . ' - ' . $codes_array[$selected] . '</option>';
    }
    
    if ( is_array( $arr) ) {
	foreach( $arr as $cc ){
		if ( isset( $_POST["currency-switcher"] ) ) {
   		 $selected = sanitize_text_field( $_POST["currency-switcher"] );
   		 if ( $cc != $selected ) {
   		 $html.= '<option value="' . $cc . '"';
		 $html.= '>' . $cc . ' - ' . $codes_array[$cc] . '</option>';
   		 }
   		} 
   		else {
		$html.= '<option value="' . $cc . '"';
		$html.= '>' . $cc . ' - ' . $codes_array[$cc] . '</option>';
		next( $arr );
		}
	}}
	$html.= '</select>';
    $html.= '</form>';
	}	
	
	return $html;	
}

function switcher( $paras = '' ) {

	extract( shortcode_atts( array( 'currencies' => '', 'type' => ''  ), $paras ) );
	$currencies = str_replace(" ","",$currencies);
	
	$currencies = strtoupper($currencies);


	return html_switcher( $currencies, $type );
    
}

// Symbols

function symbol( $paras = '' ) {

	extract( shortcode_atts( array( 'currency' => '' ), $paras ) );
	
	$initial = str_replace(" ","",$currency);

	$symbols_array = mcsc_get_symbols();
	
    if ( isset( $_POST["currency-switcher"] ) ) {
    $initial = sanitize_text_field( $_POST["currency-switcher"] );
    }
    
    $initial = strtoupper($initial);

    
    return $symbols_array[$initial];    
}


add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

add_filter( 'the_excerpt', 'shortcode_unautop');
add_filter( 'the_excerpt', 'do_shortcode');

add_filter( 'term_description', 'shortcode_unautop');
add_filter( 'term_description', 'do_shortcode' );

add_shortcode( 'switcher', 'switcher' );
add_shortcode( 'price', 'price' );
add_shortcode( 'symbol', 'symbol' );



?>