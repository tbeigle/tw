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

// TO DO: Create a different plugin for this, one that can be reused for any site.
if (!class_exists( 'TinWingsClover' )) {
  class TinWingsClover {
    //var $client_id = 'XBV2A5BTAEPDM';
    var $client_id = 'NJASA1B5VA0MP';
    var $merchant_id;
    var $uid;
    var $mail = '';
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
        'merchant_id' => '',
        'uid' => '',
        'mail' => '',
      ));

      $this->merchant_id = $opt['merchant_id'];
      $this->uid = $opt['uid'];
      $this->mail = isset($opt['mail']) ? $opt['mail'] : '';

      return !empty($this->merchant_id) && !empty($this->uid);
    }

    /**
     * Prints a link for fetching an oauth code.
     */
    public function get_code() {
      echo '<a href="https://www.clover.com/oauth/authorize?client_id=' . $this->client_id . '&redirect_uri=https://clover.designated-developers.com/ddclover/setup">';
      echo 'Authorize the App';
      echo '</a>';
    }

    /**
     * @param array $postdata $_POST array
     * @return bool
     */
    private function validatePostData(array $postdata) {
      return TRUE;
    }
  }
}

/*if (is_admin()) {
  add_action('admin_menu', 'tinwings_menu');
  add_action('admin_init', 'tinwings_register_settings');
}*/

//add_action('init', 'tinwings_register_extra_pages');

/*function tinwings_register_extra_pages() {
  add_feed('twclover', 'tinwings_clover_auth');
}

function tinwings_register_settings() {
  register_setting('tinwings_clover', 'tw_clover');

  if (get_option('tw_clover') === FALSE) {
    update_option('tw_clover', array(
      'merchant_id' => '',
      'uid' => '',
      'mail' => '',
    ));
  }
}

function tinwings_clover_auth() {
  if (!empty($_GET['ddcm']) && !empty($_GET['ddcu'])) {
    $opt = get_option('tw_clover', array(
      'merchant_id' => 0,
      'uid' => 0,
      'mail' => '',
    ));

    update_option('tw_clover', array(
      'merchant_id' => $_GET['ddcm'],
      'uid' => $_GET['ddcu'],
      'mail' => !empty($opt['mail']) ? $opt['mail'] : '',
    ));

    wp_redirect('/wp-admin/options-general.php?page=tinwings-clover-options');
    exit();
  }
}*/

/*
URLs:
  listen (callback)
  authorize (form)

*/

/*function tinwings_menu() {
  add_options_page('Tinwings Clover', 'Tinwings Clover', 'manage_options', 'tinwings-clover-options', 'tinwings_clover_options');
}*/

/*function tinwings_clover_options() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  $twc = new TinWingsClover();

  echo '<div class="wrap">';

  if (!$twc->good_to_go()) {
    $twc->get_code();
  }
  else {
    echo '<h2>Designated Developers Clover Settings</h2>';
    echo '<form method="post" action="options.php">';
    settings_fields('tinwings_clover');
    $options = get_option('tw_clover');
    echo '<div><label>Merchant ID</label>: ' . $twc->merchant_id . '</div>';
    echo '<div><label>DD Clover User ID</label>: ' . $twc->uid . '</div>';
    echo '<div><label>DD Clover User Email</label><input type="text" name="tw_clover[mail]" value="' . $twc->mail . '"></div>';
    echo '<input type="hidden" name="tw_clover[merchant_id]" value="' . $twc->merchant_id . '">';
    echo '<input type="hidden" name="tw_clover[uid]" value="' . $twc->uid . '">';

    submit_button();
    echo '</form>';
  }

  echo '</div>';
}*/

/*
  TO DO:
    Figure out where this should live so you can get the damn thing approved and usable by Tin Wings (clover.tinwings.designated-developers.com?)
    Add calls to get merchant orders
    Add calls to get inventory
    Add calls to get the stock of all inventory items

*/
