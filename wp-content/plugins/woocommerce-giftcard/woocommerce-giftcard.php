<?php
/**
 * Plugin Name: WooCommerce Gift Card
* Plugin URI: http://store.magenest.com/woocommerce-plugins/woocommerce-giftcard.html
* Description:Add ability to create/sell/redeem giftcard.
* Author: Magenest
* Author URI:http://magenest.com
* Version: 2.9
* Text Domain: woocommerce-gift-card
* Domain Path: /languages/
*
* Copyright: (c) 2011-2014 Hungnam. (info@magenest.com)
*
*
* @package   woocommerce-giftcard
* @author    Hungnam
* @category  Gift card
* @copyright Copyright (c) 2014, Magenest, Inc.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (! defined ('GIFTCARD_TEXT_DOMAIN'))
	define ( 'GIFTCARD_TEXT_DOMAIN', 'GIFTCARD' );

// Plugin Folder Path
if (! defined ('GIFTCARD_PATH'))
	define ('GIFTCARD_PATH', plugin_dir_path ( __FILE__ ) );

// Plugin Folder URL
if (! defined ('GIFTCARD_URL'))
	define ('GIFTCARD_URL', plugins_url ( 'woocommerce-giftcard', 'woocommerce-giftcard.php' ) );

// Plugin Root File
if (! defined ('GIFTCARD_FILE'))
	define ('GIFTCARD_FILE', plugin_basename ( __FILE__ ) );

class Magenest_Giftcard_Main {
	private static $giftcard_instance;

	/** plugin version number */
	const VERSION = '2.9';

	/** plugin text domain */
	const TEXT_DOMAIN = 'giftcard';

	public function __construct() {
		global $wpdb;

		register_activation_hook ( GIFTCARD_FILE, array ($this,'install' ) );
		add_action ( 'init', array ($this,'create_post_type') );
		//add_action ( 'woocommerce_loaded', array ($this,'test_send_giftcard') );
		//
		add_action('wp_enqueue_scripts', array($this,'load_custom_scripts'));
		//add_action('wp_print_scripts', array($this,'add_media_script'));
		$this->include_for_frontend();
		
		add_action('init',array($this,'register_session'));
        add_action('init', array($this, 'load_text_domain') );

        add_filter('page_row_actions',array($this,'add_sendmail_link') , 10, 2);

        if (is_admin ()) {
			add_action ( 'admin_enqueue_scripts', array ($this,'load_admin_scripts' ), 99 );
			//require_once plugin_dir_path ( __FILE__ ). 'admin/magenest-giftcard-setting.php';

			add_action ( 'admin_menu', array ( $this, 'admin_menu' ), 5 );
		}

	}

    public function add_sendmail_link($actions,$object) {
        if ($object->post_type == 'shop_giftcard') {
            $giftcardId = $object->ID;//edit.php?post_type=shop_giftcard&page=giftcard_send_mail
        $actions['send_mail'] = "<a class='send-mail' href='" . admin_url( "edit.php?post_type=shop_giftcard&page=giftcard_send_mail&action=send_mail&id={$giftcardId}") . "'>" . __( 'Send Mail', 'GIFTCARD' ) . "</a>";
        }
        return $actions;
    }
	public function load_text_domain() {
		load_plugin_textdomain( 'GIFTCARD', false, 'woocommerce-giftcard/languages/' );
	}
	
	public function load_admin_scripts() {
		global $woocommerce;
			
		if (is_object($woocommerce))
			wp_enqueue_style ( 'woocommerce_admin_styles', $woocommerce->plugin_url () . '/assets/css/admin.css' );
		//wp_enqueue_style('giftregistryadmin', GIFTCARD_URL. '/assets/magenestgiftregistry.css');
	}
	public function load_custom_scripts($hook) {

		wp_enqueue_style('magenestgiftcard' , GIFTCARD_URL .'/assets/giftcard.css');
		//wp_enqueue_script('magenestgiftregistryjs' , GIFTCARD_URL .'/assets/magenestgiftregistry.js');

	}
	public function include_for_frontend() {
		include_once GIFTCARD_PATH .'model/giftcard.php';
		
      	include_once GIFTCARD_PATH .'model/observer/product.php';
      	include_once GIFTCARD_PATH .'model/observer/buy-giftcard.php';
      	include_once GIFTCARD_PATH .'model/observer/apply-giftcard.php';
      	include_once GIFTCARD_PATH .'model/giftcard-applied-form-handler.php';
	}
	public function register_session(){
		if( !session_id() )
			session_start();
	}
	public function create_post_type() {
		$show_in_menu = current_user_can ( 'manage_woocommerce' ) ? 'woocommerce' : true;
		
		register_post_type ( 'shop_giftcard', array (
		'labels' => array (
		'name' => __ ( 'Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'singular_name' => __ ( 'Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'menu_name' => _x ( 'Giftcard', 'Admin menu name', 'GIFTCARD_TEXT_DOMAIN' ),
		'add_new' => __ ( 'Add Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'add_new_item' => __ ( 'Add Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'edit' => __ ( 'Edit', 'GIFTCARD_TEXT_DOMAIN' ),
		'edit_item' => __ ( 'Edit Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'new_item' => __ ( 'New Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'view' => __ ( 'View Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'view_item' => __ ( 'View Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'search_items' => __ ( 'Search Giftcard', 'GIFTCARD_TEXT_DOMAIN' ),
		'not_found' => __ ( 'No Giftcard found', 'GIFTCARD_TEXT_DOMAIN' ),
		'not_found_in_trash' => __ ( 'No Giftcard found in trash', 'GIFTCARD_TEXT_DOMAIN' ),
		'parent' => __ ( 'Parent Giftcard', 'GIFTCARD_TEXT_DOMAIN' )
		),
		'public' => true,
		'publicly_queryable'    => false,
		'exclude_from_search'   => false,
		'has_archive' => true,
		'show_in_menu' => true,
		'hierarchical' => true,
		'supports' => array (
		'title'
	
				)
		) );
		
		register_post_status ( 'inactive', array (
		'label' => __ ( 'In active', 'GIFTCARD_TEXT_DOMAIN' ),
		'public' => true,
		'exclude_from_search' => false,
		'show_in_admin_all_list' => true,
		'show_in_admin_status_list' => true,
		'label_count' => _n_noop ( 'In active <span class="count">(%s)</span>', 'In active <span class="count">(%s)</span>' )
		) );
	}
	public function install() {
		global $wpdb;
		// get current version to check for upgrade
		$installed_version = get_option( 'magenest_giftregistry_version' );
		
		// install
		if ( ! $installed_version ) {

	
			
			// install default settings, terms, etc

			if (!function_exists('dbDelta')) {
				include_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			}
			$prefix = $wpdb->prefix;

			$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftcard` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_id` int(11) NOT NULL,
			  `product_name` varchar(255)  NULL,
			  `user_id` int(11) NOT NULL,
			  `balance` decimal(12,4) NOT NULL,
			  `init_balance` decimal(12,4) NOT NULL,
			  `send_from_firstname` varchar(255) NOT NULL,
			  `send_from_last_name` varchar(255) NOT NULL,
			  `send_from_email` varchar(255) NOT NULL,
			  `send_to_name` varchar(255) NOT NULL,
			  `send_to_email` varchar(255)  NOT NULL,
			  `scheduled_send_time` datetime NOT NULL,
			  `is_sent` tinyint(4) NOT NULL,
			  `send_via` varchar(255)   NULL,
			  `extra_info` text   NULL,
			  `code` varchar(255)  NOT NULL,
			  `message` text   NULL,
			  `status` tinyint(4) NOT NULL,
			  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `expired_at` datetime  NULL,
			  `recipient_address` text   NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
						/**
			 *  $product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data = array()
			 */
			dbDelta( $query );
			$query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftcard_history` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`giftcard_id` int(11)NOT NULL,
			`product` varchar(255) NULL,
			`product_id` int(11) NOT NULL,
			`balance` int(11) NOT NULL,
			`change_balanced` int(11)  NULL,
			`order_id` int(11)  NULL,
			`action` TEXT NULL,
			`user_id` int(11) NULL,
			`description` varchar (255)  NOT NULL,
			`info_request` text,
			`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";

			dbDelta( $query );

		
				
			$this->create_pages();
			update_option( 'magenest_giftcard_version', self::VERSION );

		}

		// upgrade if installed version lower than plugin version
		if ( -1 === version_compare( $installed_version, self::VERSION ) )
		$this->upgrade( $installed_version );
	}

	public function upgrade() {

}
/**
* create gift registry pages for plugin
	*/
	public function create_pages() {
	if (!function_exists('wc_create_page'))  {
		   include_once dirname ( __DIR__ ) . '/woocommerce/includes/admin/wc-admin-functions.php';
		}
		$pages =  array (
	'giftregistry' => array (
						'name' => _x ( 'giftcard', 'Page slug', 'woocommerce' ),
								'title' => _x ( 'Gift card', 'Page title', 'woocommerce' ),
								'content' => '[magenest_giftcard]'
				)
		) ;

		foreach ( $pages as $key => $page ) {
		wc_create_page ( esc_sql ( $page ['name'] ), 'magenest-giftcard' . $key . '_page_id', $page ['title'], $page ['content'], ! empty ( $page ['parent'] ) ? wc_get_page_id ( $page ['parent'] ) : '' );
	}
	}

	/**
			* add menu items
	*/
    public function admin_menu() {
        global $menu;
        include_once GIFTCARD_PATH .'admin/magenest-giftcard-admin.php';
        include_once GIFTCARD_PATH .'admin/magenest-giftcard-setting.php';
        include_once GIFTCARD_PATH .'admin/giftcard-columns.php';
        include_once GIFTCARD_PATH .'admin/giftcard-metabox.php';
        include_once GIFTCARD_PATH .'admin/giftcard-savemeta.php';

        add_submenu_page ( 'edit.php?post_type=shop_giftcard', __ ( 'Send bulk email', self::TEXT_DOMAIN ), __ ( 'Send bulk email',  self::TEXT_DOMAIN), 'manage_woocommerce', 'giftcard_send_mail', array (
            $this,
            'send_email'
        ) );
    }

    public function send_email() {
//edit.php?post_type=shop_giftcard&page=giftcard_send_mail
        if ($_REQUEST['action'] =='send_mail' && isset($_REQUEST['id']))  {
            $giftcardId = $_REQUEST['id'];
            $post = get_post($giftcardId);
            $code =$post->post_title;

            $giftcardInstance = new Magenest_Giftcard($code);
            $giftcardInstance->send();
        }
    }

	public static function getInstance() {
	if (! self::$giftcard_instance) {
	self::$giftcard_instance = new Magenest_Giftcard_Main();
	}

	return self::$giftcard_instance;
	}


	}

	$magenest_giftcard_loaded = Magenest_Giftcard_Main::getInstance ();
