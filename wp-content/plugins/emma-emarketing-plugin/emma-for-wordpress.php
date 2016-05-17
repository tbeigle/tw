<?php

/**
 * Plugin Name: Emma For WordPress
 * Plugin URI: http://ahsodesigns.com/what-we-do/plugin-development/
 * Description: The Emma WordPress plugin allows you to quickly and easily add a signup form for your Emma list as a widget or a shortcode.
 * Version: 1.2.4.2
 * Author: Ah So
 * Author URI: http://ahsodesigns.com
 * Contributors: ahsodesigns, brettshumaker, thackston
 * License: GPLv2
 *
 */

/*  Copyright 2012 Ah SO Designs  (email : info@ahsodesigns.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// proxy for debugging emma API calls
//define('WP_PROXY_HOST','192.168.1.7');
//define('WP_PROXY_PORT','8888');

define( 'EMMA_EMARKETING_PATH',     dirname( __FILE__ ) );
define( 'EMMA_EMARKETING_URL',      plugins_url( '', __FILE__ ) );
define( 'EMMA_EMARKETING_FILE',     plugin_basename( __FILE__ ) );
define( 'EMMA_EMARKETING_ASSETS',   EMMA_EMARKETING_URL . '/assets' );

// just sos ya know, this can't be called from the main class.
// i don't want to talk about it.
register_activation_hook( __FILE__, array( 'Start', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Start','plugin_deactivation' ) );

include_once( EMMA_EMARKETING_PATH . '/class-emma-emarketing.php' );

include_once('admin/class-account-information.php');
include_once('admin/class-advanced-settings.php');
include_once('admin/class-form-setup.php');
include_once('admin/class-form-custom.php');

function emma_admin_styles(){
	wp_register_script('emma admin js', EMMA_EMARKETING_URL . '/assets/js/emma-admin.js', array('jquery'), '201501261441');
	wp_enqueue_script('emma admin js');
	
	wp_localize_script('emma admin js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	
	
    wp_register_style('emma admin', EMMA_EMARKETING_URL . '/assets/css/emma-admin-styles.css' );
    wp_enqueue_style('emma admin');
}

add_action( 'admin_enqueue_scripts', 'emma_admin_styles' );

function emma_frontend_scripts() {
	wp_register_script('emma js', EMMA_EMARKETING_URL . '/assets/js/emma.js', array('jquery'), '201501261441');
	// We'll enqueue the script whenever we output the form so we're not loading it on pages without our form.
	
	wp_localize_script('emma js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
}
add_action( 'init', 'emma_frontend_scripts');

// instantiate main class
$emma_emarketing = new Emma_Emarketing();

// class this sh!t out, and pass it into the activation hook.
// aaah, much better.
class Start {

    private $error_txt;

    function __construct() {

    }

    function plugin_activation() {

        if( version_compare( PHP_VERSION, '5.2.6', '<' ) ) {
            $this->error_txt = 'The Emma For WordPress plugin requires at least PHP 5.2.6.';
        }
        if( version_compare( get_bloginfo( 'version' ), '3.1', '<' ) ) {
            $this->error_txt = 'The Emma For WordPress plugin requires at least WordPress version 3.1.';

        }

        // probably should do some checking before sending this off...
        add_action( 'admin_notices', array( &$this, 'version_require' ) );

        // load default options into database on activation
        // add_option( $option, $value, $depreciated, $autoload );
		add_option( Account_Information::$key, Account_Information::get_settings_defaults(), '', 'yes' );
		add_option( Advanced_Settings::$key, Advanced_Settings::get_settings_defaults(), '', 'yes' );
		add_option( Form_Setup::$key, Form_Setup::get_settings_defaults(), '', 'yes' );
		add_option( Form_Custom::$key, Form_Custom::get_settings_defaults(), '', 'yes' );

    }

    function plugin_deactivation() {
		delete_option('emma_account_information');
		delete_option('emma_advanced_settings');
		delete_option('emma_form_custom');
		delete_option('emma_form_setup');
        // buh-bye!
    }

    function version_require() {
        if( current_user_can( 'manage_options' ) )
            echo '<div class="error"><p>' . $this->error_txt . '</p></div>';
    }

} // end class Start



// setup our AJAX actions for the front-end - I couldn't do this from the class for whatever reason
add_action( 'wp_ajax_emma_ajax_form_submit', 'emma_ajax_form_submit_callback' );
add_action( 'wp_ajax_nopriv_emma_ajax_form_submit', 'emma_ajax_form_submit_callback' );

function emma_ajax_form_submit_callback() {
	
	$emma_email = $_POST['emma_email'];
	$emma_firstname = $_POST['emma_firstname'];
	$emma_lastname = $_POST['emma_lastname'];
	$emma_signup_form = $_POST['emma_signup_form_id'];
	
	$emma_form = new Emma_Form();
	$emma_form->generate_form($_POST);
	
	$status_text = $emma_form->status_txt;
	$response = $emma_form->emma_response;
	
	$advanced_settings = get_option('emma_advanced_settings');
    $success_pixel = $advanced_settings['successTrackingPixel'];

	$response_array = array(
		'status_txt' => $status_text,
		'code' => $response,
		'raw_data' => $emma_form->raw_data,
		'raw_response' => $emma_form->raw_response,
		'tracking_pixel' => '' . apply_filters('emma_tracking_pixel', $success_pixel),
	);
	echo json_encode($response_array);
	
	wp_die();
}

add_action( 'admin_notices', 'emma_admin_notices' );
function emma_admin_notices() {
	if ( isset($_GET['page']) && $_GET['page'] == 'emma_plugin_options' ) {
		echo '<div class="update-nag notice is-dismissible"><p>Want to serve you’re Emma subscribers the latest posts or customized content from your WordPress site?  Think of it like RSS to email, but better.  To enable this functionality, customization to your Emma and WordPress account is needed, but Ah So can help.</p><p><a class="button button-primary" href="http://ahsodev.com/advanced-emma-plugin/" title="Ah So Designs custom content build for Emma campaigns" target="_blank">Learn more!</a></p></div>';
	}
}

add_action( 'admin_enqueue_scripts', 'emma_pointer_load', 1000 );
function emma_pointer_load( $hook_suffix ) {
 
    // Don't run on WP < 3.3
    if ( get_bloginfo( 'version' ) < '3.3' )
        return;
 
    $screen = get_current_screen();
    $screen_id = $screen->id;
 
    // Get pointers for this screen
    $pointers = apply_filters( 'emma_admin_pointers', array() );
 
    if ( ! $pointers || ! is_array( $pointers ) )
        return;
 
    // Get dismissed pointers
    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
    $valid_pointers =array();
 
    // Check pointers and remove dismissed ones.
    foreach ( $pointers as $pointer_id => $pointer ) {
 
        // Sanity check
        if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
            continue;
 
        $pointer['pointer_id'] = $pointer_id;
 
        // Add the pointer to $valid_pointers array
        $valid_pointers['pointers'][] =  $pointer;
    }
 
    // No valid pointers? Stop here.
    if ( empty( $valid_pointers ) )
        return;
 
    // Add pointers style to queue.
    wp_enqueue_style( 'wp-pointer' );
 
    // Add pointers script to queue. Add custom script.
    wp_enqueue_script( 'emma-pointer', EMMA_EMARKETING_URL . '/assets/js/emma-pointer.js', array( 'wp-pointer' ) );
	
    // Add pointer options to script.
    wp_localize_script( 'emma-pointer', 'emmaPointer', $valid_pointers );
}

add_filter( 'emma_admin_pointers', 'emma_register_pointer' );
function emma_register_pointer( $p ) {
    $p['bsefw1'] = array(
        'target' => '#menu-settings',
        'options' => array(
			'content' => '<h3>RSS To Email, Only Better</h3><p>Want to serve you’re Emma subscribers the latest posts or customized content from your WordPress site?  Think of it like RSS to email, but better.  To enable this functionality, customization to your Emma and WordPress account is needed, but Ah So can help.</p><p><a href="' . get_admin_url() . 'options-general.php?page=emma_plugin_options&tab=emma_advanced_settings" title="Ah So Designs custom content build for Emma campaigns">Learn more!</a></p>',
            'position' => array( 'edge' => 'left', 'align' => 'middle' )
        )
    );
    return $p;
}

add_action('wp_ajax-dismiss-wp-pointer', 'emma_dismiss_pointer', 1);
function emma_dismiss_pointer() {
	$user_dismissed_pointers = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
	$user_dismissed_pointers .= ', ' . $_POST['pointer'];
	
	update_user_meta( get_current_user_id(), 'dismissed_wp_pointers', $user_dismissed_pointers );
}