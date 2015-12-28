<?php

namespace Wooclover\Core;

use Wooclover\CloverApi\CloverConnector;
use Wooclover\Core\MasterConnector;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CustomerManager {

	public function __construct () {
		
	}

	public function importFullCustomers () {
		$items = CloverConnector::getCustomerConnector()->getCustomers( \Wooclover\Core\Conversor::TYPE_OBJECT );

		if ( $items ) {
			foreach ( $items as $item ) {
				$result = MasterConnector::getCustomerConnector()->addCustomer( $item );

				if ( is_wp_error( $result ) ) {
					return $result;
				}
			}
		}
	}

}
