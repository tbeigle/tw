<?php
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directlyGIFTCARD_TEXT_DOMAIN
class Magenest_Giftcard_Admin_Metabox {
	
	public function __construct() {
		add_action ( 'add_meta_boxes', array($this,'giftcard_meta_boxes') );
		
	}
	
	public function giftcard_meta_boxes() {
		global $post;
		
		add_meta_box ( 'giftcard_main', __ ( 'Gift card', GIFTCARD_TEXT_DOMAIN ), array($this,'giftcard_main'), 'shop_giftcard', 'normal', 'high' );
		add_meta_box ( 'giftcard_buy', __ ( 'Purchase Information', GIFTCARD_TEXT_DOMAIN ), array($this,'giftcard_buy'), 'shop_giftcard', 'normal', 'high' );
		
		add_meta_box ( 'giftcard_send_friend', __ ( 'Send friend', GIFTCARD_TEXT_DOMAIN ), array($this,'giftcard_send_friend'), 'shop_giftcard', 'normal', 'default' );
		
		remove_meta_box ( 'woothemes-settings', 'shop_giftcard', 'normal' );
		remove_meta_box ( 'commentstatusdiv', 'shop_giftcard', 'normal' );
		remove_meta_box ( 'commentsdiv', 'shop_giftcard', 'normal' );
		remove_meta_box ( 'slugdiv', 'shop_giftcard', 'normal' );
	}
	
	public function giftcard_main() {
		global $woocommerce;
		
		wp_nonce_field ( 'woocommerce_save_data', 'woocommerce_meta_nonce' );
		woocommerce_wp_text_input ( array (
		'id' => 'balance',
		'label' => __ ( 'Balance',GIFTCARD_TEXT_DOMAIN ),
		'placeholder' => 'Enter balance of giftcard',
		
		) );
		woocommerce_wp_select ( array (
		'id' => 'status',
		'label' => __ ( 'Status', GIFTCARD_TEXT_DOMAIN ),
		'options' =>array (
			'1' => __ ( 'Active', GIFTCARD_TEXT_DOMAIN ),
			'0' => __ ( 'Inactive', GIFTCARD_TEXT_DOMAIN  ) 
	     )
		) );
		
		woocommerce_wp_text_input ( array (
		'id' => 'expired_at',
		'label' => __ ( 'Expiry date', GIFTCARD_TEXT_DOMAIN ),
		
		) );
		//extra_info
		woocommerce_wp_text_input ( array (
		'id' => 'extra_info',
		'label' => __ ( 'Comment', GIFTCARD_TEXT_DOMAIN ),
		
		) );
	}
	public function giftcard_buy() {
		
		woocommerce_wp_text_input ( array (
		'id' => 'product_name',
		'label' => __ ( 'Product name',GIFTCARD_TEXT_DOMAIN),
		'placeholder' => 'Gift card generate from buying product',
		
		) );
	}
	public function giftcard_send_friend() {
		global $woocommerce;
		
		woocommerce_wp_text_input ( array (
		'id' => 'send_to_email',
		'label' => __ ( 'To Email',GIFTCARD_TEXT_DOMAIN ),
		'placeholder' => 'To Email',
		
		) );
		
		woocommerce_wp_text_input ( array (
		'id' => 'send_to_name',
		'label' => __ ( 'To name', GIFTCARD_TEXT_DOMAIN ),
		'placeholder' => 'To name',
		
		) );
		woocommerce_wp_text_input ( array (
		'id' => 'message',
		'label' => __ ( 'To message', GIFTCARD_TEXT_DOMAIN ),
		'placeholder' => 'To message',
		
		) );
	}
}

return new Magenest_Giftcard_Admin_Metabox();




?>
