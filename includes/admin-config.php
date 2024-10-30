<?php
/**
* Admin Menu Functions
*
* Various functions relating to the various administration screens
*
* @package	Multi-currency-switcher-converter
*/

/**
* Show Admin Messages
*
* Display messages on the administration screen
*
* @since	1.2
*
*/

function mcsc_show_admin_messages() {

    $options = mcsc_get_options();
	$screen = get_current_screen();

    if ( ( ( !isset( $options[ 'id' ] ) ) or ( $options[ 'id' ] == '' ) ) && ( current_user_can( 'edit_posts' ) && ( $screen->id != 'settings_page_options' ) ) ) {
        echo '<div id="message" class="error"><p>' . __( '<a href="options-general.php?page=options">A valid API key must be specified</a>.', 'multi-currency-switcher-converter' ) . '</p></div>';
		$screen = get_current_screen();
    }
}

add_action( 'admin_notices', 'mcsc_show_admin_messages' );

/**
* Add Settings link to plugin list
*
* Add a Settings link to the options listed against this plugin
*
* @since	1.0.4
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function mcsc_add_settings_link( $links, $file ) {

	static $this_plugin;

	if ( !$this_plugin ) { $this_plugin = plugin_basename( __FILE__ ); }

	if ( strpos( $file, 'multi-currency-switcher-converter.php' ) !== false ) {
		$settings_link = '<a href="options-general.php?page=options">' . __( 'Settings', 'multi-currency-switcher-converter' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}
add_filter( 'plugin_action_links', 'mcsc_add_settings_link', 10, 2 );

/**
* Add meta to plugin details
*
* Add options to plugin meta line
*
* @since	1.0.4
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function mcsc_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'multi-currency-switcher-converter.php' ) !== false ) { $links = array_merge( $links, array( '<a href="http://wordpress.org/support/plugin/multi-currency-switcher-converter">' . __( 'Support', 'multi-currency-switcher-converter' ) . '</a>' ) ); }

	return $links;
}
add_filter( 'plugin_row_meta', 'mcsc_set_plugin_meta', 10, 2 );

/**
* Administration Menu
*
* Add a new option to the Admin menu and context menu
*
* @since	1.0.4
*
* @uses mcsc_help		Return help text
*/

function mcsc_menu() {

	// Add rates sub-menu

	global $mcsc_rates_hook;

	$mcsc_rates_hook = add_submenu_page( 'tools.php', 'Rates', __( 'Currency Rates', 'multi-currency-switcher-converter' ), 'edit_posts', 'rates', 'mcsc_rates' );

	
    // Add options sub-menu

    global $mcsc_options_hook;

	$mcsc_options_hook = add_submenu_page( 'options-general.php', 'Options', __( 'Currency Switcher', 'multi-currency-switcher-converter' ), 'install_plugins', 'options', 'mcsc_options' );


    

}
add_action( 'admin_menu','mcsc_menu' );

/**
* Add Options Help
*
* Add help tab to options screen
*
* @since	1.0.4
*
* @uses     mcsc_options_help    Return help text
*/

function mcsc_add_options_help() {

    global $mcsc_options_hook;
    $screen = get_current_screen();

    if ( $screen->id != $mcsc_options_hook ) { return; }

    $screen -> add_help_tab( array( 'id' => 'options-help-tab', 'title'	=> __( 'Help', 'multi-currency-switcher-converter' ), 'content' => mcsc_options_help() ) );

}

/**
* Add Rates Help
*
* Add help tab to exchange rates screen
*
* @since	1.0.4
*
* @uses     mcsc_search_help    Return help text
*/

function mcsc_add_rates_help() {

    global $mcsc_rates_hook;
    $screen = get_current_screen();

    if ( $screen->id != $mcsc_rates_hook ) { return; }

    $screen -> add_help_tab( array( 'id' => 'rates-help-tab', 'title' => 'Help', 'content' => mcsc_rates_help() ) );
}

/**
* Options screen
*
* Define an option screen
*
* @since	1.0.4
*/

function mcsc_options() {

	include_once( plugin_dir_path( __FILE__ ) . 'options.php' );

}

/**
* Rates screen
*
* Define the exchange rates screen
*
* @since	1.0.4
*/

function mcsc_rates() {

	include_once( WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) ) . 'rates.php' );

}

/**
* Options Help
*
* Return help text for options screen
*
* @since	1.0.4
*
* @return	string	Help Text
*/

function mcsc_options_help() {

	$help_text = 'refer to doc';
	
	return $help_text;
}

/**
* Rates Help
*
* Return help text for exchange rates screen
*
* @since	1.0.4
*
* @return	string	Help Text
*/

function mcsc_rates_help() {

	$help_text = 'refer to doc';

	return $help_text;
}
?>
