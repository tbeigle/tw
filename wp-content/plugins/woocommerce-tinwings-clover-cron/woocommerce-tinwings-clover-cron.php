<?php
//error_reporting(E_ALL);
/*
 * Plugin Name: Tinwings Woocommerce Clover Cron
 * Depends: Clover for WooCommerce
 * Plugin URI:
 * Description: This is a custom plugin for Tinwings to sync products with Clover using "Clover for WooCommerce"
 * Author: Plan Left
 * Version: 1.0
 * Author URI: https://planleft.com
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Schedule our cron job
 */
function tw_wooclover_schedule_cron() {
  wp_schedule_event(time(), 'twicedaily', 'tw_wooclover_cron_sync');
}
// register activation hook
register_activation_hook(__FILE__, 'tw_wooclover_schedule_cron');

/**
 * Notify customers
 */
add_action('tw_wooclover_cron_sync', function() {
  $clover = new \Wooclover\Admin\Controllers();

  $clover->importFullInventory();
  //do_action('wp_ajax_wooclover_importInventory');
});

// this will run when the plugin gets disabled
function tw_wooclover_unschedule_cron() {
	wp_clear_scheduled_hook('tw_wooclover_schedule_cron');
}
register_deactivation_hook(__FILE__, 'tw_wooclover_unschedule_cron');