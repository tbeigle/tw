<?php

namespace Wooclover\Core\Interfaces;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

interface iOrderConnector {
	
	function addOrder ( \Wooclover\CloverApi\Domain\Order $order );
	
	/**
	 * @return \Wooclover\CloverApi\Domain\Order[] orders
	 */
	function getNotSyncOrders();
	
	/**
	 * Associate an existing order with a clover existing one
	 * @param int $orderId
	 * @param int $cloverOrderId
	 */
	function associateOrderWithCloverId( $orderId, $cloverOrderId );
	
}