<?php

namespace Wooclover\CloverApi;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class OrderConnector extends BaseConnector {

	public function __construct ( $merchantId, $apiToken ) {
		parent::__construct( $merchantId, $apiToken, "orders", Domain\Order::getClassName() );
	}

	public function createOrder () {
		$result = parent::post();

		$result = json_decode( $result );

		return $result->uuid;
	}

	public function getOrders ( $type = \Wooclover\Core\Conversor::TYPE_STRING ) {

		return parent::get( $this->getDefaultConversor( $type, true ), "", array( 'lineItems', 'payments.cardTransaction', 'customers' ) );
	}

	private function processItems () {
		
	}

	/**
	 * Return the created order with the following fields
	 *		href,
	 *		id,
	 *		currency
	 *		total
	 *		title
	 *		taxRemoved
	 *		isVat
	 *		manualTransaction
	 *		groupLineItems
	 *		testMode
	 *		createdTime
	 *		clientCreatedTime
	 *		modifiedTime
	 * @param \Wooclover\CloverApi\Domain\Order $order
	 * @param type $type
	 * @return type
	 */
	public function addOrder ( \Wooclover\CloverApi\Domain\Order $order, $type = \Wooclover\Core\Conversor::TYPE_STRING ) {

		return parent::post( "", $order, $this->getDefaultConversor( $type, false ) );
	}

	public function updateOrder ( \Wooclover\CloverApi\Domain\Order $order ) {

		return parent::post( "{$order->getId()}", $order );
	}

}
 