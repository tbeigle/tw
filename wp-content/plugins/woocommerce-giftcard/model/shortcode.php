<?php
class Magenest_Giftcard_Shortcode {
	public  function init() {
		// Define shortcodes
		$shortcodes = array (
	
				'magenest_giftcard' => __CLASS__ . '::magenest_giftcard',
	
				
		);
	
		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode ( apply_filters ( "{$shortcode}", $shortcode ), $function );
		}
	
		;
	}
	
	public  function magenest_giftcard() {
		$order_id =199;
			$order = wc_get_order($order_id);
				
			/* @var $order WC_Order */
			if (sizeof ( $order->get_items () ) > 0) {
					
				foreach ( $order->get_items () as $item ) {
					$_product     = apply_filters( 'magenest_giftcard_order_item_product', $order->get_product_from_item( $item ), $item );
		
					/* @var $_product WC_Product */
		
					$giftcard_balance = $_product->get_price();
					$is_giftcard = get_post_meta ( $_product->id, '_giftcard', true );
					if($is_giftcard=='yes') {
						$to_name ='';
						$to_email ='';
						$message ='';
					//$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
					$item_meta = $item;
					if (isset($item_meta['To Name']) && isset($item_meta['To Name'][0])) {
						$to_name = $item_meta['To Name'][0];
					}
		
					if (isset($item_meta['Message']) && isset($item_meta['Message'][0])) {
						$message = $item_meta['Message'][0];
					}
		
					if (isset($item_meta['To Email']) && isset($item_meta['To Email'][0])) {
						$to_email = $item_meta['To Email'][0];
					}
		
					/* save gift card and send notification email*/
		
					$gift_card_data = array(
							'product_id'              => $_product->id,
							'product_name'              => $_product->get_title(),
							'user_id'             => $order->get_user_id(),
							'balance'                => $giftcard_balance,
							'init_balance'        => $giftcard_balance,
							'send_from_firstname'                =>'',
							'send_from_last_name'       => '',
							'send_to_name'=>$to_name,
							'send_to_email'=>$to_email,
							'scheduled_send_time'     => '',
							'is_sent'                => 0,
							'send_via'                => '',
							'extra_info'           => '',
							'code'              => '',
							'message'         =>$message,
							'status' =>0,
							'expired_at'         => '',
								
					);
					$gifcard = new Magenest_Giftcard();
					$gifcard->generateGiftcard($code = '' ,$gift_card_data );
				}
				}
			}
		}
		
}