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
    //private $client_id = 'XBV2A5BTAEPDM';
    //private $client_secret = '7f663c3f-d438-ff62-a636-660199c15a24';
    private $client_id = 'NJASA1B5VA0MP';
    private $client_secret = '3e32545e-6a10-de95-6219-af52539ab3fc';
    var $access_token;
    var $merchant_id;
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
     * Checks if access is already set.
     */
    public function good_to_go() {
      $opt = get_option('tw_clover', array(
        'access_token' => '',
        'merchant_id' => '',
      ));
      
      $this->access_token = $opt['access_token'];
      $this->merchant_id = $opt['merchant_id'];
      
      return !empty($this->access_token) && !empty($this->merchant_id) && !empty($this->client_id) && !empty($this->client_secret);
    }
    
    /**
     * Prints a link for fetching an oauth code.
     */
    public function get_code() {
      echo '<a href="https://www.clover.com/oauth/authorize?client_id=' . $this->client_id . '&redirect_uri=' . get_site_url() . '/twclover/auth">';
      echo 'Authorize the App';
      echo '</a>';
    }
    
    /**
     * Retrieves an oauth token.
     */
    public function authorize($code) {
      $url = 'https://www.clover.com/oauth/token?client_id=' . $this->client_id . '&client_secret=' . $this->client_secret . '&code=' . $code;
      
      $response = wp_remote_get($url);
      
      if (!is_array($response)) {
        echo '<pre>' . print_r($response) . '</pre>';
      }
      elseif (!empty($response['body'])) {
        $decoded = json_decode($response['body']);
        
        $opt = get_option('tw_clover', array(
          'access_token' => '',
          'merchant_id' => '',
        ));
        
        if (!empty($decoded->access_token)) {
          $opt['access_token'] = $decoded->access_token;
        }
        
        update_option('tw_clover', $opt);
      }
      
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

if (is_admin()) {
  add_action('admin_menu', 'tinwings_menu');
  add_action('admin_init', 'tinwings_register_settings');
}

add_action('plugins_loaded', array('TinWingsHelper', 'init'), 20);
add_action('init', 'tinwings_register_extra_pages');


function tinwings_register_extra_pages() {
  add_feed('twclover', 'tinwings_clover_oauth_auth');
  add_feed('twclover/auth', 'tinwings_clover_oauth_receive');
}

function tinwings_register_settings() {
  register_setting('tinwings_clover', 'tw_clover');
  
  if (get_option('tw_clover') === FALSE) {
    update_option('tw_clover', array(
      'access_token' => '',
      'merchant_id' => '',
    ));
  }
}

function tinwings_clover_oauth_auth() {
  print '<p>It works!</p>';
}

function tinwings_clover_oauth_receive() {
  if (!empty($_GET['code'])) {
    $opt = get_option('tw_clover', array(
      'access_token' => '',
      'merchant_id' => '',
    ));
    
    if (!empty($_GET['merchant_id'])) {
      $opt['merchant_id'] = $_GET['merchant_id'];
    }
    
    update_option('tw_clover', $opt);
    
    $twc = new TinWingsClover();
    $twc->authorize($_GET['code']);
    
    wp_redirect('/wp-admin/options-general.php?page=tinwings-clover-options');
    exit();
  }
}

/*
URLs:
  listen (callback)
  authorize (form)
  
*/

function tinwings_menu() {
  add_options_page('Tinwings Clover', 'Tinwings Clover', 'manage_options', 'tinwings-clover-options', 'tinwings_clover_options');
}

function tinwings_clover_options() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  
  $twc = new TinWingsClover();
  
  echo '<div class="wrap">';
  
  if (!$twc->good_to_go()) {
    $twc->get_code();
  }
  else {
    echo '<h2>Clover Settings</h2>';
    echo '<form method="post" action="options.php">';
    echo '<div><label>Merchant ID</label><input type="text" name="tw_clover[merchant_id]" value="' . $twc->merchant_id . '"></div>';
    echo '<div><label>Access Token</label><input type="text" name="tw_clover[access_token]" value="' . $twc->access_token . '"></div>';
    submit_button();
    echo '</form>';
  }
  
  echo '</div>';
}

/*
  TO DO:
    Add calls to get merchant orders
    Add calls to get inventory
    Add calls to get the stock of all inventory items
    
*/
