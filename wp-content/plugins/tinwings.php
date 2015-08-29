<?php
/*
 * Plugin Name: Tin Wings Helper
 * Depends: WooCommerce
 * Plugin URI: 
 * Description: This is a custom plugin for Tin Wings to facilitate special functionality, such as auto-disabling products on Sunday mornings and sending the reminder emails on Tuesdays.
 * Author: Designated Developers
 * Version: 1.0
 * Author URI: http://designated-developers.com
 */
 
if (!class_exists( 'TinWingsHelper')) {
  /**
   * Register the plugin.
   */
  class TinWingsHelper {
    /**
     * Init
     */
    public static function init() {
      $tinwingshelper = new self();
    }
    
    /**
     * Construct
     */
    public function __construct() {
      // Auto emptying product stock
      if ($this->_should_disable_products()) $this->_disable_products();
      // Auto notification pick-up emails
      
    }
    
    /**
     * Disables products.
     */
    private function _disable_products() {
      update_metadata('post', NULL, '_stock', 0);
      // Set the transient so the process will not be attempted again until the next Sunday.
      set_transient('tinwings_sunday_update_complete', 1, DAY_IN_SECONDS);
    }
    
    /**
     * Notify for pickup.
     */
    private function _notify() {
      // Need to place some test orders to figure out what meta key to update for
      // Should only update orders not already marked as the to-update status, more specifically, only those orders which are active and ready to process.
      
      // Set the transient so the process will not be attempted again until the next Tuesday.
      set_transient('tinwings_tuesday_notify_complete', 1, DAY_IN_SECONDS);
    }
    
    /**
     * Checks to see if products should be deactivated.
     */
    private function _should_disable_products() {
      // Check transient to see if the operation has already been performed.
      if (get_transient('tinwings_sunday_update_complete')) return FALSE;
      // Build the day and hour variables
      list($day, $hour) = $this->_day_hour();
      // Do not proceed unless it's Sunday and at least 10 a.m.
      if ($day > 0 || (int) $hour < 10) return FALSE;
      
      return TRUE;
    }
    
    /**
     * Checks whether the pick-up notification emails should be sent.
     */
    private function _should_notify() {
      // Check transient to see if the operation has already been performed.
      if (get_transient('tinwings_tuesday_notify_complete')) return FALSE;// Build the day and hour variables
      list($day, $hour) = $this->_day_hour();
      // Do not proceed unless it's Tuesday and at least 10 a.m.
      if ($day != 2 || (int) $hour < 10) return FALSE;
      
      return TRUE;
    }
    
    /**
     * Helper for checking the day and hour.
     */
    private function _day_hour() {
      list($day, $hour) = explode('|', date('w|G'));
      return array('day' => $day, 'hour' => $hour);
    }
  }
}

add_action('plugins_loaded', array('TinWingsHelper', 'init'), 20);
