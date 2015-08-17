<?php

/**
 * Plugin Name: Emma Emarketing Plugin
 * Plugin URI: http://ahsodeisgns.com/wordpress-plugins/emma-emarketing
 * Description: The Emma Emarketing plugin allows you to quickly and easily add a signup form for your Emma list as a widget or a shortcode.
 * Version: 1.0.5
 * Author: Ah So
 * Author URI: http://ahsodesigns.com
 * Contributors: ahsodesigns
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
include_once('admin/class-form-setup.php');
include_once('admin/class-form-custom.php');

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
            $this->error_txt = 'The Emma Emarketing plugin requires at least PHP 5.2.6.';
        }
        if( version_compare( get_bloginfo( 'version' ), '3.1', '<' ) ) {
            $this->error_txt = 'The Emma Emarketing plugin requires at least WordPress version 3.1.';

        }

        // probably should do some checking before sending this off...
        add_action( 'admin_notices', array( &$this, 'version_require' ) );

        // load default options into database on activation
        // add_option( $option, $value, $depreciated, $autoload );
        add_option( Account_Information::$key, Account_Information::get_settings_defaults(), '', 'yes' );
        add_option( Form_Setup::$key, Form_Setup::get_settings_defaults(), '', 'yes' );
        add_option( Form_Custom::$key, Form_Custom::get_settings_defaults(), '', 'yes' );

    }

    function emma_emarketing_deactivation() {

        // buh-bye!
    }

    function version_require() {
        if( current_user_can( 'manage_options' ) )
            echo '<div class="error"><p>' . $this->error_txt . '</p></div>';
    }

} // end class Start

