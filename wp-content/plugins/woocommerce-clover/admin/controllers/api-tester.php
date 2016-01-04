<?php

namespace Wooclover\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ApiTester {

	public function init() {
		add_action( 'wp_ajax_wooclover_getCustomers', array( $this, 'getCustomers' ) );
		add_action( 'wp_ajax_wooclover_getCustomer', array( $this, 'getCustomer' ) );
		add_action( 'wp_ajax_wooclover_setCustomer', array( $this, 'updateCustomer' ) );

		add_action( 'wp_ajax_wooclover_getItems', array( $this, 'getItems' ) );
		add_action( 'wp_ajax_wooclover_getItem', array( $this, 'getItem' ) );

		add_action( 'wp_ajax_wooclover_getCategories', array( $this, 'getCategories' ) );

		add_action( 'wp_ajax_wooclover_import', array( $this, 'import' ) );
		add_action( 'wp_ajax_wooclover_importAll', array( $this, 'importAllItems' ) );

		add_action( 'wp_ajax_wooclover_createOrder', array( $this, 'createOrder' ) );
		add_action( 'wp_ajax_wooclover_getOrders', array( $this, 'getOrders' ) );
	}

	public function import( $data ) {
		$request = \Wooclover\Core\Request::current();

		$data = $request->getProperty( 'data' );
		//var_dump($data);
		switch ( $data[ 'type' ] ) {
			case "item":
				$item = $this->getInventoryConnector()->getItem( $data[ 'id' ], \Wooclover\Core\Conversor::TYPE_OBJECT, array( 'categories' ) );

				\Wooclover\Core\Output::send( $item );
				break;
		}
	}

	public function importAllItems() {

		$items = $this->getInventoryConnector()->getItems( \Wooclover\Core\Conversor::TYPE_OBJECT, array( 'categories' ) );

		\Wooclover\Core\Output::sendCollection( $items );
	}

	public function updateCustomer() {
		$request = \Wooclover\Core\Request::current();
		$customerId = $request->getProperty( 'customerId' );
		$data = $request->getProperty( 'data' );

		$result = $this->getCustomerConnector()->updateName( $customerId, $data[ 'firstName' ], $data[ 'lastName' ] );
		\Wooclover\Core\Output::sendString( $result );
	}

	public function getOrders() {
		$result = $this->getOrderConnector()->getOrders( \Wooclover\Core\Conversor::TYPE_STRING );
		\Wooclover\Core\Output::sendString( $result );
	}

	public function createOrder() {
		try {
//		$order = new \Wooclover\CloverApi\Domain\Order();
//		$order->setNote( "This is a new order" );
//		$order->setTitle( "Creating an order " );
//		$order->setEmployeeId( 'JEQAS4SRRBSJE' );
//		$order->setCurrency( \Wooclover\CloverApi\Domain\Currency::CUR_USD);
//		$order->setManualTransaction( true );
//		$order->setPayType('FULL');
//		$order->setId( 'D899X6DBS9F2W');
//		$order->setState( 'OPEN' );
//		
//		$lineItem = new \Wooclover\CloverApi\Domain\LineItem();
//		$lineItem->setNote('This is a note in the item');
//		$lineItem->setPrice( 22 );
//		$lineItem->setQty( 1 );
//		$lineItem->setProductCode( "111AAA" );
//		$lineItem->setUnitQty(1 );
//		
//		$order->addLineItem($lineItem);
//		
			$orderId = $this->getOrderConnector()->createOrder(); //'KGDARC6TRMCH4'; //

			$result = $this->getOrderConnector()->updateNote( $orderId, 'This is a test from yeah!!' );

			$result = $this->getOrderConnector()->updateState( $orderId, 'OPEN' );

			$result = $this->getOrderConnector()->updateTotal( $orderId, 1234 );

			//die( $orderId );

			\Wooclover\Core\Output::sendString( $orderId );
		} catch ( \Exception $ex ) {
			\Wooclover\Core\Output::send( array( "message" => $ex->getMessage() ), TRUE );
		}
	}

	public function getCustomers() {
		$customers = $this->getCustomerConnector()->getCustomers();

		\Wooclover\Core\Output::sendString( $customers );
	}

	public function getCustomer() {
		$request = \Wooclover\Core\Request::current();
		$customerId = $request->getProperty( 'customerId' );

		$customers = $this->getCustomerConnector()->getCustomer( $customerId );

		\Wooclover\Core\Output::sendString( $customers );
	}

	public function getItems() {
		$items = $this->getInventoryConnector()->getItems();

		\Wooclover\Core\Output::sendString( $items );
	}

	public function getCategories() {
		$categories = $this->getInventoryConnector()->getCategories();

		\Wooclover\Core\Output::sendString( $categories );
	}

	public function getItem() {
		$request = \Wooclover\Core\Request::current();
		$itemId = $request->getProperty( 'itemId' );

		$item = $this->getInventoryConnector()->getItem( $itemId );


		\Wooclover\Core\Output::sendString( $item );
	}

	private function getCustomerConnector() {

		return \Wooclover\CloverApi\CloverConnector::getCustomerConnector();
	}

	private function getOrderConnector() {

		return \Wooclover\CloverApi\CloverConnector::getOrderConnector();
	}

	private function getInventoryConnector() {

		return \Wooclover\CloverApi\CloverConnector::getInventoryConnector();
	}

}
