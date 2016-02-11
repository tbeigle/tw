<?php
include_once GIFTCARD_PATH .'model/giftcard.php';

 class Magenest_Giftcard_Buygiftcard {
 	
 	public function __construct() {
        /**
         * Cart manipulation
         */
        add_filter( 'woocommerce_add_cart_item_data', array( &$this, 'add_cart_item_data' ), 50, 2 );
        add_filter( 'woocommerce_add_cart_item', array( &$this, 'add_cart_item' ), 50, 1 );

        add_filter( 'woocommerce_get_cart_item_from_session', array( &$this, 'get_cart_item_from_session' ), 50, 2 );
        add_filter( 'woocommerce_get_item_data', array( &$this, 'get_item_data' ), 50, 2 );
        add_action( 'woocommerce_add_order_item_meta', array( $this, 'order_item_meta' ), 50, 2 );


 		add_action("woocommerce_checkout_order_processed", array($this , 'generateGiftcard'),10);

 		///////////////////////////

 		add_action ( 'woocommerce_order_status_pending', array ( $this, 'active_giftcard' ),10 );
 		add_action ( 'woocommerce_order_status_on-hold', array ( $this, 'active_giftcard' ) ,10);
 		add_action ( 'woocommerce_order_status_processing', array ( $this, 'active_giftcard') ,10 );
 		add_action ( 'woocommerce_order_status_completed', array ( $this, 'active_giftcard' ),10 );
 	}

     /**
      * @param $cart_item_meta
      * @param $product_id
      * @return mixed
      */
     public function add_cart_item_data( $cart_item_meta, $product_id ) {

         $post = $_REQUEST;

         if (isset($post['giftcard'])) {
             $option = $post['giftcard'];

             foreach($option as $key =>$value) {
                 //key can be amount

                 switch ($key)  {
                     case 'amount' :
                         $cart_item_meta['giftcard_option'][$key] = array(
                             'name'   		=> esc_html( __('Value', GIFTCARD_TEXT_DOMAIN)  ),
                             'value'  		=> esc_html( $value ),
                             'price'  		=> $value
                         );
                         break;

                     case 'send_to_name' :
                         $cart_item_meta['giftcard_option'][$key] = array(
                             'name'   		=> esc_html( __('To', GIFTCARD_TEXT_DOMAIN)  ),
                             'value'  		=> esc_html( $value ),
                             'price'  		=> 0
                         );
                         break;

                     case 'send_to_email' :
                         $cart_item_meta['giftcard_option'][$key] = array(
                             'name'   		=> esc_html( __('Send To Email', GIFTCARD_TEXT_DOMAIN)  ),
                             'value'  		=> esc_html( $value ),
                             'price'  		=> 0
                         );
                         break;

                     case 'message' :
                         $cart_item_meta['giftcard_option'][$key] = array(
                             'name'   		=> esc_html( __('Message', GIFTCARD_TEXT_DOMAIN)  ),
                             'value'  		=> esc_html( $value ),
                             'price'  		=> 0
                         );
                         break;

                 }
             }

         }


         return $cart_item_meta;

     }

     /**
      * @param $cart_item
      */
     public function add_cart_item($cart_item) {

         if ( ! empty( $cart_item['giftcard_option'] ) ) {
             $price = 0;

             foreach ( $cart_item['giftcard_option'] as $option ) {
                 $option['price']=(float)wc_format_decimal($option['price'],"",true);
                 $price += $option['price'];
             }
             if ($price > 0)
             $cart_item['data']->set_price( $price );
         }

         return $cart_item;
     }

     /**
      * @param $cart_item
      * @param $values
      * @return mixed
      */
     public function get_cart_item_from_session($cart_item, $values) {

         if ( ! empty( $values['giftcard_option'] ) ) {
             $cart_item['giftcard_option'] = $values['giftcard_option'];
             $cart_item = $this->add_cart_item( $cart_item );
         }

         return $cart_item;
     }

     /**
      * @param $other_data
      * @param $cart_item
      * @return array
      */
     public function get_item_data($other_data, $cart_item) {
         if (  !empty( $cart_item['giftcard_option'] ) ) {

             if (isset($cart_item['giftcard_option']['amount'])) {
                 $other_data[] = array('name'=> __('Value', GIFTCARD_TEXT_DOMAIN) ,'value' => $cart_item['giftcard_option']['amount']['value']);
             }

             if (isset($cart_item['giftcard_option']['send_to_name'])) {
                 $other_data[] = array('name'=> __('To Name', GIFTCARD_TEXT_DOMAIN) ,'value' => $cart_item['giftcard_option']['send_to_name']['value']);
             }

             if (isset($cart_item['giftcard_option']['send_to_email'])) {
                 $other_data[] = array('name'=> __('To Email', GIFTCARD_TEXT_DOMAIN) ,'value' => $cart_item['giftcard_option']['send_to_email']['value']);
             }

             if (isset($cart_item['giftcard_option']['message'])) {
                 $other_data[] = array('name'=> __('Message', GIFTCARD_TEXT_DOMAIN) ,'value' => $cart_item['giftcard_option']['message']['value']);
             }
         }

         return $other_data;
     }

     /**
      * Adds meta data to the order.
      */
     public function order_item_meta( $item_id, $values ) {
         if ( ! empty( $values['giftcard_option'] ) ) {
             wc_add_order_item_meta( $item_id, 'giftcard_option', $values['giftcard_option'] );
             $filtered_array=$values['giftcard_option'];

             foreach ( $filtered_array as $section ) {

                 $name = $section['name'];

                 $value = $section['value']  ;


                 wc_add_order_item_meta ( $item_id, $name, $value );


             }
         }

     }
     /**
 	 * change gift card status to active and send mail to the recipient and the giver
 	 * @param int $order_id
 	 */
 	public function active_giftcard($order_id) {
 		
 		global $wpdb;
 		$tbl = $wpdb->prefix .'postmeta';
 		
 		$order = new WC_Order($order_id);
 		
 		$status = $order->get_status();
 		$active_status = get_option('magenest_giftcard_active_when');
 		
 		if ($status == $active_status) {
 			$giftcard = new Magenest_Giftcard();
 			$giftcard->active_giftcard($order_id);
 			
 		}
 	}


	public function generateGiftcard($order_id) {
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
					
					$qty = $item['item_meta']['_qty'][0];

                        if (isset ($item_meta['giftcard_option'])) {
                            $giftcard_option = unserialize($item_meta['giftcard_option']);

                            if (isset($giftcard_option['send_to_name']['value'])) {
                                $to_name = $giftcard_option['send_to_name']['value'];
                            }
                            if (isset($giftcard_option['message']['value'])) {
                                $message = $giftcard_option['message']['value'];
                            }

                            if (isset($giftcard_option['send_to_email']['value']) ) {
                                $to_email = $giftcard_option['send_to_email']['value'];
                            }

                             if (isset($giftcard_option['amount']['value']) ) {
                                 $giftcard_balance = $giftcard_option['amount']['value'];
                            }



                        }

		

                    
					//calculate gift card expired date
					
					$expired_at  = '';
					
					$expired_at_product_scope = get_post_meta($_product->id , '_giftcard-expiry-date', true);
					
					if ($expired_at_product_scope) {
						$expired_at = $expired_at;
					} else {
						if (get_option('magenest_giftcard_timespan')) {
							$giftcard = new Magenest_Giftcard();
							$expired_at_website_scope  = $giftcard->calculateExpiryDate();
							if ($expired_at_website_scope)
							$expired_at  =$expired_at_website_scope;
						}
					}
					
						/* save gift card and send notification email */
					$gift_card_data = array (
							'product_id' => $_product->id,
							'magenest_giftcard_order_id'=> $order->id,
							'product_name' => $_product->get_title (),
							'user_id' => $order->get_user_id (),
							'balance' => $giftcard_balance,
							'init_balance' => $giftcard_balance,
							'send_from_firstname' => '',
							'send_from_last_name' => '',
							'send_to_name' => $to_name,
							'send_to_email' => $to_email,
							'scheduled_send_time' => '',
							'is_sent' => 0,
							'send_via' => '',
							'extra_info' => '',
							'code' => '',
							'message' => $message,
							'status' => 0,
							'expired_at' => $expired_at 
					)
					;
					$gifcard = new Magenest_Giftcard();
					
					for($i = 0; $i < $qty; $i ++) {
						$gifcard->generateGiftcard ( $code = '', $gift_card_data );
					}
				}
			}
		}
	}
 }
 return new Magenest_Giftcard_Buygiftcard();
 