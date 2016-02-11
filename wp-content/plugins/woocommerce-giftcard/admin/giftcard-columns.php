<?php
/**
 * Admin functions for the hn_followup post type.
 *
 */
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly

		                                    // Admin Columns
function magenest_giftcard_add_columns($columns) {
	$new_columns = (is_array ( $columns )) ? $columns : array ();
	// $new_columns['title'] ;
	//unset ( $new_columns ['date'] );
	unset ( $new_columns ['comments'] );
	
	// all of your columns will be added before the actions column on the Follow up email page
	
	$new_columns ["balance"] = __ ( 'Balance', GIFTCARD_TEXT_DOMAIN );
	$new_columns ["status"] = __ ( 'Status', GIFTCARD_TEXT_DOMAIN );
	$new_columns ["send_to_email"] = __ ( 'To email', GIFTCARD_TEXT_DOMAIN );
	$new_columns ["product_name"] = __ ( 'Product', GIFTCARD_TEXT_DOMAIN );
	//
	return $new_columns;
}
add_filter ( 'manage_edit-shop_giftcard_columns', 'magenest_giftcard_add_columns' );

/**
 * Define our custom columns shown in admin.
 * 
 * @param string $column        	
 *
 */
function magenest_giftcard_custom_columns($column) {
	global $post, $woocommerce;
	
	switch ($column) {
		
		case "send_to_email" :
			$to_email = get_post_meta ( $post->ID, 'send_to_email', true );
			
			echo '<span style="font-size: 0.9em">' . esc_html ( $to_email ) . '</div>';
			break;
		
		case "balance" :
			echo '<span style="font-size: 0.9em">' . esc_html ( get_post_meta ( $post->ID, 'balance', true ) ) . '</span></div>';
			break;
		case "product_name" :
			echo '<span style="font-size: 0.9em">' . esc_html ( get_post_meta ( $post->ID, 'product_name', true ) ) . '</span></div>';
			break;
		
		case "status" :
			$status = get_post_meta ( $post->ID, 'status', true );
			if ($status == 0) {
				echo __('Inactive' , GIFTCARD_TEXT_DOMAIN) ;
			} elseif ($status == 1) {
			echo __('Active' , GIFTCARD_TEXT_DOMAIN) ;
				
			}
			break;
	}
}

add_action ( 'manage_shop_giftcard_posts_custom_column', 'magenest_giftcard_custom_columns', 2 );