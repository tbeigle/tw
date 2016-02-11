<?php
if (!class_exists('Magenest_Giftcard')) {
class Magenest_Giftcard {
	const ICONV_CHARSET = 'UTF-8';
	/** @public int Gift card id. */
	public $id;
	
	/** @public string Coupon code. */
	public $code;
	
	/** @public string product_id. */
	public $product_id;
	
	public $product_name;
	/** @public int  buyer id. */
	public $user_id;
	
	/** @public float balancee. */
	public $balance;
	
	/** @public float init_balance. */
	public $init_balance;
	
	/** @public string send_from_firstname. */
	public $send_from_firstname;
	
	/** @public string send_from_last_name. */
	public $send_from_last_name;
	
	/** @public string send_to_name. */
	public $send_to_name;
	
	/** @public string send_to_email. */
	public $send_to_email;
	
	/** @public string message. */
	public $message;
	
	/** @public string scheduled_send_time. */
	public $scheduled_send_time;
	
	/** @public string is_sent. */
	public $is_sent;
	
	/** @public string send_via. */
	public $send_via;
	
	/** @public string expired_at. */
	public $expired_at;
	
	
	/** @public string extra_info. */
	public $extra_info;
	
	/** @public string status. */
	public $status;
	
	private $post_type  = 'shop_giftcard';
	/** @public string error_message. */
	public $error_message;
	
	public $giftcard_custom_fields;
	public function __construct( $code='' ) {
		global $wpdb;
		
		$this->post_type = 'shop_giftcard';
	
	
	
			$giftcard_id 	= $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_giftcard' AND post_status = 'publish'",$code ) );
	
			if ( ! $giftcard_id )
				return;
	
			$giftcard            = get_post( $giftcard_id );
			$this->post_title   = apply_filters( 'magenest_giftcard_code', $giftcard->post_title );
	
			if ( empty( $giftcard ) ||$code !== $this->post_title )
				return;
	
			$this->id                   = $giftcard->ID;
			$this->giftcard_custom_fields = get_post_meta( $this->id );
				
	        $this->code = $this->post_title;
			$load_data = array(
					'product_id'              => 0,
					'product_name'              => '',
					'user_id'             => 0,
					'balance'                => 0,
					'init_balance'        => 0,
					'send_from_firstname'                => '',
					'send_from_last_name'       => '',
					'send_to_name'=>'',
					'send_to_email'=>'',
					'scheduled_send_time'     => '',
					'is_sent'                => 0,
					'send_via'                => '',
					'extra_info'           => '',
					'code'              => '',
					'message'         =>'',
					'status' =>0,
					'expired_at'         => '',
					
			);
	
			foreach ( $load_data as $key => $default )
				$this->$key = isset( $this->giftcard_custom_fields[ $key ][0] ) && $this->giftcard_custom_fields[ $key ][0] !== '' ? $this->giftcard_custom_fields[ $key ][0] : $default;
				
	//	do_action( 'magenest_giftcard_loaded', $this );
		//add_action("woocommerce_checkout_order_processed", array($this , 'generateGiftcardForOrder'),10);
		
	}
	
	public function is_valid($code) {
		$this->code = $code;
		global $wpdb;
		
		$this->post_type = 'shop_giftcard';
		
		
		
		$giftcard_id 	= $wpdb->get_var( $wpdb->prepare( apply_filters( 'magenest_giftcard_code_query', "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_giftcard' AND post_status = 'publish'" ), $this->code ) );
		
		if ( ! $giftcard_id )
			return;
		
		$giftcard            = get_post( $giftcard_id );
		$this->post_title   = apply_filters( 'magenest_giftcard_code', $giftcard->post_title );
		
		if ( empty( $giftcard ) ||$code !== $this->post_title )
			return;
		
		$this->id                   = $giftcard->ID;
		
		$this->giftcard_custom_fields = get_post_meta( $this->id );
		
		
		$this->code = $this->post_title;
		$load_data = array(
				'product_id'              => 0,
				'product_name'              => '',
				'user_id'             => 0,
				'balance'                => 0,
				'init_balance'        => 0,
				'send_from_firstname'                => '',
				'send_from_last_name'       => '',
				'send_to_name'=>'',
				'send_to_email'=>'',
				'scheduled_send_time'     => '',
				'is_sent'                => 0,
				'send_via'                => '',
				'extra_info'           => '',
				'code'              => '',
				'message'         =>'',
				'status' =>0,
				'expired_at'         => '',
					
		);
		
		foreach ( $load_data as $key => $default )
			$this->$key = isset( $this->giftcard_custom_fields[ $key ][0] ) && $this->giftcard_custom_fields[ $key ][0] !== '' ? $this->giftcard_custom_fields[ $key ][0] : $default;
		
		/////////////////////////////////////////
		$valid      = true;
		
		if (!$this->id ||$this->id <1 ||$this->id =='') {
			$this->error_message = __('Gift card code is not existed', GIFTCARD_TEXT_DOMAIN) ;
			$valid = false;
			
		} elseif ($this->status == 0) {
			
			$this->error_message = __('Gift card code is  not active', GIFTCARD_TEXT_DOMAIN) ;
			$valid = false;
			
		} elseif($this->expired_at) {
			
			if ( current_time( 'mysql' ) > $this->expired_at ) {
				$valid = false;
				$this->error_message = __('Gift card code is expired', GIFTCARD_TEXT_DOMAIN) ;
			}
		} elseif ($this->balance ==0 || $this->balance < 0) {
			$valid = false;
			$this->error_message = __('Gift card balance is zero', GIFTCARD_TEXT_DOMAIN);
		}
		
		return $valid;
	}

    public function send() {
        $fromEmail =  get_option ( 'woocommerce_email_from_name' ) ;
        $fromName =get_option('woocommerce_email_from_address');
        $this->send_mail_to_recipient($this->send_to_name, $this->send_to_email, $this->message, $this->post_title, $this->balance, $this->expired_at,$fromEmail, $fromName);

    }

	public function add_balance($amount,$giftcard_code) {
		global $wpdb;
		$giftcard = $wpdb->get_var( $wpdb->prepare( "
				SELECT $wpdb->posts.ID
				FROM $wpdb->posts
				WHERE $wpdb->posts.post_type = 'shop_giftcard'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->posts.post_title = '%s'
				", $giftcard_code ) );
		
		if ( $giftcard ) {
		
			$oldBalance = get_post_meta( $giftcard, 'balance' );
			
		
			$giftcard_balance = (float) $oldBalance[0] + (float)$amount;
		
			update_post_meta( $giftcard, 'balance', $giftcard_balance ); // Update balance of Giftcard
		}
	}
	/**
	 * analysis pattern of coupon which is defined by admin in setting panel
	 *
	 * @param string $pattern
	 * @return string
	 */
	public function generate_code($pattern) {
		$gen_arr = array ();
	
		preg_match_all ( "/\[[AN][.*\d]*\]/", $pattern, $matches, PREG_SET_ORDER );
		foreach ( $matches as $match ) {
			$delegate = substr ( $match [0], 1, 1 );
			$length = substr ( $match [0], 2, strlen ( $match [0] ) - 3 );
			if ($delegate == 'A') {
	
				$gen = $this->generate_string ( $length );
			} elseif ($delegate == 'N') {
	
				$gen = $this->generate_num ( $length );
			}
				
			$gen_arr [] = $gen;
		}
	
		foreach ( $gen_arr as $g ) {
			$pattern = preg_replace ( '/\[[AN][.*\d]*\]/', $g, $pattern, 1 );
		}
		return $pattern;
	}
	
	public function generate_string($length) {
		if ($length == 0 || $length == null || $length == '')
			$length = 5;
		$c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$rand = '';
		for($i = 0; $i < $length; $i ++) {
			$rand .= $c [rand () % strlen ( $c )];
		}
	
		return $rand;
	}
	
	/**
	 * generate arbitratry string contain number digit
	 *
	 * @param int $length
	 * @return string
	 */
	public function generate_num($length) {
		if ($length == 0 || $length == null || $length == '')
			$length = 5;
		$c = "0123456789";
		$rand = '';
		for($i = 0; $i < $length; $i ++) {
			$rand .= $c [rand () % strlen ( $c )];
		}
		return $rand;
	}
	
	/**
	 * Returns the error_message string
	 *
	 * @access public
	 * @return string
	 */
	
	public function get_error_message() {
		return $this->error_message;
	}
	
	public function generateGiftcard($code ='',$data) {
	
		$pattern = get_option('magenest_giftcard_code_pattern');
		if (!$code) $code = $this->generate_code($pattern);
		$post_id = -1;
	
		$author_id = 1;
		$title = $code;
	
		if( !$this->getGiftcardByCode($code)) {
	
			$post_id = wp_insert_post(
					array(
							'comment_status'	=>	'closed',
							'ping_status'		=>	'closed',
							'post_author'		=>	$author_id,
							'post_title'		=>	$title,
							'post_status'		=>	'publish',
							'post_type'		=>	$this->post_type
					)
			);
	
		} else {
	
			$post_id = -2;
	
		} 
		
		$data['code'] = $code;
		
		if ($post_id > 0) {
			$this->updateGiftcard($post_id, $data);
		}
		return $post_id;
	}
	public function calculateExpiryDate() {
		$time = current_time('mysql');
		$current_date_time = new DateTime($time);
		$timespan = get_option('magenest_giftcard_timespan');
		if (!$timespan)
			return;
		//$current_date_time->add ( $interval );
		$modify='+';
		$modify .= floatval($timespan);
		$modify .= ' days';
		$current_date_time->modify($modify);
		$format = 'Y-m-d H:i:s';
		
		return $current_date_time->format ( $format );
	}
	public function updateGiftcard($post_id, $data) {
	$load_data = array(
			        'magenest_giftcard_order_id' =>0,
					'product_id'              => 0,
					'product_name'              => '',
					'user_id'             => 0,
					'balance'                => 0,
					'init_balance'        => 0,
					'send_from_firstname'                => '',
					'send_from_last_name'       => '',
					'send_to_name'=>'',
					'send_to_email'=>'',
					'scheduled_send_time'     => '',
					'is_sent'                => 0,
					'send_via'                => '',
					'extra_info'           => '',
					'code'              => '',
					'message'         =>'',
					'status' =>0,
					'expired_at'         => '',
					
			);
		foreach ( $load_data as $key => $default ){
			$value = isset ( $data [$key] ) && $data [$key] != '' ? $data [$key] : $default;
			update_post_meta( $post_id, $key, $value );
				
		}
	}
	public static  function getGiftcardByCode($code) {
		global $wpdb;
		$giftcard_id 	= $wpdb->get_var( $wpdb->prepare( apply_filters( 'magenest_giftcard_code_query', "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_giftcard' AND post_status = 'publish'" ),$code ) );
		
		if ($giftcard_id) {
			return $giftcard_id;
		} else {
			return false;
		}
		
		
	}
	public function generateGiftcardForOrder($order_id) {
		$order = wc_get_order($order_id);
	
		/* @var $order WC_Order */
		if (sizeof ( $order->get_items () ) > 0) {
				
			foreach ( $order->get_items () as $item ) {
				$_product     = apply_filters( 'magenest_giftcard_order_item_product', $order->get_product_from_item( $item ), $item );
	
				/* @var $_product WC_Product */
	
				$giftcard_balance = $_product->get_price();
				$is_giftcard = get_post_meta ( $_product->id, '_giftcard', true );
				if($is_giftcard=='yes') {
					$to_name ='';
					$to_email ='';
					$message ='';
					//$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
					$item_meta = $item;
					if (isset($item_meta['To Name']) && isset($item_meta['To Name'][0])) {
						$to_name = $item_meta['To Name'][0];
					}
	
					if (isset($item_meta['Message']) && isset($item_meta['Message'][0])) {
						$message = $item_meta['Message'][0];
					}
	
					if (isset($item_meta['To Email']) && isset($item_meta['To Email'][0])) {
						$to_email = $item_meta['To Email'][0];
					}
	
					/* save gift card and send notification email */
					$gift_card_data = array (
							'product_id' => $_product->id,
							'product_name' => $_product->get_title (),
							'user_id' => $order->get_user_id (),
							'balance' => $giftcard_balance,
							'init_balance' => $giftcard_balance,
							'send_from_firstname' => '',
							'send_from_last_name' => '',
							'send_to_name' => $to_name,
							'send_to_email' => $to_email,
							'scheduled_send_time' => '',
							'is_sent' => 0,
							'send_via' => '',
							'extra_info' => '',
							'code' => '',
							'message' => $message,
							'status' => 0,
							'expired_at' => ''
					)
					;
					
					$this->generateGiftcard ( $code = '', $gift_card_data );
				}
			}
		}
	}
	
	public function printPdf($send_from ,$send_to ,$balance,$code , $expiry_date  , $message) {
		//echo "send from  ". $send_from ;
		//echo "send to ". $send_to;
		//echo "balance ". $balance;
		//echo 'code ' . $code;
		//echo '$expiry_date ' .$expiry_date;
		//$send_from = 'John Doe';
		//$send_to = 'Lisa Vidal';
		//$code = 'thxH0xjh93';
		set_include_path ( implode ( PATH_SEPARATOR, array (
		GIFTCARD_PATH . 'lib',
		get_include_path ()
		) ) );
		require_once 'Zend/Loader.php';
		
		require_once 'Zend/Loader/Autoloader.php';
		Zend_Loader_Autoloader::getInstance ();
		if (!class_exists('Zend_Pdf'))
		
		include_once GIFTCARD_PATH . 'lib/Zend/Pdf.php';
		
		if (!class_exists('Zend_Barcode'))
		include_once GIFTCARD_PATH . 'lib/Zend/Zend_Barcode.php';
		
		$pdf = new Zend_Pdf ();
		$bold_font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA_BOLD );
		$font_regular = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
		$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
		
		if ($expiry_date) {
			$date = new DateTime($expiry_date);
			$expiry_date = $date->format('d-m-Y');
		} 

		$replaces = array(
				'[giftcard_send_from]'	=>$send_from,
				'[giftcard_send_to]'	=>$send_to,
				'[giftcard_balance]' =>$balance,
				'[giftcard_code]' =>$code,
				'[giftcard_message]'=>$message,
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
			$page->drawImage ( $image, 0, 0, $pagewidth, $pageheight );
		}
		
		$page->setFont ( $font, 8 );
		//$page->setFillColor ( Zend_Pdf_Color_Html::color ( 'black' ) )->drawText ( 'hung', '10', '70', 'UTF-8' );
         
		///////////////////////////
		
				$lines  = array('send_from','send_to', 'balance', 'code' ,'expirydate', 'message');
		
				$pdf_line = array();
				foreach ($lines as $line) {
					$text = get_option('magenestgc_pdf_'.$line);
					$text = strtr($text, $replaces);
					$x = get_option('magenestgc_pdf_'.$line.'_x',20);
					$y = get_option('magenestgc_pdf_'.$line.'_y',20);
					if ($text && $x && $y && $line != 'message') {
						$page->setFont ( $font, 10 );
						$page->setFillColor ( Zend_Pdf_Color_Html::color ('black' ) )->drawText ( $text, $x,$y, 'UTF-8' );
					} elseif($text && $x && $y && $line == 'message') {
						$character_per_line = get_option('magenestgc_pdf_message_linelimit');
						
						error_log('line page '. $character_per_line);
						if (!$character_per_line) $character_per_line = 20;
						
						$result = self::str_split($text,$character_per_line,true, false);
						error_log($text);
						
						if (count($result) > 0) {
							error_log('co day message');
							$i  = 0;
							foreach ($result as $text_line) {
								$i = $i + 1;
								$y = $y - 10;
								$page->setFillColor ( Zend_Pdf_Color_Html::color ('black' ) )->drawText ( $text_line, $x,$y, 'UTF-8' );
								
							}
						}
						
					}
				}
		////////////////////////////////
				$barcode_x = get_option('magenestgc_pdf_barcode_x');
				$barcode_y = get_option('magenestgc_pdf_barcode_y');
				if ($barcode_x && $barcode_y) {
					$barcodeOptions = array('text' => $code,'factor'=>1);
					// 		// No required options
					
					$rendererOptions = array();
					
					// Draw the barcode in a new image
                    $barcode ='code128';
					$imageResource = Zend_Barcode::factory($barcode, 'image', $barcodeOptions, $rendererOptions ,true)->draw();
					
					// 		$output =  Mage::getBaseDir('var').DS.'
					$output =  GIFTCARD_PATH."barcode.jpg";
						
					try {
					imagejpeg($imageResource, $output);
						
					list($width, $height) = getimagesize($output);
					
					
			
					//draw image 
					$image = Zend_Pdf_Image::imageWithPath($output);
					$x2 = $barcode_x + $width/2;
					$y2 = $barcode_y + $height/2;
					$page->drawImage($image, $barcode_x, $barcode_y, $x2, $y2);
					} catch (Exception $e)  {
						error_log($e->getMessage());
					}
	 				//$page->drawImage($image,  $x2, $y2,$barcode_x, $barcode_y);
				}
				$pdf->pages [] = $page;
		
		// $file = $pdf->render ();
		$pdfData = $pdf->render ();
		$filePath = GIFTCARD_PATH.'export/'. 'giftcard.pdf';
		$pdf->save($filePath);
		return $filePath;
	}
	
	
	public function extract_email_address($string) {
	
		// $string='<a href="mailto:luuthuy205@gmail.com">luuthuy205@gmail.com</a>';
		if (! is_email ( $string )) {
			preg_match ( '/\>(.*)\</', $string, $matches );
			if (isset ( $matches [1] ))
				return $matches [1];
		} else {
			return $string;
		}
	}
	/**
	 * @param int $order_id
	 */
	public static  function strlen($string)
	{
		return iconv_strlen($string, self::ICONV_CHARSET);
	}
	public static function str_split($str, $length = 1, $keepWords = false, $trim = false, $wordSeparatorRegex = '\s')
	{
		$result = array();
		$strlen = self::strlen($str);
		if ((!$strlen) ||  ($length <= 0)) {
			return $result;
		}
		// trim
		if ($trim) {
			$str = trim(preg_replace('/\s{2,}/siu', ' ', $str));
		}
		// do a usual str_split, but safe for our encoding
		if ((!$keepWords) || ($length < 2)) {
			for ($offset = 0; $offset < $strlen; $offset += $length) {
				$result[] = substr($str, $offset, $length);
			}
		}
		// split smartly, keeping words
		else {
			$split = preg_split('/(' . $wordSeparatorRegex . '+)/siu', $str, null, PREG_SPLIT_DELIM_CAPTURE);
			$i        = 0;
			$space    = '';
			$spaceLen = 0;
			foreach ($split as $key => $part) {
				if ($trim) {
					// ignore spaces (even keys)
					if ($key % 2) {
						continue;
					}
					$space    = ' ';
					$spaceLen = 1;
				}
				if (empty($result[$i])) {
					$currentLength = 0;
					$result[$i]    = '';
					$space         = '';
					$spaceLen      = 0;
				}
				else {
					$currentLength = self::strlen($result[$i]);
				}
				$partLength = self::strlen($part);
				// add part to current last element
				if (($currentLength + $spaceLen + $partLength) <= $length) {
					$result[$i] .= $space . $part;
				}
				// add part to new element
				elseif ($partLength <= $length) {
					$i++;
					$result[$i] = $part;
				}
				// break too long part recursively
				else {
					foreach (self::str_split($part, $length, false, $trim, $wordSeparatorRegex) as $subpart) {
						$i++;
						$result[$i] = $subpart;
					}
				}
			}
		}
		// remove last element, if empty
		if ($count = count($result)) {
			if ($result[$count - 1] === '') {
				unset($result[$count - 1]);
			}
		}
		// remove first element, if empty
		if (isset($result[0]) && $result[0] === '') {
			array_shift($result);
		}
		return $result;
	}
	
	public function active_giftcard($order_id) {
		global $wpdb;
		
		$tbl = $wpdb->prefix.'postmeta';
		$order = new WC_Order($order_id);
		
		$from_email = $order->billing_email;
		$from_name = $order->billing_first_name . ' ' . $order->billing_last_name;
		
	//	$order->billing_email
	   //select all gift cards which have the order id like this 
 			$query = "select * from $tbl where meta_key = %s and meta_value=%s";
 			$args = array('magenest_giftcard_order_id', $order_id);
 			$results = $wpdb->get_results($wpdb->prepare($query, $args), ARRAY_A);
 			
 			if (!empty($results)) {
 				foreach ($results as $row) {
 					$post_id = $row['post_id'];
 					update_post_meta($post_id, 'status', 1);
 					
 					//send email to recipient
 					$to_name = get_post_meta($post_id,'send_to_name', true);
 					$to_email = get_post_meta($post_id,'send_to_email', true);
 					$to_message = get_post_meta($post_id,'message', true);
 					
 					$code = get_post_meta($post_id,'code', true);
 					//echo 'Code is '.$code;
 					$balance = get_post_meta($post_id,'balance', true);
 					$firstname = get_post_meta($post_id,'send_from_firstname', true);
 					$lastname = get_post_meta($post_id,'send_from_last_name', true);
 					$expire_at  = get_post_meta($post_id,'expired_at', true);
 					
 					/* validate the send to email */
 					if (!is_email($to_email)) {
 						$to_email = $this->extract_email_address($to_email);
 					}

                    /**
                     * for jeeta
                     *
                     */


 					$this->send_mail_to_recipient($to_name, $to_email, $to_message, $code, $balance, $expire_at, $from_email, $from_name);
 				    $message = 'to name '. $to_name. " to email " . $to_email . "  to message " . $to_message. ' code '.$code . " balance " .$balance . ' $expire_at  '  . $expire_at . 'from _email '.$from_email. ' $from_name '.$from_name;
 				    update_post_meta($post_id, 'is_sent', 1);
 					
 				}
 			} 
		
	}
	
	public function send_mail_to_recipient($to_name,$to_email,$to_message,$code, $balance,$expired_at,  $from_email, $from_name) {
		$headers = array ();
		$headers [] = "Content-Type: text/html";
		$headers [] = 'From: ' . get_option ( 'woocommerce_email_from_name' ) . ' <' . get_option('woocommerce_email_from_address') . '>';
		$headers [] = 'Bcc: ' .$from_name . ' <' . $from_email . '>';
		$to = $to_name. '<' . $to_email . '>';
		
		$subject = get_option('magenest_giftcard_to_subject');
		
		$content = get_option('magenest_giftcard_to_content');
        $post_id = $this->getGiftcardByCode($code);

        $product_id = get_post_meta($post_id, 'product_id' ,true);

        $product_image =  get_the_post_thumbnail($product_id, 'medium');
		$replaces = array(
				'{{from_name}}' => $from_name,
				'{{to_name}}' => $to_name,
				'{{to_email}}' =>$to_email,
				'{{message}}' => $to_message,
				'{{code}}' => $code,
				'{{balance}}' => $balance,
				'{{expired_at}}' => $expired_at,
                '{{product_image}}'=> $product_image,
				'{{store_url}}' => get_permalink ( woocommerce_get_page_id ( 'shop' ) ),
				'{{store_name}}' => get_bloginfo ( 'name' )
		);
		
		$message = strtr($content, $replaces);
		
		$attach_pdf_option = get_option('magenest_giftcard_to_pdf' ,'yes');
			if ($attach_pdf_option == 'yes') {
				$attachments = array ();
				$pdf = $this->printPdf ( $from_name, $to_name, $balance, $code, $expired_at, $to_message );
				$attachments [] = $pdf;
				add_filter ( 'wp_mail_content_type', array ( $this, 'set_html_content_type' ) );
				wp_mail ( $to, $subject, $message, $headers, $attachments );
				
			} else {
				add_filter ( 'wp_mail_content_type', array ( $this, 'set_html_content_type' ) );
				wp_mail ( $to, $subject, $message, $headers );
				
			}
		
	}
	
	public function set_html_content_type() {
		return 'text/html';
	}
}

return new Magenest_Giftcard();
}