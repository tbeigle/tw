<?php

namespace Wooclover\Core;

use \Wooclover\CloverApi\CloverConnector;
use \Wooclover\Core\MasterConnector;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class OrderManager {

	public function __construct () {
		
	}

	public function importFull () {
		$orders = CloverConnector::getOrderConnector()->getOrders( \Wooclover\Core\Conversor::TYPE_OBJECT );

		if ( $orders ) {
			foreach ( $orders as $order ) {

				$result = MasterConnector::getOrderConnector()->addOrder( $order );

				if ( is_wp_error( $result ) ) {
					return $result;
				}
			}
		}
	}

	public function exportNotSyncOrders () {

		$orders = MasterConnector::getOrderConnector()->getNotSyncOrders();

		foreach ( $orders as $order ) {

			$cloverResult = CloverConnector::getOrderConnector()->addOrder( $order, \Wooclover\Core\Conversor::TYPE_ARRAY );
 
			MasterConnector::getOrderConnector()->associateOrderWithCloverId( $order->getLocalId(), $cloverResult[ 'id' ] );
		}

		return "Exported successful: ";
	}

	public function import ( $cloverId ) {

		$item = CloverConnector::getInventoryConnector()->getItem( $cloverId, \Wooclover\Core\Conversor::TYPE_OBJECT );

		$result = MasterConnector::getInventoryConnector()->addProduct( $item );

		return $result;
	}

	public function export ( $id ) {
		
	}

}
