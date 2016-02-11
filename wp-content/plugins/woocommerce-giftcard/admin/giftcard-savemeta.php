<?php

class Magenest_Giftcard_Savemeta {
	public function __construct() {
		add_action ( 'save_post', array($this, 'updateGiftcard'), 10, 2 );
		
	}
	
	public function updateGiftcard($post_id, $post) {
		global $wpdb, $woocommerce_errors;
		
		$load_data = array(
				'magenest_giftcard_order_id' =>0,
				'product_id'              => 0,
				'product_name'              => '',
				'user_id'             => 0,
				'balance'                => 0,
				'init_balance'        => 0,
				'send_from_firstname'                => '',
				'send_from_last_name'       => '',
				'send_to_name'=>'',
				'send_to_email'=>'',
				'scheduled_send_time'     => '',
				'is_sent'                => 0,
				'send_via'                => '',
				'extra_info'           => '',
				'code'              => '',
				'message'         =>'',
				'status' =>0,
				'expired_at'         => '',
					
		);
		foreach ( $load_data as $key => $default ){
			$value = isset ( $_POST [$key] ) && $_POST [$key] != '' ? $_POST [$key]: $default;
			update_post_meta( $post_id, $key, $value );
	
		}
	}
}

return new Magenest_Giftcard_Savemeta();
