<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class MasterConnector {

	private static $customerConnector = null;
	private static $orderConnector = null;
	private static $inventoryConnector = null;

	/**
	 * 
	 * @return \Wooclover\Core\Interfaces\iCustomerConnector
	 */
	public static function getCustomerConnector() {

		if ( ! self::$customerConnector ) {
			$type = self::getType( 'customer' );
			self::$customerConnector = new $type();
		}

		return self::$customerConnector;
	}

	/**
	 * 
	 * @return \Wooclover\Core\Interfaces\iOrderConnector
	 * 		   
	 */
	public static function getOrderConnector() {

		if ( ! self::$orderConnector ) {
			$type = self::getType( 'order' );

			self::$orderConnector = new $type();
		}
		return self::$orderConnector;
	}

	/**
	 * 
	 * @return \Wooclover\Core\Interfaces\iInventoryConnector
	 */
	public static function getInventoryConnector() {

		if ( ! self::$inventoryConnector ) {

			$type = self::getType( 'inventory' );
			self::$inventoryConnector = new $type();
		}

		return self::$inventoryConnector;
	}

	private static function getType( $type ) {

		$message = "Connector not defined";
		switch ( $type ) {
			case "inventory":
				$tag = 'wooclover\connectors\inventory';
				$message = "Inventory Connector type is required";
				break;
			case "customer":
				$tag = 'wooclover\connectors\customer';
				$message = "Customer Connector type is required";
				break;
			case "order":
				$tag = 'wooclover\connectors\order';
				$message = "Order Connector type is required";
				break;
		}

		if ( ! $tag ) {
			throw new \Exception( 'Tag connector can\'t be empty' );
		}

		$type = apply_filters( $tag, null );

		if ( ! $type ) {
			throw new \Exception( $message );
		}

		return $type;
	}

}
