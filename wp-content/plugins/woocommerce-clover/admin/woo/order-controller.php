<?php

namespace Wooclover\Admin\Woo;

class OrderController {

	public function init () {
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'showOrderCloverIdColumn' ), 11 );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'showOrderCloverIdColumnContent' ) );
		add_action('add_meta_boxes',array($this,'addMetaBoxes'));
		
	}

	public function addMetaBoxes(){
		\add_meta_box( 'clover-info', 'Clover', array( $this, 'showCloverInfo' ), 'shop_order', 'normal' );
	}
	
	public function showCloverInfo () {
		global $post;
		
		$cloverInfo = \Wooclover\Core\Utils::getCloverInfo( $post->ID );
		 
		$link = $cloverInfo->synched?"<a target='_blank' href='https://www.clover.com/r/".$cloverInfo->cloverId."'>".$cloverInfo->cloverId."</a>":$cloverInfo->cloverId;
		
		?>
		<p>
			<strong>Clover ID:</strong>
			<span><?php echo $link; ?></span>
		</p>
		<p>
			<strong>Last Sync Date:</strong>
			<span><?php echo $cloverInfo->lastSyncDate; ?></span>
		</p>
		
		<?php

	}

	public function showOrderCloverIdColumn ( $columns ) {

		$newColumns = array();
		foreach ( $columns as $key => $title ) {
			if ( $key == 'order_actions' ) { // in front of the Billing column
				$newColumns[ 'clover_id' ] = "Clover ID";
			}

			$newColumns[ $key ] = $title;
		}
		return $newColumns;
	}

	public function showOrderCloverIdColumnContent ( $column ) {
		global $post;

		if ( $column == "clover_id" ) {
			$cloverInfo = \Wooclover\Core\Utils::getCloverInfo( $post->ID );
			echo $cloverInfo->cloverId ;
		}
	}

	

}
