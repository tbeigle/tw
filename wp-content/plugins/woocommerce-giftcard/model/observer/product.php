<?php
class Magenest_Giftcard_Product {
	public function __construct() {
		add_filter( 'product_type_options', array($this,'add_giftcard_product_type')  );
		add_action('woocommerce_product_options_pricing', array($this, 'add_giftcard_price'));
		add_action('woocommerce_process_product_meta_simple' , array($this,'save_giftcard_product_info') );
		add_filter('woocommerce_add_cart_item_data',array($this,'process_add_giftcard'),10,2);
		add_action( 'woocommerce_before_add_to_cart_button', array($this,'add_giftcart_fields') );
		add_action( 'woocommerce_proceed_to_checkout', array($this,'show_apply_giftcart_form'  ),1);
		
		//adjust price for gift card
		add_filter('woocommerce_get_price_html', array($this, 'show_giftcard_price') ,10,2);
		
		//adjust price for gift card
		add_filter('woocommerce_get_price', array($this, 'get_giftcard_price') ,10,2);
		
		//add_action('woocommerce_before_shop_loop_item', array($this,'hide_input_price_on_category_page'));
		
		add_filter('woocommerce_loop_add_to_cart_link', array($this, 'add_to_cart_link'),10,2);
	}
	
	/**
	 * @param unknown $add_to_cart_html
	 * @param WC_Product $product
	 * @return unknown|string
	 */
	public function add_to_cart_link($add_to_cart_html, $product) {
		$post_id = $product->id;
		$is_giftcard = get_post_meta( $post_id, '_giftcard', true );
		if (!$is_giftcard) {
			return $add_to_cart_html;
		} else {
			$select_options = sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s</a>',
			esc_url( $product->get_permalink() ),
			esc_attr( $product->id ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			$product->is_purchasable() && $product->is_in_stock() ? 'available' : '',
			esc_attr( $product->product_type ),
			esc_html(__('Select Options' , GIFTCARD_TEXT_DOMAIN))
			);
			return $select_options;
		}
	}
	public function hide_input_price_on_category_page() {
		$js = '<script type="text/javascript">
				jQuery(document).ready(function() {
				jQuery(".giftcardinputprice").hide();
				jQuery(".giftcardhelpicon").hide();
	});
				 </script>';
		echo $js;
	}
	public function get_giftcard_price($price, $product) {
		$post_id = $product->id;
		$is_giftcard = get_post_meta( $post_id, '_giftcard', true );
		if ($is_giftcard) {
			$price_model = get_post_meta($post_id,'_giftcard-price-model' , true);
			$gc = 'gc'.$post_id ;
			//$woocommerce->session->$gc= $_POST['giftcard-amount'];
			if (isset($_SESSION[$gc]) &&  is_numeric($_SESSION[$gc]) &&  $_SESSION[$gc] > 0 ) {
				
				return $_SESSION[$gc];
			} else {
				return $price;
			}
		}
		return $price;
	}
	public function show_giftcard_price($price, $product) {
		if (!is_single())  return $price;
		$post_id = $product->id;
		$is_giftcard = get_post_meta( $post_id, '_giftcard', true );
		if ($is_giftcard) {
		$price_model = get_post_meta($post_id,'_giftcard-price-model' , true);
		
		switch ($price_model) {
			case 'fixed-price': {
				return $price;
				break;
			}
			case 'selected-price': {
				$presets = get_post_meta($post_id ,'_giftcard-preset-price' , true);
				$preset = explode(';', $presets);
				
				$currency_symbol =get_woocommerce_currency_symbol();
				$html = __('From', GIFTCARD_TEXT_DOMAIN) .' ' .$currency_symbol.$preset[0];
				
				return $html;
				break;
			}
			
			case 'custom-price' : {
				$currency_symbol =get_woocommerce_currency_symbol();
				
				$price_range = get_post_meta($post_id ,'_giftcard-price-range' , true);
				
				$prices = explode('-', $price_range);
				$help_tit = __('Enter an amount between ', GIFTCARD_TEXT_DOMAIN) .$currency_symbol .' ' . $prices[0] . __(' and ', GIFTCARD_TEXT_DOMAIN).$currency_symbol.' '.  $prices[1];  
				$html = __('From', GIFTCARD_TEXT_DOMAIN) .' ' .$currency_symbol.$prices[0];
				
				$placeholder = $prices[0]. '-'. $prices[1];
				$help_icon = GIFTCARD_URL. '/assets/HelpIcon_small.gif';
				$html .="<img class='giftcardhelpicon' style='display:inline' src='{$help_icon}' title='{$help_tit}' />";
				return $html;
				break;
			}
		}
		} else {
			return $price;
		}
		
	}
	public function save_giftcard_product_info($post_id) {
		//_giftcard-price-model
		if (isset($_POST['_giftcard-price-model']))
			update_post_meta( $post_id, '_giftcard-price-model', $_POST['_giftcard-price-model'] );
		
		if (isset($_POST['_giftcard-preset-price']))
		update_post_meta( $post_id, '_giftcard-preset-price', $_POST['_giftcard-preset-price'] );
		
		if (isset($_POST['_giftcard-price-range']))
		update_post_meta( $post_id, '_giftcard-price-range', $_POST['_giftcard-price-range'] );
		
		if (isset($_POST['_giftcard-expiry-date']))
		update_post_meta( $post_id, '_giftcard-expiry-date', $_POST['_giftcard-expiry-date'] );
		
		
		if (isset($_POST['_giftcard'])){
		update_post_meta( $post_id, '_giftcard', 'yes' );
		update_post_meta( $post_id, '_virtual', 'yes' );
		} else {
            update_post_meta( $post_id, '_giftcard', 'no' );

        }
		
		
	}
	public function add_giftcard_product_type( $product_type_options ) {
	
		$giftcard = array(
				'giftcard' => array(
						'id' => '_giftcard',
						'wrapper_class' => 'show_if_simple show_if_variable',
						'label' => __( 'Gift Card', GIFTCARD_TEXT_DOMAIN ),
						'description' => __( 'Gift card is virtual product', GIFTCARD_TEXT_DOMAIN )
				),
		);
	
		$product_type_options = array_merge( $giftcard, $product_type_options );
	
		return $product_type_options;
	}
	
	public function add_giftcard_price() {
		ob_start();
		$id = 0;
		$template_path = GIFTCARD_PATH.'admin/view/';
		$default_path = GIFTCARD_PATH.'admin/view/';
		
		
		wc_get_template( 'view-giftcard-price.php', array(
		'id' 		=>$id,
		
		),$template_path,$default_path
		);
		echo  ob_get_clean();
	//echo 	include_once GIFTCARD_PATH.'/admin/view/view-giftcard-price.php';
	}
			
   public	function process_add_giftcard($cart_item_data, $product_id) {
		$is_giftcard = get_post_meta( $product_id, '_giftcard', true );
	
		if ( $is_giftcard == "yes" ) {
	
			$unique_cart_item_key = md5("gc" . microtime().rand());
			$cart_item_data['unique_key'] = $unique_cart_item_key;
	
		}
	
		return $cart_item_data;
	}
	
	/**
	 * 
	 */
	public function add_giftcart_fields() {
		if (!is_single()) return;
		global $post;
		
		$is_giftcard = get_post_meta( $post->ID, '_giftcard', true );
		if ( $is_giftcard == 'yes' ) {
			ob_start();
			$id = 0;
			$template_path = GIFTCARD_PATH.'template/';
			$default_path = GIFTCARD_PATH.'template/';
			
			
			wc_get_template( 'add_giftcart_fields.php', array( 'id'=>$id,  ),$template_path,$default_path );
			echo  ob_get_clean();
		}
	}
	
	public function show_apply_giftcart_form() {
		global $post;
		
			ob_start();
			$id = 0;
			$template_path = GIFTCARD_PATH.'template/';
			$default_path = GIFTCARD_PATH.'template/';
				
				
			wc_get_template( 'add_giftcart_form.php', array( 'id'=>$id,  ),$template_path,$default_path );
			echo  ob_get_clean();
	}
}

return new Magenest_Giftcard_Product();