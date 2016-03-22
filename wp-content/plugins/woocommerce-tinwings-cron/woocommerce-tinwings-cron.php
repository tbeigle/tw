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

namespace TinWings\WooCommerce;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

if (!class_exists('TinWings\Woocommerce\Cron')) {
  /**
   * Register the plugin.
   */
  class Cron {
    /**
     * Initialize
     */
    public static function init() {
      $class = __CLASS__;
      new $class;
    }

    /**
     * Construct
     */
    public function __construct() {
      self::interval_define();
      self::interval_register();
      self::schedule_register();
      self::unschedule_register();
      self::execute();
    }

    /**
     * Define custom interval
     */
    public static function interval_define() {
      $schedules['weekly'] = array(
    		'interval'	=> WEEK_IN_SECONDS,
    		'display'	=> 'Once Every Week'
    	);

    	return (array)$schedules;
    }

    /**
     * Register custom interval to execute tasks weekly
     */
    public static function interval_register() {
      add_filter('cron_schedules', [__CLASS__, 'interval_define'], 90, 1);
    }

    /**
     * Schedule our cron job
     */
    public static function schedule() {
      wp_schedule_event(time(), 'weekly', [__CLASS__, 'execute']);
    }

    /**
     * Register schedule event
     */
    public static function schedule_register() {
      register_activation_hook(__FILE__, [__CLASS__, 'schedule']);
    }

    /**
     * Execute cron
     */
    public static function execute() {
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
            $e->getMessage(); // Error message.
          }
        }
      });
    }

    /**
     * Unschedule cron task (will run when plugin gets disabled)
     */
    public static function unschedule() {
      wp_clear_scheduled_hook([__CLASS__, 'schedule']);
    }

    public static function unschedule_register() {
      register_deactivation_hook(__FILE__, [__CLASS__, 'unschedule']);
    }
  }
}

add_action('plugins_loaded', [__NAMESPACE__ . '\\Cron', 'init']);