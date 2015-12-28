<?php

namespace Wooclover\Core\Interfaces;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

interface iInventoryConnector {
	
	/**
	 * 
	 * @param \Wooclover\CloverApi\Domain\Product $product
	 */
	function addProduct( \Wooclover\CloverApi\Domain\Product $product );
	
	/**
	 * 
	 * @param type $cloverId
	 */
	function getProductByCloverId( $cloverId );
	
	/**
	 * 
	 */
	function getProducts();
}