<?php

namespace Wooclover\Admin\Woo;

class ProductController {

	public function init () {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'addCloverTab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'cloverTabContent' ) );
		
		add_filter( 'manage_edit-product_columns', array( $this, 'showProductCloverIdColumn' ), 11 );
		add_action( 'manage_product_posts_custom_column', array( $this, 'showProductCloverIdColumnContent' ) );
	}

	public function addCloverTab ( $tabs ) {


		// Adds the new tab
		$tabs[ 'clover_tab' ] = array(
			'label' => __( 'Clover', 'woocommerce' ),
			'target' => 'general_product_clover',
			'callback' => array( $this, 'cloverTabContent' ),
			'class' => array( 'show_if_simple', 'show_if_variable', 'show_if_grouped' ),
		);

		return $tabs;
	}

	public function cloverTabContent () {

		global $post;
		 
		$cloverInfo = \Wooclover\Core\Utils::getCloverInfo( $post->ID );
		
		?>
		<div id="general_product_clover" class="panel woocommerce_options_panel" style="display:block">
			<div class="options_group">
				<p class="form-field  ">
					<label ><abbr title="Clover ID">Clover ID:</abbr></label>
					<span class="description"><?php echo $cloverInfo->cloverId ?></span>
				</p>

			</div>
			<div class="options_group">
				<p class="form-field  ">
					<label ><abbr title="Last Sync Date">Last Sync Date:</abbr></label>
					<span class="description"><?php echo $cloverInfo->lastSyncDate ?></span>
				</p>
			</div>
		</div>
		<?php
	}

	public function showProductCloverIdColumn ( $columns ) {

		$newColumns = array();
		foreach ( $columns as $key => $title ) {
			if ( $key == 'featured' ) { // in front of the Featured column
				$newColumns[ 'clover_id' ] = "Clover ID";
			}

			$newColumns[ $key ] = $title;
		}
		return $newColumns;
	}

	public function showProductCloverIdColumnContent ( $column ) {
		global $post;

		if ( $column == "clover_id" ) {
			$cloverInfo = \Wooclover\Core\Utils::getCloverInfo( $post->ID );
			echo $cloverInfo->cloverId ;
		}
	}
}
