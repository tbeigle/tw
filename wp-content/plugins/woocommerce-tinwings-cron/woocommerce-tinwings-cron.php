<?php
//error_reporting(E_ALL);
/*
 * Plugin Name: Tinwings Woocommerce Cron
 * Depends: WooCommerce
 * Plugin URI: https://planleft.com
 * Description: This is a custom plugin for Tinwings to do custom cron tasks for the site
 * Author: Plan Left
 * Version: 1.0
 * Author URI: https://planleft.com
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

/**
 * Add custom interval to execute tasks weekly
 */
function tw_cron_interval() {
  $schedules['weekly'] = array(
		'interval'	=> WEEK_IN_SECONDS,
		'display'	=> 'Once Every Week'
	);

	return (array)$schedules;
}
// apply custom interval
add_filter('cron_schedules', 'tw_cron_interval', 90, 1);

/**
 * Schedule our cron job
 */
function tw_schedule_cron() {
  wp_schedule_event(time(), 'weekly', 'tw_cron_execute');
}
// register activation hook
register_activation_hook(__FILE__, 'tw_schedule_cron');

/**
 * Execute cron
 */
add_action('tw_cron_execute', function() {
  $siteurl = get_option('siteurl');

  $woocommerce = new Client(
    $siteurl,
    'ck_008188fe039a419204fc7b606f6521d7ffa954a7',
    'cs_9ba7573029fbf4eae730ea45976e327afe82d910'
  );

  $query = new WP_Query([
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => '-1',
  ]);

  // puts the products out of stock
  $data = [
    'product' => [
      'in_stock' => false
    ]
  ];

  foreach ($query->posts as $product) {
    try {
      // sends out the request
      $woocommerce->put('products/' . $product->ID, $data);
    } catch (HttpClientException $e) {
      $e->getMessage() // Error message.
    }
  }
});

// this will run when the plugin gets disabled
function tw_unschedule_cron() {
	wp_clear_scheduled_hook('tw_schedule_cron');
}
register_deactivation_hook(__FILE__, 'tw_unschedule_cron');