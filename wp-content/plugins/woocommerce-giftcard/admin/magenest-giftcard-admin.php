<?php
class Magenest_Giftcard_Admin {
	public function __construct() {
    add_action('admin_footer-edit.php', array($this,'add_bulk_action'))	;
    add_action('load-edit.php', array($this,'printpdf_action'));
    add_action('admin_notices',         array($this, 'admin_notices'));
    
    add_action ( 'woocommerce_admin_order_totals_after_discount',  array($this,'display_giftcard_on_order' ));
    
	}
	public function display_giftcard_on_order ( $order_id ) {
		global $wpdb;
		
		$tbl = $wpdb->prefix.'postmeta';
		$order = new WC_Order($order_id);
		$giftcard_code = get_post_meta($order_id , 'giftcard_code' , true);
		$giftcard_discount = get_post_meta($order_id , 'giftcard_discount' , true);
		if( $giftcard_discount > 0 ) {
			?>
			<tr>
				<td class="label"><?php _e( 'Gift Card Payment:  ' .$giftcard_code , 'woocommerce' ); ?>:</td>
				<td class="giftcardTotal">
					<div class="view"><?php echo wc_price( $giftcard_discount ); ?></div>
				</td>
			</tr>
			<?php
		}
	
	}
	public function add_bulk_action() {
		global $post_type;
		
		if($post_type == 'shop_giftcard') {
			?>
		    <script type="text/javascript">
		      jQuery(document).ready(function() {
		        jQuery('<option>').val('printpdf').text('<?php _e('Print PDF')?>').appendTo("select[name='action']");
		        jQuery('<option>').val('printpdf').text('<?php _e('Print PDF')?>').appendTo("select[name='action2']");
		      });
		    </script>
		    <?php
		  }	
	}
	
	public function printpdf_action() {
		global $typenow;
		$post_type = $typenow;
			
		if($post_type == 'shop_giftcard') {
		
			// get the action
			$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
			$action = $wp_list_table->current_action();
		
			$allowed_actions = array("printpdf");
			if(!in_array($action, $allowed_actions)) return;
		
			// security check
			check_admin_referer('bulk-posts');
		
			// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
			if(isset($_REQUEST['post'])) {
				$post_ids = array_map('intval', $_REQUEST['post']);
			}
		
			if(empty($post_ids)) return;
		
			// this is based on wp-admin/edit.php
			$sendback = remove_query_arg( array('printpdf', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
			if ( ! $sendback )
				$sendback = admin_url( "edit.php?post_type=$post_type" );
		
			$pagenum = $wp_list_table->get_pagenum();
			$sendback =esc_url( add_query_arg( 'paged', $pagenum, $sendback ));
		
			switch($action) {
				case 'printpdf':
		
					// if we set up user permissions/capabilities, the code might look like:
					//if ( !current_user_can($post_type_object->cap->export_post, $post_id) )
					//	wp_die( __('You are not allowed to export this post.') );
		
					$exported = 0;
					///////////////////////////////////////
					//////////////////////
					
					//foreach( $post_ids as $post_id ) {
							
					$pdf_file = 	$this->perform_export($post_ids) ;
					
							//wp_die( __('Error exporting post.') );
							
						//$exported++;
					//}
		            /////////////////////////////////
		            /////////////////////////////////
		            /////////////////////////////
					$sendback = esc_url(add_query_arg( array('printpdf' => $exported, 'file'=> $pdf_file ,'ids' => join(',', $post_ids) ), $sendback ));
					break;
						
				default: return;
			}
			//$this->admin_notices();
			$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
 $sendback = esc_url(add_query_arg( array('printpdf' => $exported, 'ids' => join(',', $post_ids) ), $sendback ));		   // $sendback = GIFTCARD_URL . '';
		    wp_redirect($sendback);
			exit();
		}
	}
	
	public function admin_notices() {
		global $post_type, $pagenow;
			
		if(  $post_type == 'shop_giftcard' && isset($_REQUEST['printpdf'])) {
			$message = sprintf( _n( 'Pdf exported.', '%s posts exported.', $_REQUEST['printpdf'] ), number_format_i18n( $_REQUEST['printpdf'] ) );
			$message = sprintf( 'PDF file is available for <a href="%s "> Download</a>', $_REQUEST['file']  );
			
			echo "<div class=\"updated\"><p>{$message}</p></div>";
		}
		}
		
		function perform_export($post_ids) {
			//,$send_from ,$send_to ,$balance,$code , $expiry_date  , $message
			
			
			set_include_path ( implode ( PATH_SEPARATOR, array (
		GIFTCARD_PATH . 'lib',
		get_include_path ()
		) ) );
		require_once 'Zend/Loader.php';
		
		require_once 'Zend/Loader/Autoloader.php';
		Zend_Loader_Autoloader::getInstance ();
		if (!class_exists('Zend_Pdf'))
		
			include_once GIFTCARD_PATH . 'lib/Zend/Pdf.php';
		$pdf = new Zend_Pdf ();
		$bold_font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA_BOLD );
		$font_regular = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
		$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
		foreach ($post_ids as $post_id ) {
			$send_to = get_post_meta($post_id,'send_to_name', true);
			$to_email = get_post_meta($post_id,'send_to_email', true);
			$message = get_post_meta($post_id,'message', true);
				
			$code = get_post_meta($post_id,'code', true);
			$balance = get_post_meta($post_id,'balance', true);
				
			$firstname = get_post_meta($post_id,'send_from_firstname', true);
				
			$lastname = get_post_meta($post_id,'send_from_last_name', true);
			$expiry_date  = get_post_meta($post_id,'expired_at', true);
			$order_id =get_post_meta($post_id,'order_id', true);
				
			$order = new WC_Order($order_id);
				
			$from_email = $order->billing_email;
			$from_name = $order->billing_first_name . ' ' . $order->billing_last_name;
			$send_from=$from_name;
		if ($expiry_date) {
			$date = new DateTime($expiry_date);
			$expiry_date = $date->format('d-m-Y');
		} 

		$replaces = array(
				'[giftcard_send_from]'	=>$send_from,
				'[giftcard_send_to]'	=>$send_to,
				'[giftcard_balance]' =>$balance,
				'[giftcard_code]' =>$code,
				'[giftcard_expiry_date]'=>$expiry_date
		);
		$pagewidth = get_option('magenestgc_pdf_width',321);
		
		$pageheight = get_option('magenestgc_pdf_height',214);
		$page_size = $pagewidth. ':' . $pageheight;
		
		//echo $page_size;
		//$page_size = '306:214';
		$page = $pdf->newPage ( $page_size );
		$image = GIFTCARD_PATH .'assets/giftcard.png';
		$template = get_option('magenestgc_pdf_background', 'giftcard.png');
		
		if (!$template) $template ='giftcard.png';
		$image = GIFTCARD_PATH . 'assets/' . $template;
		if (is_file ( $image )) {
			$image = Zend_Pdf_Image::imageWithPath ( $image );
			// $page->drawImage($image, 0,$pageheight , $pagewidth, 0);
			$page->drawImage ( $image, 0, 0, 306, 214 );
		}
		
		$page->setFont ( $font, 8 );
		//$page->setFillColor ( Zend_Pdf_Color_Html::color ( 'black' ) )->drawText ( 'hung', '10', '70', 'UTF-8' );
         
		///////////////////////////
		
				$lines  = array('send_from','send_to', 'balance', 'code' ,'expirydate');
		
				$pdf_line = array();
				foreach ($lines as $line) {
					$text = get_option('magenestgc_pdf_'.$line);
					$text = strtr($text, $replaces);
					//echo $text;
					//echo '<br>';
					$x = get_option('magenestgc_pdf_'.$line.'_x',20);
					//echo $x;
					//echo '<br>';
					$y = get_option('magenestgc_pdf_'.$line.'_y',20);
			//echo $y;
			//echo '<br>';
					if ($text && $x && $y) {
						$page->setFont ( $font, 10 );
						$page->setFillColor ( Zend_Pdf_Color_Html::color ('black' ) )->drawText ( $text, $x,$y, 'UTF-8' );
					}
				}
		////////////////////////////////
				$pdf->pages [] = $page;
				
		}
		
		// $file = $pdf->render ();
		$pdfData = $pdf->render ();
		$file_name = current_time('mysql').'giftcards.pdf';
		$file_path =GIFTCARD_PATH . $file_name;
		$file_url = GIFTCARD_URL . '/'.$file_name;
		$file_path = $pdf->save($file_path);
		return $file_url;
		}
	
}
return new Magenest_Giftcard_Admin();