<?php

namespace Wooclover\CloverApi;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CloverConnector {

	private static $customerConnector = null;
	private static $orderConnector = null;
	private static $inventoryConnector = null;
	private static $merchantConnector = null;
	
	/**
	 * 
	 * @return \Wooclover\CloverApi\CustomerConnector
	 */
	public static function getCustomerConnector () {

		if ( !self::$customerConnector ) {
			$registry = \Wooclover\Core\Settings\WcRegistry::instance();
			self::$customerConnector = new \Wooclover\CloverApi\CustomerConnector( $registry->getMerchantId(), $registry->getApiToken() );
		}

		return self::$customerConnector;
	}
	
	/**
	 * 
	 * @return \Wooclover\CloverApi\MerchantConnector
	 */
	public static function getMerchantConnector () {

		if ( !self::$merchantConnector ) {
			$registry = \Wooclover\Core\Settings\WcRegistry::instance();
			self::$merchantConnector = new \Wooclover\CloverApi\MerchantConnector( $registry->getMerchantId(), $registry->getApiToken() );
		}

		return self::$merchantConnector;
	}


	/**
	 * 
	 * @return \Wooclover\CloverApi\OrderConnector
	 */
	public static function getOrderConnector () {

		if ( !self::$orderConnector ) {
			$registry = \Wooclover\Core\Settings\WcRegistry::instance();
			self::$orderConnector = new \Wooclover\CloverApi\OrderConnector( $registry->getMerchantId(), $registry->getApiToken() );
		}
		return self::$orderConnector;
	}

	/**
	 * 
	 * @return \Wooclover\CloverApi\InventoryConnector
	 */
	public static function getInventoryConnector () {

		if ( !self::$inventoryConnector ) {
			$registry = \Wooclover\Core\Settings\WcRegistry::instance();
			self::$inventoryConnector = new \Wooclover\CloverApi\InventoryConnector( $registry->getMerchantId(), $registry->getApiToken() );
		}

		return self::$inventoryConnector;
	}

}
