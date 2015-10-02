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

if (!class_exists( 'TinWingsClover')) {
  class TinWingsClover {
    // Vars
    private $client_id = 'XBV2A5BTAEPDM';
    private $client_secret = '7f663c3f-d438-ff62-a636-660199c15a24';
    private $code;
    private $access_token;
    private $merchant_id;
    private $valid = FALSE;
    
    /**
     * Init
     */
    public static function init() {
      $tinwingsclover = new self();
    }
    
    /**
     * Construct
     */
    public function __construct($postdata = array()) {
      if (!empty($postdata) && is_array($postdata)) {
        $this->valid = $this->validatePostData($postdata);
      }
    }
    
    /**
     * Retrieves an oauth token.
     */
    public function authorize($code) {
      $url = 'https://www.clover.com/oauth/token?client_id=' . $this->client_id . '&client_secret=' . $this->client_secret . '&code=' . $code;
      // TO DO: Send GET request to the $url and decode and save the JSON response
      // {"access_token": ”[your access token]”}
      
      // To make a call using the token (from command line, example for orders request)
      // curl -s https://api.clover.com/v3/merchants/[Merchant ID]/orders --header "Authorization:Bearer [API Token]" | python -mjson.tool
    }
    
    /**
     * Runs on onchangeapi action
     */
    public function __invoke() {
      if ($this->valid) {
        // Do yo shiii
      }
    }
    
    /**
     * @param array $postdata $_POST array
     * @return bool
     */
    private function validatePostData(array $postdata) {
      return TRUE;
    }
    
    /**
     * Registers the path for the Clover webhook listener.
     */
    public function addListener() {
      /*global $wp;
      $wp->add_query_var('args');
      add_rewrite_rule('twclover\/(.*)','index.php?pagename=clovertestapp&args=$matches[1]','top');*/
    }
  }
}

add_action('plugins_loaded', array('TinWingsHelper', 'init'), 20);
//add_action('init', array('TinWingsClover', 'addListener'));

add_action( 'init', 'tinwings_register_extra_pages');

function tinwings_register_extra_pages() {
  add_feed('twclover', 'tinwings_clover_oauth_auth');
  add_feed('twclover/auth', 'tinwings_clover_oauth_receive');
}

function tinwings_clover_oauth_auth() {
  print '<p>It works!</p>';
}

function tinwings_clover_oauth_receive() {
  $twc = new TinWingsClover();
  
  //print '<p>It works part 2!</p>';
  $params['code'] = get_query_var('merchant_id', '');
  $params['merchant_id'] = get_query_var('merchant_id', '');
  $params['employee_id'] = get_query_var('employee_id', '');
  $params['client_id'] = get_query_var('client_id', '');
  
  print '<pre>' . print_r($params, TRUE) . '</pre>';
  print '<pre>' . print_r($_GET, TRUE) . '</pre>';
  
  if (!empty($_GET['code'])) {
    $twc->authorize($_GET['code']);
  }
}

/*
URLs:
  listen (callback)
  authorize (form)
  
*/
