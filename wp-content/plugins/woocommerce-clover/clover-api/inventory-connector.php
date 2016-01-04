<?php

namespace Wooclover\CloverApi;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class InventoryConnector extends BaseConnector {

	public function __construct( $merchantId, $apiToken ) {
		parent::__construct( $merchantId, $apiToken, '', Domain\Product::getClassName() );
	}

	public function getItem( $itemId, $type = \Wooclover\Core\Conversor::TYPE_STRING, $expands = FALSE ) {

		$response = parent::get( $this->getDefaultConversor( $type ), "items/{$itemId}", $expands );

		return $response;
	}

	public function getItems( $type = \Wooclover\Core\Conversor::TYPE_STRING, $expands = FALSE ) {
	
		return parent::get( $this->getDefaultConversor( $type, true ), "items", $expands );
	}

	public function addItem( Domain\Product $item, $type = \Wooclover\Core\Conversor::TYPE_OBJECT ) {
		$defaultConversor = $this->getDefaultConversor( $type );
		$defaultConversor->setInnerProperty( 'element' );

		$itemId = $item->getId();
		if ( $itemId ) {
			return parent::post( "items/{$itemId}", $item );
		} else {
			return parent::post( "items", $item );
		}
	}

	public function getCategories( $type = \Wooclover\Core\Conversor::TYPE_STRING ) {

		return parent::get( $this->getDefaultConversor( $type, true ), "categories" );
	}

	public function getCategory( $categoryId, $type = \Wooclover\Core\Conversor::TYPE_STRING ) {

		$response = parent::get( $this->getDefaultConversor( $type ), "categories/{$categoryId}" );

		return $response;
	}

}
