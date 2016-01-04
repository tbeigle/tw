<?php

/*
  Plugin Name: Clover for WooCommerce
  Plugin URI: http://www.sitemavens.com/portfolio/woocommerce-and-clover-connector/
  Description: Our WooCommerce Connector allows store owners to easily manage their WordPress eCommerce site from the convenience of Clover. Products and inventory can be easily sync’d, and orders processed through a single merchant account. Bonus: no more reconciling your online sales with your books.
  Version: 0.1
  Author: SiteMavens.com
  Author URI: http://www.sitemavens.com/
  License: GPL
  Copyright: SiteMavens.com
 */

namespace WooClover;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

require_once plugin_dir_path( __FILE__ ) . 'validation.php';

// Check if Woocomerce is activate, if not, just return.
if ( Validation::isWooMissing() ) {
	return;
}

//These are the only require_once needed. Then you should use the Loader class
require_once plugin_dir_path( __FILE__ ) . '/core/loader.php';
Core\Loader::load( plugin_dir_path( __FILE__ ), array( 'core/domain/domain-object', 'core/settings/option', 'core/settings/registry', 'core/settings/wordpress-registry', 'core/settings/wc-registry', 'core/utils', 'core/view-handler' ) );

$registry = Core\Settings\WcRegistry::instance();
$registry->setPluginDir( plugin_dir_path( __FILE__ ) );
$registry->setPluginUrl( defined( 'DEV_ENV' ) && DEV_ENV ? WP_PLUGIN_URL . "/woocommerce-clover/" : plugin_dir_url( __FILE__ )  );
$registry->setPluginVersion( "0.1" );
$registry->setPluginName( 'Clover for WooCommerce' );
$registry->setPluginShortName( 'wooclo' );
$registry->init();

/**
 * We need to register the namespace of the plugin. It will be used for autoload function to add the required files. 
 */
Core\Loader::registerType( "Wooclover", $registry->getPluginDir() );


$main = new \Wooclover\Admin\Main();
//add_action( 'wp_json_server_before_serve', array( $main, 'registerRouters' ) );

if ( is_admin() ) {
	add_action( 'init', array( $main, 'registerApi' ) );
}

$main->init();
add_action( 'init', array( '\WooClover\Core\ViewHandler', 'init' ) );
add_filter( 'query_vars', array( '\WooClover\Core\ViewHandler', 'queryVars' ) );
add_action( 'parse_request', array( '\WooClover\Core\ViewHandler', 'parseRequest' ) );



/**
 * 
 * Instantiate the installer 
 *
 * * */
$installer = new \Wooclover\Core\Installer();
register_activation_hook( __FILE__, array( $installer, 'install' ) );
register_deactivation_hook( __FILE__, array( $installer, 'uninstall' ) );

//Connectors
add_filter( 'wooclover\connectors\customer', function( $nothing ) {
	return "\Wooclover\WooApi\CustomerConnector";
} );
add_filter( 'wooclover\connectors\inventory', function( $nothing ) {
	return "\Wooclover\WooApi\InventoryConnector";
} );
add_filter( 'wooclover\connectors\order', function( $nothing ) {
	return "\Wooclover\WooApi\OrderConnector";
} );
