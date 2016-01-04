<?php

namespace Wooclover\WooApi;

use \Wooclover\Core\MasterConnector;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class OrderConnector extends BaseConnector implements \Wooclover\Core\Interfaces\iOrderConnector {

	/**
	 * Create an order
	 * @param \Wooclover\CloverApi\Domain\Order $order
	 * @return int | WP_Error
	 */
	public function addOrder( \Wooclover\CloverApi\Domain\Order $order ) {

		if ( $order->getTotal() == 0 ) {
			return;
		}

		$createdTime = \Wooclover\Core\Utils::convertCloverDate( $order->getCreatedTime() );

		// build order data
		$orderData = array(
		    'post_type' => 'shop_order',
		    'post_title' => 'Order &ndash; ' . \Wooclover\Core\Utils::convertCloverDate( $order->getCreatedTime(), 'F d, Y @ h:i A' ), //'June 19, 2014 @ 07:19 PM'
		    'post_status' => 'publish',
		    'ping_status' => 'closed',
		    'post_excerpt' => $order->getNote(),
		    'post_author' => \Wooclover\Core\Settings\WcRegistry::instance()->getSyncUserId(),
		    'post_date' => $createdTime,
		    'comment_status' => 'open'
		);

		//First we need to ensure that the order doesn't exists
		$args = array(
		    'meta_key' => Config::CloverIdMetaKey,
		    'meta_value' => $order->getId(),
		    'post_type' => 'shop_order',
		    'posts_per_page' => 1
		);
		$posts = get_posts( $args );

		$orderId = false;
		if ( $posts && count( $posts ) == 1 ) {

			$existingOrder = $posts[ 0 ];
			$orderId = $existingOrder->ID;
		} else {

			// Create woo order
			$orderId = wp_insert_post( $orderData, true );

			if ( is_wp_error( $orderId ) ) {
				return $orderId;
			}
		}


		wp_set_object_terms( $orderId, array( 'completed' ), 'shop_order_status', false );

		// add a bunch of meta data
		add_post_meta( $orderId, 'transaction_id', $order->getId(), true );
		add_post_meta( $orderId, '_payment_method_title', 'Imported from Clover', true );
		add_post_meta( $orderId, '_order_total', \Wooclover\Core\Utils::convertPrice( $order->getTotal() ), true );
		//add_post_meta( $orderId, '_customer_user', $account->user_id, true );
		add_post_meta( $orderId, '_completed_date', $createdTime, true );
		//add_post_meta( $orderId, '_order_currency', $order->getCurrency(), true );
		add_post_meta( $orderId, '_paid_date', $createdTime, true );
		add_post_meta( $orderId, Config::CloverIdMetaKey, $order->getId(), true );
		update_post_meta( $orderId, Config::LastSyncDate, current_time( 'mysql' ) );



		$items = $order->getSummarizedLineItems();

		if ( ! $items ) {
			return $orderId;
		}

		//Import the items
		foreach ( $items as $item ) {

			//Check if the item already exist
			$product = MasterConnector::getInventoryConnector()->getProductByCloverId( $item->getExistingID() );

			// If it doesn't exists, we have to create it
			if ( ! $product ) {
				$product = new \Wooclover\CloverApi\Domain\Product();
				$product->setPrice( \Wooclover\Core\Utils::convertPrice( $item->getPrice() ) );
				$product->setName( $item->getName() );
				$product->setId( $item->getId() );
				$productId = MasterConnector::getInventoryConnector()->addProduct( $product );
			} else {
				$productId = $product->ID;
			}

			$itemId = wc_add_order_item( $orderId, array(
			    'order_item_name' => $item->getName(),
			    'order_item_type' => 'line_item'
				) );

			if ( $itemId ) {

				// add item meta data
				wc_add_order_item_meta( $itemId, '_qty', $item->getQty() );
				wc_add_order_item_meta( $itemId, '_product_id', $productId );
				wc_add_order_item_meta( $itemId, '_variation_id', '' );
				wc_add_order_item_meta( $itemId, '_line_subtotal', wc_format_decimal( \Wooclover\Core\Utils::convertPrice( $item->getPrice() ) ) );
				wc_add_order_item_meta( $itemId, '_line_total', wc_format_decimal( \Wooclover\Core\Utils::convertPrice( $item->getPrice() ) ) );
				wc_add_order_item_meta( $itemId, '_line_tax', wc_format_decimal( 0 ) );
				wc_add_order_item_meta( $itemId, '_line_subtotal_tax', wc_format_decimal( 0 ) );
			}
		}

		//payment
		$payments = $order->getPayments();
		foreach ( $payments as $payment ) {
			if ( $payment->getTipAmount() ) {
				$tipId = wc_add_order_item( $orderId, array(
				    'order_item_name' => 'Tip',
				    'order_item_type' => 'line_item'
					) );
				if ( $tipId ) {
					// add item meta data
					wc_add_order_item_meta( $tipId, '_qty', 1 );
					wc_add_order_item_meta( $tipId, '_product_id', FALSE );
					wc_add_order_item_meta( $tipId, '_variation_id', '' );
					wc_add_order_item_meta( $tipId, '_line_subtotal', wc_format_decimal( \Wooclover\Core\Utils::convertPrice( $payment->getTipAmount() ) ) );
					wc_add_order_item_meta( $tipId, '_line_total', wc_format_decimal( \Wooclover\Core\Utils::convertPrice( $payment->getTipAmount() ) ) );
					wc_add_order_item_meta( $tipId, '_line_tax', wc_format_decimal( 0 ) );
					wc_add_order_item_meta( $tipId, '_line_subtotal_tax', wc_format_decimal( 0 ) );
				}
			}

			if ( $payment->getTaxAmount() ) {
				$taxId = wc_add_order_item( $orderId, array(
				    'order_item_name' => '',
				    'order_item_type' => 'tax'
					) );
				if ( $taxId ) {
					// add tax meta data
					wc_add_order_item_meta( $taxId, 'rate_id', FALSE );
					wc_add_order_item_meta( $taxId, 'label', 'Tax' );
					wc_add_order_item_meta( $taxId, 'compound', '' );
					wc_add_order_item_meta( $taxId, 'tax_amount', wc_format_decimal( \Wooclover\Core\Utils::convertPrice( $payment->getTaxAmount() ) ) );
					wc_add_order_item_meta( $taxId, 'shipping_tax_amount', wc_format_decimal( 0 ) );
				}
			}
		}


		return $orderId;
//			
//			// billing info
//			add_post_meta( $orderId, '_billing_address_1', $order->address_line_1, true );
//			add_post_meta( $orderId, '_billing_address_2', $order->address_line_2, true );
//			add_post_meta( $orderId, '_billing_city', $order->city, true );
//			add_post_meta( $orderId, '_billing_state', $order->state, true );
//			add_post_meta( $orderId, '_billing_postcode', $order->zip, true );
//			add_post_meta( $orderId, '_billing_country', $order->country, true );
//			add_post_meta( $orderId, '_billing_email', $order->from_email, true );
//			add_post_meta( $orderId, '_billing_first_name', $order->first_name, true );
//			add_post_meta( $orderId, '_billing_last_name', $order->last_name, true );
//			add_post_meta( $orderId, '_billing_phone', $order->phone, true );
//
//			// get product by item_id
//			$product = get_product_by_sku( $order->item_id );
//
//			if ( $product ) {
//
//				// add item
//				$itemId = wc_add_order_item( $orderId, array(
//					'order_item_name' => $product->get_title(),
//					'order_item_type' => 'line_item'
//						) );
//
//				if ( $itemId ) {
//
//					// add item meta data
//					wc_add_order_item_meta( $itemId, '_qty', 1 );
//					wc_add_order_item_meta( $itemId, '_tax_class', $product->get_tax_class() );
//					wc_add_order_item_meta( $itemId, '_product_id', $product->ID );
//					wc_add_order_item_meta( $itemId, '_variation_id', '' );
//					wc_add_order_item_meta( $itemId, '_line_subtotal', wc_format_decimal( $order->gross ) );
//					wc_add_order_item_meta( $itemId, '_line_total', wc_format_decimal( $order->gross ) );
//					wc_add_order_item_meta( $itemId, '_line_tax', wc_format_decimal( 0 ) );
//					wc_add_order_item_meta( $itemId, '_line_subtotal_tax', wc_format_decimal( 0 ) );
//				}
//			} else {
//
//				$order->errors = 'Product SKU (' . $order->$itemId . ') not found.';
//			}
	}

	public function getNotSyncOrders() {


		//Read non sync orders
		$args = array(
		    'meta_key' => Config::CloverIdMetaKey,
		    'meta_compare' => 'NOT EXISTS',
		    'post_type' => 'shop_order',
		    'posts_per_page' => -1
		);


		$posts = get_posts( $args );
		$orders = array();

		foreach ( $posts as $post ) {

			$wooOrder = new \WC_Order( $post->ID );
			$order = new \Wooclover\CloverApi\Domain\Order();


			$wooItems = $wooOrder->get_items();
			foreach ( $wooItems as $wooItem ) {
				//qty
				//_line_total
//				$quantity = $wooItem->qty;
				//If the quantity is bigger than 1 we need to slit the items
//				if ( $quantity > 1){
//					
//				}
				
				$lineItem = new \Wooclover\CloverApi\Domain\LineItem();
				$lineItem->setName( 'Not defined' );

//				if ( isset( $wooItem[ 'name' ] ) && !\Wooclover\Core\Utils::isEmpty( $wooItem[ 'name' ] ) ) {
//					$lineItem->setName( $wooItem[ 'name' ] );
//				} elseif ( isset( $wooItem[ 'product_id' ] ) ) {

				if ( isset( $wooItem[ 'item_meta' ] ) && isset( $wooItem[ 'item_meta' ][ '_product_id' ] ) ) {
					
					$productId = $wooItem[ 'item_meta' ][ '_product_id' ][0];
					$product = get_post( $wooItem[ 'item_meta' ][ '_product_id' ][0] );

					$info = \Wooclover\Core\Utils::getCloverInfo( $product->ID );
					$lineItem->getItem()->setId( $info->cloverId );
					$lineItem->setName( $product->post_title );

					$lineItem->setPrice( \Wooclover\Core\Utils::convertPriceForClover( $wooItem[ 'line_total' ] ) );
					$lineItem->setUnitQty( $wooItem[ 'qty' ] );

					$order->addLineItem( $lineItem );
				}
				
			}

//			$order->setCreatedTime($wooOrder->order_date);
			$order->setTotal( \Wooclover\Core\Utils::convertPriceForClover( $wooOrder->get_total() ) );

			$order->setTitle( "Order number " . $wooOrder->get_order_number() );
			$order->setLocalId( $post->ID );

			$orders[] = $order;
		}

		return $orders;
	}


	public function associateOrderWithCloverId ( $orderId, $cloverOrderId ) {
		add_post_meta( $orderId, Config::CloverIdMetaKey, $cloverOrderId, true );
		update_post_meta( $orderId, Config::LastSyncDate, current_time( 'mysql' ) );
	}

}

/*


object(WC_Order)#361 (11) {
  ["id"]=>
  int(1189)
  ["prices_include_tax"]=>
  bool(false)
  ["tax_display_cart"]=>
  string(4) "excl"
  ["display_totals_ex_tax"]=>
  bool(true)
  ["display_cart_ex_tax"]=>
  bool(true)
  ["order_date"]=>
  string(19) "2014-08-04 14:40:00"
  ["modified_date"]=>
  string(19) "2014-08-04 14:43:07"
  ["customer_message"]=>
  string(0) ""
  ["customer_note"]=>
  string(0) ""
  ["post_status"]=>
  string(7) "publish"
  ["status"]=>
  string(10) "processing"
}

array(11) {
  ["name"]=>
  string(17) "Pollo Quesadilla "
  ["type"]=>
  string(9) "line_item"
  ["item_meta"]=>
  array(8) {
    ["_qty"]=>
    array(1) {
      [0]=>
      string(1) "1"
    }
    ["_tax_class"]=>
    array(1) {
      [0]=>
      string(0) ""
    }
    ["_product_id"]=>
    array(1) {
      [0]=>
      string(3) "170"
    }
    ["_variation_id"]=>
    array(1) {
      [0]=>
      string(0) ""
    }
    ["_line_subtotal"]=>
    array(1) {
      [0]=>
      string(4) "8.25"
    }
    ["_line_subtotal_tax"]=>
    array(1) {
      [0]=>
      string(1) "0"
    }
    ["_line_total"]=>
    array(1) {
      [0]=>
      string(4) "8.25"
    }
    ["_line_tax"]=>
    array(1) {
      [0]=>
      string(1) "0"
    }
  }
  ["qty"]=>
  string(1) "1"
  ["tax_class"]=>
  string(0) ""
  ["product_id"]=>
  string(3) "170"
  ["variation_id"]=>
  string(0) ""
  ["line_subtotal"]=>
  string(4) "8.25"
  ["line_subtotal_tax"]=>
  string(1) "0"
  ["line_total"]=>
  string(4) "8.25"
  ["line_tax"]=>
  string(1) "0"
}



 */