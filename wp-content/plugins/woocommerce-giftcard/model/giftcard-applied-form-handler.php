<?php
class Magenest_Giftcard_Form_Handler {
	
	public function __construct() {
		add_action('wp_loaded', array($this,'apply_giftcard' ) );
		add_action('woocommerce_calculate_totals' , array($this, 'gc') ,10 ,1);
		add_action('woocommerce_cart_totals_before_order_total', array($this,'show_giftcard_discount_in_cart'));
		add_action('woocommerce_review_order_before_order_total', array($this,'show_giftcard_discount_in_cart'));
		add_action('woocommerce_checkout_order_processed' , array($this, 'after_checkout')) ;
		add_action( 'woocommerce_order_status_refunded', 'refund_order' );
		add_filter( 'woocommerce_calculated_total',  array($this,'applydiscount'), 10, 1 );
		
		//add_filter( 'woocommerce_get_order_item_totals',  array($this, 'show_giftcard_discount_on_order'), 10, 2);
		
		//woocommerce_review_order_before_order_total
		//add_action('woocommerce_get_discounted_price',array($this, 'calculate_giftcard_discount'),10,3);
	}
	
	public function refund_order($order_id) {
		global $woocommerce, $wpdb;
		
		$order = new WC_Order( $order_id );
		
		$total = $order->get_order_total();
		$gift_code = get_post_meta( $order_id, 'giftcard_code' );
		$gifcard_discount = get_post_meta( $order_id, 'giftcard_discount' );
		if ($gifcard_discount) {
			$giftcard = new Magenest_Giftcard();
			$giftcard->add_balance($gifcard_discount, $gift_code);
		}
		
	}
	public function show_giftcard_discount_on_order($total_rows,$order) {
		global $woocommerce;
		
		$return = array();
		
		$order_id = $order->id;
		
		$gift_discount = get_post_meta( $order_id, 'giftcard_discount', true);
		$gift_code = get_post_meta( $order_id, 'giftcard_code', true);
		
		if ($gift_discount && $gift_discount != '') {
			$newRow['giftcard'] = array(
					'label' => __( 'Gift Card Payment', GIFTCARD_TEXT_DOMAIN ) . '(' .$gift_code. ')' ,
					'value'	=> woocommerce_price( -1 * $gift_discount)
			);
		
				array_splice($total_rows, 1, 0, $newRow);
		}
		
		return $total_rows;
	}
	public function after_checkout($order_id) {
		global $woocommerce,$wpdb;
		
		if (isset($woocommerce->session->giftcard_discount)) {
			update_post_meta($order_id, 'giftcard_discount', $woocommerce->session->giftcard_discount);
			update_post_meta($order_id, 'giftcard_code', $woocommerce->session->giftcard_code);
			
			
			//subtract giftcard balance
			$giftcard = new Magenest_Giftcard();
			$giftcard->add_balance(-$woocommerce->session->giftcard_discount, $woocommerce->session->giftcard_code);
		
			$woocommerce->session->__unset('giftcard_discount');
			$woocommerce->session->__unset('giftcard_code');
		}
		
	}
	public function show_giftcard_discount_in_cart() {
		global $woocommerce, $wpdb;
		if (isset($woocommerce->session->giftcard_discount) && $woocommerce->session->giftcard_discount > 0) {
		?>

<tr class="order-discount giftcard-discount">
	<th><?php echo __('Gift card') ?> : <?php echo $woocommerce->session->giftcard_code ?></th>
	<td>-<?php echo get_woocommerce_currency_symbol() . ''. $woocommerce->session->giftcard_discount ?></td>
</tr>
<?php 	
	}			
	}
	public function getGiftcardProductInCart() {
		global $woocommerce;
		if ($woocommerce->cart->cart_contents ) {
			foreach ($woocommerce->cart->cart_contents  as $key=>$cart_item) {
				$product_id = $cart_item['product_id'];
				
				$is_giftcard = get_post_meta( $product_id, '_giftcard', true );
				
				if ($is_giftcard) 
					return true;
				
				
			}
		}
		return  false;
	}
	function applydiscount( $total ) {
		$giftcard_code 	= WC()->session->giftcard_code;
	
		if ( isset( $giftcard_code ) ) {
			$total -= WC()->session->discount_cart;
		}
	
		return $total;
	}
	public function gc($cart) {
		global $woocommerce, $wpdb;
		$is_giftcardproduct_incart = $this->getGiftcardProductInCart();
		if (!$is_giftcardproduct_incart) {
			$giftCardCode = $woocommerce->session->giftcard_code;
			$giftcard = new Magenest_Giftcard($giftCardCode);
				
			$balance = $giftcard->balance;
			
			$charge_shipping = get_option ( 'giftcard_apply_for_shipping' );
			$charge_tax = get_option ( 'magenest_enable_giftcard_charge_tax' );
			$charge_fee = get_option ( 'magenest_enable_giftcard_charge_fee' );
			
			////////////////////
			
			$giftcardPayment = 0;
			$cart 			= WC()->session->cart;
			foreach( $cart as $key => $product ) {
			
					if( $charge_tax == 'yes' ){
					if (isset($product['line_total']))	$giftcardPayment += $product['line_total'];
					if (isset($product['line_tax']))	$giftcardPayment += $product['line_tax'];
					} else {
					if (isset($product['line_total']))	$giftcardPayment += $product['line_total'];
					}
			}
			
			
			if( $charge_shipping == 'yes' )
				$giftcardPayment += WC()->session->shipping_tax_total;
				
			
			if( $charge_tax == "yes" )
				$giftcardPayment += WC()->session->shipping_total;
			
			
			if( $charge_fee == "yes" )
				$giftcardPayment += WC()->session->fee_total;
			
			
			if ( $giftcardPayment <= $balance ) {
				WC()->session->giftcard_discount = $giftcardPayment;
				WC()->session->discount_cart = $giftcardPayment;
			
			} else {
				WC()->session->giftcard_discount = $balance;
				WC()->session->discount_cart = $balance;
			}
			
			///////////////////
		
		} else {
			if (get_option('magenest_giftcard_buy_other_giftcard') == 'no' && isset($woocommerce->session->giftcard_discount)) {
			if (isset($woocommerce->session->giftcard_discount)) unset($woocommerce->session->giftcard_discount);
			if (isset($woocommerce->session->giftcard_code)) unset($woocommerce->session->giftcard_code);
			wc_add_notice(__('A gift card can not be used to buy other gift card', GIFTCARD_TEXT_DOMAIN), 'error' );
				
			}
		}
		return $cart;
		
	}
	public function apply_giftcard() {
		if (isset($_POST['giftcard_code'])) {
				global $woocommerce, $wpdb;
			
				if ( ! empty( $_POST['giftcard_code'] ) ) {
					$giftCardCode = sanitize_text_field( $_POST['giftcard_code'] );
			
						//get gift card if it is available check balance , status , expiry date
						$giftcard = new Magenest_Giftcard($giftCardCode);
						$woocommerce->session->giftcard_code = $giftCardCode;
					
						//$orderTotal = (float) $woocommerce->cart->total;
			         
						//if ( $giftcard->is_valid($giftCardCode) ) {
							// Valid Gift Card Entered
			
								//$balance =  $giftcard->balance;
			
								//if ( is_string( $balance ) )  // Determin if the Value from $oldBalance is a String and convert it
									//$balance = (float) $balance;
			
								//if ( is_string( $orderTotal ) )   // Determin if the Value from $orderTotal is a String and convert it
									//$orderTotalCost = (float) $orderTotal;
			
								//$woocommerce->session->giftcard = $giftcard;
								//$woocommerce->session->giftcard_code = $giftCardCode;
			
								//calculate giftcard discount for order
								//if( get_option( 'giftcard_apply_for_shipping' ) == 'no' ) {
								//	$total = $orderTotal - $woocommerce->cart->shipping_total;
								//} else {
								//	$total = $orderTotal ;
								//}
								
							  //	$woocommerce->session->giftcard_discount = min($balance ,$total );
							  	//$woocommerce->cart->discount_cart =$woocommerce->session->giftcard_discount ;
							  	//$woocommerce->cart->total = ($total> $balance) ? ($woocommerce->cart->total -$woocommerce->session->giftcard_discount ) :0;
								//$woocommerce->cart->calculate_totals();
						 if ( $giftcard->is_valid($giftCardCode)) {
								wc_add_notice(  __( 'Gift card applied successfully.', GIFTCARD_TEXT_DOMAIN ), 'success' );
						 } else {
						 	wc_add_notice(  __( 'Gift card is not valid.', GIFTCARD_TEXT_DOMAIN ), 'success' );
						 }
								
						}  else {
							wc_add_notice($giftcard->error_message, 'error' );
			
						}
					
					
				}
			
	}
}

return new Magenest_Giftcard_Form_Handler();