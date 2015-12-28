<?php

namespace Wooclover\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Synchronizer {

	public function init() {
		add_action( 'wp_ajax_wooclover_importCustomers', array( $this, 'importFullCustomers' ) );

		add_action( 'wp_ajax_wooclover_importInventory', array( $this, 'importFullInventory' ) );

		add_action( 'wp_ajax_wooclover_exportInventory', array( $this, 'exportFullInventory' ) );

		add_action( 'wp_ajax_wooclover_importOrders', array( $this, 'importFullOrders' ) );

		add_action( 'wp_ajax_wooclover_exportOrders', array( $this, 'exportNotSyncOrders' ) );
	}

	public function importFullCustomers() {
		try {
			$manager = new \Wooclover\Core\CustomerManager();
			$result = $manager->importFullCustomers();
			\Wooclover\Core\Output::send( array( "message" => 'Customers imported.' ) );
		} catch ( \Exception $ex ) {
			\Wooclover\Core\Output::send( array( "message" => $ex->getMessage() ), TRUE );
		}
	}

	public function importFullInventory() {

		try {
			$inventoryManager = new \Wooclover\Core\InventoryManager();
			$result = $inventoryManager->importFullInventory();
			if ( is_wp_error( $result ) ) {
				\Wooclover\Core\Output::send(
					array( "message" => $result->get_error_message() ), TRUE );
			} else {
				\Wooclover\Core\Output::send( array( "message" => 'Inventory imported.' ) );
			}
		} catch ( \Exception $ex ) {
			\Wooclover\Core\Output::send( array( "message" => $ex->getMessage() ), TRUE );
		}
	}

	public function exportFullInventory() {
		try {
			$manager = new \Wooclover\Core\InventoryManager();

			$result = $manager->exportFullInventory();
			if ( is_wp_error( $result ) ) {
				\Wooclover\Core\Output::send(
					array( "message" => $result->get_error_message() ), TRUE );
			} else {
				\Wooclover\Core\Output::send( array( "message" => 'Inventory exported.' ) );
			}
		} catch ( \Exception $ex ) {
			\Wooclover\Core\Output::send( array( "message" => $ex->getMessage() ), TRUE );
		}
	}

	public function exportNotSyncOrders() {
		try {
			$manager = new \Wooclover\Core\OrderManager();

			$result = $manager->exportNotSyncOrders();

			\Wooclover\Core\Output::send( array( "message" => 'Orders exported.' ) );
		} catch ( \Exception $ex ) {
			\Wooclover\Core\Output::send( array( "message" => $ex->getMessage() ), TRUE );
		}
	}

	public function importFullOrders() {

		try {
			$manager = new \Wooclover\Core\OrderManager();
			$manager->importFull();

			\Wooclover\Core\Output::send( array( "message" => 'Orders imported.' ) );
		} catch ( \Exception $ex ) {
			\Wooclover\Core\Output::send( array( "message" => $ex->getMessage() ), TRUE );
		}
	}

}
