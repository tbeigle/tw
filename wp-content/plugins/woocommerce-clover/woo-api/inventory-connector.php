<?php

namespace Wooclover\WooApi;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class InventoryConnector extends BaseConnector implements \Wooclover\Core\Interfaces\iInventoryConnector {

	/**
	 * Add a product into Woo
	 * @param \Wooclover\CloverApi\Domain\Product $product
	 * @return int | WP_Error
	 */
	public function addProduct( \Wooclover\CloverApi\Domain\Product $product ) {

		$post = array(
		    'post_author' => get_current_user_id(),
		    'post_content' => '',
		    'post_status' => "publish",
		    'post_title' => $product->getName(),
		    'post_parent' => '',
		    'post_type' => "product",
		);

		//First we need to ensure that the product doesn't exists
		$args = array(
		    'meta_key' => config::CloverIdMetaKey,
		    'meta_value' => $product->getId(),
		    'post_type' => 'product',
		    'posts_per_page' => 1
		);
		$posts = get_posts( $args );

		if ( $posts && count( $posts ) == 1 ) {

			$existingProduct = $posts[ 0 ];
			$postId = $existingProduct->ID;
		} else {
			//Create the woo product
			$postId = wp_insert_post( $post );

			if ( is_wp_error( $postId ) ) {
				return $postId;
			}
		}


		//wp_set_object_terms( $postId, 'Races', 'product_cat' );
		wp_set_object_terms( $postId, 'simple', 'product_type' );

		update_post_meta( $postId, '_visibility', 'visible' );
		update_post_meta( $postId, '_stock_status', 'instock' );
		update_post_meta( $postId, '_total_sales', '0' );
		update_post_meta( $postId, '_downloadable', 'no' );
		update_post_meta( $postId, '_virtual', 'no' );
		update_post_meta( $postId, '_regular_price', ( \Wooclover\Core\Utils::convertPrice( $product->getPrice() ) ) );
		update_post_meta( $postId, '_sale_price', "0" );
		update_post_meta( $postId, '_purchase_note', "" );
		update_post_meta( $postId, '_featured', "no" );
		update_post_meta( $postId, '_weight', "" );
		update_post_meta( $postId, '_length', "" );
		update_post_meta( $postId, '_width', "" );
		update_post_meta( $postId, '_height', "" );
		update_post_meta( $postId, '_sku', $product->getCode() );
		update_post_meta( $postId, '_product_attributes', array() );
		update_post_meta( $postId, '_sale_price_dates_from', "" );
		update_post_meta( $postId, '_sale_price_dates_to', "" );
		update_post_meta( $postId, '_price', ($product->getPrice() / 100 ) );
		update_post_meta( $postId, '_sold_individually', "" );
		update_post_meta( $postId, '_manage_stock', is_null( $product->getStockCount() ) ? "no" : "yes"  );
		update_post_meta( $postId, '_backorders', "no" );
		update_post_meta( $postId, '_stock', is_null( $product->getStockCount() ) ? '' : $product->getStockCount()  );

		update_post_meta( $postId, Config::CloverIdMetaKey, $product->getId() );
		update_post_meta( $postId, Config::LastSyncDate, current_time( 'mysql' ) );


		$productCategories = $product->getCategories();

		$termsId = array();
		//now work with categories
		if ( $productCategories ) {
			foreach ( $productCategories as $cat ) {
				$term = term_exists( $cat->getName(), Config::ProductCategoryTaxonomy );
				if ( $term === 0 || $term === NULL ) {
					$term = wp_insert_term( $cat->getName(), Config::ProductCategoryTaxonomy );
				}

				if ( ! is_wp_error( $term ) ) {
					$termsId[] = $term[ 'term_id' ];
				}
			}
			wp_set_post_terms( $postId, $termsId, Config::ProductCategoryTaxonomy );
		}else{
			//remove terms from post
			wp_set_post_terms( $postId, array(), Config::ProductCategoryTaxonomy );
		}

		return $postId;
	}

	public function getProductByCloverId( $cloverId ) {

		$args = array(
		    'meta_key' => Config::CloverIdMetaKey,
		    'meta_value' => $cloverId,
		    'post_type' => 'product',
		    'posts_per_page' => 1
		);
		$posts = get_posts( $args );

		if ( $posts && count( $posts ) == 1 ) {

			$existingProduct = $posts[ 0 ];
			return $existingProduct;
		}

		return false;
	}

	/**
	 * Get woo products
	 * @return \Wooclover\CloverApi\Domain\Product[]
	 */
	public function getProducts() {

		$args = array( 'post_type' => 'product' );
		$products = get_posts( $args );

		$cloverProducts = array();
		foreach ( $products as $product ) {
			$cloverProduct = new \Wooclover\CloverApi\Domain\Product();

			$cloverProduct->setLocalId( $product->ID );

			$cloverProduct->setName( $product->post_title );
			$price = get_post_meta( $product->ID, '_price', TRUE );
			if ( $price ) {
				$cloverProduct->setPrice( $price * 100 );
			}
			$cloverProduct->setCode( get_post_meta( $product->ID, '_sku', TRUE ) );
			if ( get_post_meta( $product->ID, '_manage_stock', TRUE ) == 'yes' ) {
				$cloverProduct->setStockCount( get_post_meta( $product->ID, '_stock', TRUE ) );
			}
			$cloverId = get_post_meta( $product->ID, Config::CloverIdMetaKey, TRUE );
			if ( $cloverId ) {
				$cloverProduct->setId( $cloverId );
			}


			$cloverProducts[] = $cloverProduct;
		}

		return $cloverProducts;
	}

	public function updateCloverId( $postId, $cloverId ) {
		return update_post_meta( $postId, Config::CloverIdMetaKey, $cloverId );
	}

}
