<?php

namespace Wooclover\Core;

use \Wooclover\CloverApi\CloverConnector;
use \Wooclover\Core\MasterConnector;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class InventoryManager {

	public function __construct() {
		
	}

	public function importFullInventory() {
		$items = CloverConnector::getInventoryConnector()->getItems( \Wooclover\Core\Conversor::TYPE_OBJECT, array( 'categories' ) );
		
		if ( $items ) {
			foreach ( $items as $item ) {
				$result = MasterConnector::getInventoryConnector()->addProduct( $item );

				if ( is_wp_error( $result ) ) {
					return $result;
				}
			}
		}
	}

	public function exportFullInventory() {

		$products = CloverConnector::getInventoryConnector()->getProducts();

		foreach ( $products as $item ) {
			/* if ( $item->getId() && $item->getId() !== '26X160YY0V71R' ) {
			  continue;
			  } */

			$result = MasterConnector::getInventoryConnector()->addItem( $item );

			if ( is_wp_error( $result ) ) {
				return $result;
			} else {
				//update id if new				
				if ( ! $item->getId() ) {
					$response = json_decode( $result );
					$item->setId( $response->id );
					MasterConnector::getInventoryConnector()->updateCloverId( $item->getLocalId(), $item->getId() );
				}
			}
		}

		//return $products;
	}

	public function importProduct( $cloverId ) {

		$item = CloverConnector::getInventoryConnector()->getItem( $cloverId, \Wooclover\Core\Conversor::TYPE_OBJECT, array( 'categories' ) );

		$result = MasterConnector::getInventoryConnector()->addProduct( $item );

		return $result;
	}

	public function exportProduct( $id ) {
		
	}

}
