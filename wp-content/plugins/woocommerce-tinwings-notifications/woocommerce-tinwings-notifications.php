<?php
/*
 * Plugin Name: Tinwings Woocommerce Notifications
 * Depends: WooCommerce
 * Plugin URI:
 * Description: This is a custom plugin for Tinwings to send email notifications to the customers
 * Author: Plan Left
 * Version: 1.0
 * Author URI: https://planleft.com
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Add custom interval to execute tasks weekly
 */
function tw_custom_interval() {
  $schedules['weekly'] = array(
		'interval'	=> WEEK_IN_SECONDS,
		'display'	=> 'Once Every Week'
	);

	return (array)$schedules;
}
// apply custom interval
add_filter('cron_schedules', 'tw_custom_interval', 90, 1);

/**
 * Schedule our cron job
 */
function tw_schedule_cron() {
  wp_schedule_event(time(), 'weekly', 'tw_cron_notifications');
}
// register activation hook
register_activation_hook(__FILE__, 'tw_schedule_cron');

/**
 * Notify customers
 */
add_action('tw_cron_notifications', function() {
  // we only want the orders from the last 7 days
  $query = new WP_Query([
    'post_type' => 'shop_order',
    'post_status' => 'publish',
    'posts_per_page' => '-1',
    'year' => date("Y"),
    'monthnum' => date("m"),
    'w' => date("W") - 1,
  ]);

  // this will filter out the orders by local_pickup or local_delivery
  foreach ($query->posts as $customer_order) {
    $order = new WC_Order($customer_order->ID);

    if($order->has_shipping_method('local_pickup')) {
      $message = '';
      $message .= '<p>Thank you for placing an order for Tinwings ToGo.</p>
      <p>We look forward to seeing you tomorrow when you pick up your yummy food!</p>
      <p>Pick up at Tinwings, 816 51st Avenue North anytime between 2 & 6 p.m. <br>
      If you need to reach us please call 615.454.5250.</p>
      <p>Thanks, Lee Ann & Ursula</p>';

      add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
      wp_mail($order->billing_email, get_bloginfo('name') . ' - Local Pickup: Order #' . $order->get_order_number(), $message);
    }

    if($order->has_shipping_method('local_delivery')) {
      $message = '';
      $message .= 'Thank you for placing an order for Tinwings ToGo.
      <p>Our friendly delivery person will arrive tomorrow afternoon with your yummy food.</p>
      <p>Enjoy! Lee Ann & Ursula</p>';

      add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
      wp_mail($order->billing_email, get_bloginfo('name') . ' - Local Delivery: Order #' . $order->get_order_number(), $message);
    }
  }
});

// this will run when the plugin gets disabled
function tw_unschedule_cron() {
	wp_clear_scheduled_hook('tw_schedule_cron');
}
register_deactivation_hook(__FILE__, 'tw_unschedule_cron');