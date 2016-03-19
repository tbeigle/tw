<?php
/*
 * Plugin Name: Tinwings Woocommerce Reports
 * Depends: WooCommerce
 * Plugin URI: https://planleft.com
 * Description: This is a custom plugin for Tinwings to create custom reports
 * Author: Plan Left
 * Version: 1.0
 * Author URI: https://planleft.com
 */

 if (!class_exists( 'TinWingsWoocommerceReports')) {
  /**
   * Register the plugin.
   */
  class TinWingsWoocommerceReports {
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

    }
  }
}

add_action('plugins_loaded', array('TinWingsWoocommerceReports', 'init'), 20);

add_action('admin_enqueue_scripts', 'tinwings_reports_styles');
function tinwings_reports_styles() {
  wp_enqueue_style('tinwings-reports-styles', plugins_url('woocommerce-tinwings-reports.css', __FILE__));
}

if (is_admin()) {
  add_action('admin_menu', 'tinwings_reports_menu');
}

function tinwings_reports_menu() {
  add_submenu_page( 'woocommerce', 'Tinwings Reports', 'Tinwings Reports', 'manage_options', 'tinwings-reports', 'tinwings_reports');
}

function tinwings_reports() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

  if( isset( $_GET[ 'tab' ] ) ) {
    $active_tab = $_GET[ 'tab' ];
  }
	?>
	<script>
  function print_report(tab) {
    window.document.body.innerHTML = document.getElementById(tab).innerHTML;
    window.focus();
    window.print();
    location.reload();
  }
  </script>
	<div class="wrap">
  <?php if ($active_tab == ''): ?>
  <h1>Tinwings Reports</h1>
  <?php else: ?>
  <h1>Tinwings Reports <a href="javascript:print_report('<?php echo $active_tab; ?>_tab')" class="page-title-action">Print Page</a></h1>
  <?php endif; ?>
	<h2 class="nav-tab-wrapper">
  	<a class="nav-tab <?php echo $active_tab == '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports">About</a>
    <a class="nav-tab <?php echo $active_tab == 'delivery_pickup' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports&tab=delivery_pickup">Delivery vs. Pickup</a>
    <a class="nav-tab <?php echo $active_tab == 'year' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports&tab=year">This Year</a>
    <a class="nav-tab <?php echo $active_tab == 'last_year' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports&tab=last_year">Last Year</a>
    <a class="nav-tab <?php echo $active_tab == 'month' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports&tab=month">This Month</a>
    <a class="nav-tab <?php echo $active_tab == 'last_month' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports&tab=last_month">Last Month</a>
    <a class="nav-tab <?php echo $active_tab == 'week' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports&tab=week">This Week</a>
    <a class="nav-tab <?php echo $active_tab == 'last_week' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url() ?>admin.php?page=tinwings-reports&tab=last_week">Last Week</a>
  </h2>
  <?php if($active_tab == ''): ?>
  <p>This section is used to display some custom basic reports. To print a report, simply click in the Print Page button right next the page title.</p>
  <p>For now we have the following reports:</p>
  <ol>
    <li>Delivery vs. Pickup</li>
    <li>This Year</li>
    <li>Last Year</li>
    <li>This Month</li>
    <li>Last Month</li>
    <li>This Week</li>
    <li>Last Week</li>
  </ol>

	<?php endif; ?>
	<?php
  	if($active_tab == 'delivery_pickup') {
      $query = new WP_Query([
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'posts_per_page' => '-1'
      ]);

      echo '<div id="delivery_pickup_tab">';
      echo '<h3>Local Pickup</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        if($order->has_shipping_method('local_pickup')) {
          echo '<tr>';
          echo '<td>';
          echo '<ul>';
            foreach($order->get_items() as $item) {
              echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
            }
          echo '</ul>';
          echo '</td>';
          $date = new \DateTime($order->order_date);
          echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
          echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
          echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
          echo '<td>' . $order->get_subtotal_to_display() . '</td>';
          echo '<td>' . $order->get_shipping_method() . '</td>';
          echo '<td>' . $order->payment_method_title . '</td>';
          echo '</tr>';
        }
      }

      echo '</tbody>';
      echo '</table>';

      echo '<h3>Local Delivery</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        if($order->has_shipping_method('local_delivery')) {
          echo '<tr>';
          echo '<td>';
          echo '<ul>';
            foreach($order->get_items() as $item) {
              echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
            }
          echo '</ul>';
          echo '</td>';
          $date = new \DateTime($order->order_date);
          echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
          echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
          echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
          echo '<td>' . $order->get_subtotal_to_display() . '</td>';
          echo '<td>' . $order->get_shipping_method() . '</td>';
          echo '<td>' . $order->payment_method_title . '</td>';
          echo '</tr>';
        }
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
	  }

	  if($active_tab == 'year') {
  	  $query = new WP_Query([
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'year' => date("Y"),
      ]);

      echo '<div id="year_tab">';
      echo '<h3>Orders this Year</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        echo '<tr>';
        echo '<td>';
        echo '<ul>';
          foreach($order->get_items() as $item) {
            echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
          }
        echo '</ul>';
        echo '</td>';
        $date = new \DateTime($order->order_date);
        echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
        echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
        echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
        echo '<td>' . $order->get_subtotal_to_display() . '</td>';
        echo '<td>' . $order->get_shipping_method() . '</td>';
        echo '<td>' . $order->payment_method_title . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
    }

    if($active_tab == 'last_year') {
  	  $query = new WP_Query([
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'year' => date("Y") - 1,
      ]);

      echo '<div id="last_year_tab">';
      echo '<h3>Orders Last Year</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        echo '<tr>';
        echo '<td>';
        echo '<ul>';
          foreach($order->get_items() as $item) {
            echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
          }
        echo '</ul>';
        echo '</td>';
        $date = new \DateTime($order->order_date);
        echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
        echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
        echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
        echo '<td>' . $order->get_subtotal_to_display() . '</td>';
        echo '<td>' . $order->get_shipping_method() . '</td>';
        echo '<td>' . $order->payment_method_title . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
    }

	  if($active_tab == 'month') {
  	  $query = new WP_Query([
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'year' => date("Y"),
        'monthnum' => date("m"),
      ]);

      echo '<div id="month_tab">';
      echo '<h3>Orders this Month</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        echo '<tr>';
        echo '<td>';
        echo '<ul>';
          foreach($order->get_items() as $item) {
            echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
          }
        echo '</ul>';
        echo '</td>';
        $date = new \DateTime($order->order_date);
        echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
        echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
        echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
        echo '<td>' . $order->get_subtotal_to_display() . '</td>';
        echo '<td>' . $order->get_shipping_method() . '</td>';
        echo '<td>' . $order->payment_method_title . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
    }

    if($active_tab == 'last_month') {
  	  $query = new WP_Query([
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'year' => date("Y"),
        'monthnum' => date("m") - 1,
      ]);

      echo '<div id="last_month_tab">';
      echo '<h3>Orders last Month</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        echo '<tr>';
        echo '<td>';
        echo '<ul>';
          foreach($order->get_items() as $item) {
            echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
          }
        echo '</ul>';
        echo '</td>';
        $date = new \DateTime($order->order_date);
        echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
        echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
        echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
        echo '<td>' . $order->get_subtotal_to_display() . '</td>';
        echo '<td>' . $order->get_shipping_method() . '</td>';
        echo '<td>' . $order->payment_method_title . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
    }

    if($active_tab == 'week') {
  	  $query = new WP_Query([
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'year' => date("Y"),
        'monthnum' => date("m"),
        'w' => date("W"),
      ]);

      echo '<div id="week_tab">';
      echo '<h3>Orders this Week</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        echo '<tr>';
        echo '<td>';
        echo '<ul>';
          foreach($order->get_items() as $item) {
            echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
          }
        echo '</ul>';
        echo '</td>';
        $date = new \DateTime($order->order_date);
        echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
        echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
        echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
        echo '<td>' . $order->get_subtotal_to_display() . '</td>';
        echo '<td>' . $order->get_shipping_method() . '</td>';
        echo '<td>' . $order->payment_method_title . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
    }

    if($active_tab == 'last_week') {
  	  $query = new WP_Query([
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'posts_per_page' => '-1',
        'year' => date("Y"),
        'monthnum' => date("m"),
        'w' => date("W") - 1,
      ]);

      echo '<div id="last_week_tab">';
      echo '<h3>Orders Last Week</h3>';
      echo '<table class="tinwings-reports-table">';
      echo '<thead>';
      echo '<td>Product(s)</td>
            <td>Order Date</td>
            <td>Email</td>
            <td>Purchased</td>
            <td>Subtotal</td>
            <td>Delivery Method</td>
            <td>Payment Method</td>';
      echo '</thead>';
      echo '<tbody>';

      foreach ($query->posts as $customer_order) {
        $order = new WC_Order($customer_order->ID);

        echo '<tr>';
        echo '<td>';
        echo '<ul>';
          foreach($order->get_items() as $item) {
            echo '<li>' . $item['qty'] . ' &times; ' . $item['name'] . ' ($' . $order->get_item_total($item) . ')</li>';
          }
        echo '</ul>';
        echo '</td>';
        $date = new \DateTime($order->order_date);
        echo '<td><a href="post.php?post=' . $order->get_order_number() . '&action=edit">' . $date->format('m/d/Y @ H:i:s') . '</a></td>';
        echo '<td><a href="user-edit.php?user_id=' . $order->get_user_id() . '">' . $order->billing_email . '</a></td>';
        echo '<td>' . $order->get_item_count() . ' Item(s)</td>';
        echo '<td>' . $order->get_subtotal_to_display() . '</td>';
        echo '<td>' . $order->get_shipping_method() . '</td>';
        echo '<td>' . $order->payment_method_title . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
    }
	?>
	</div>
<?php }