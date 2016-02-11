<?php
/**
 * WooCommerce Gift Card Settings
*
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (!class_exists('WC_Settings_Page'))
	include_once dirname (GIFTCARD_PATH) . '/woocommerce/includes/admin/settings/class-wc-settings-page.php';
if ( ! class_exists( 'Magenest_Giftcard_Settings' ) ) :

/**
 * WC_Settings_Accounts
*/
class Magenest_Giftcard_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'giftcard';
		$this->label = __( 'Gift Cards',  GIFTCARD_TEXT_DOMAIN  );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 200 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		
		$options = apply_filters( 'woocommerce_giftcard_settings', array(

				array( 'title' 		=> __( 'Processing Gift card',  GIFTCARD_TEXT_DOMAIN  ), 'type' => 'title', 'id' => 'giftcard_processing_options_title' ),

				array(
						'title'         => __( 'Apply gift card for shipping free',  GIFTCARD_TEXT_DOMAIN  ),
						'desc'          => __( 'Allow customers to pay shipping fee with  gift card.',  GIFTCARD_TEXT_DOMAIN  ),
						'id'            => 'giftcard_apply_for_shipping',
						'default'       => 'no',
						'type'          => 'checkbox',
						'autoload'      => false
				),
				array(
						'title'         => __( 'Apply gift card for tax',  GIFTCARD_TEXT_DOMAIN ),
						'desc'          => __( 'Allow customers to pay for tax with their gift card.', GIFTCARD_TEXT_DOMAIN  ),
						'id'            => 'magenest_enable_giftcard_charge_tax',
						'default'       => 'no',
						'type'          => 'checkbox',
						'autoload'      => true
				),
				
				array(
						'title'         => __( 'Apply gift card for fee',  GIFTCARD_TEXT_DOMAIN ),
						'desc'          => __( 'Allow customers to pay for fees with their gift card.',  GIFTCARD_TEXT_DOMAIN  ),
						'id'            => 'magenest_enable_giftcard_charge_fee',
						'default'       => 'no',
						'type'          => 'checkbox',
						'autoload'      => true
				),
				array(
						'title'         => __( 'Enable use gift card to buy other gift card',  GIFTCARD_TEXT_DOMAIN  ),
						'id'            => 'magenest_giftcard_buy_other_giftcard',
						'default'       => 'no',
						'type'          => 'checkbox',
						'autoload'      => false
				),
				array(
						'title'         => __( 'Active giftcard and send notification email when status of order',  GIFTCARD_TEXT_DOMAIN  ),
						'id'            => 'magenest_giftcard_active_when',
						'type'          => 'select',
						'options' => array (
								'completed' => __ ( 'Completed', GIFTCARD_TEXT_DOMAIN ),
								'processing' => __ ( 'Processing', GIFTCARD_TEXT_DOMAIN ),
								'on-hold' => __ ( 'On hold', GIFTCARD_TEXT_DOMAIN ),
								'pending' => __ ( 'Pending', GIFTCARD_TEXT_DOMAIN ),
						),
						'autoload'      => false
				),
			
				
				array( 'type' => 'sectionend', 'id' => 'giftcard_generate_option'),

				array( 'title' 		=> __( 'Gift card ',  GIFTCARD_TEXT_DOMAIN  ), 'type' => 'title', 'id' => 'giftcard_options_title' ),

				array(
						'name'     => __( 'Default expire time span', GIFTCARD_TEXT_DOMAIN ),
						'desc'     => __( 'If you set this to 30 , the gift card will expire after 30 day from created time. Leave blank or 0 if you do not want to use it', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenest_giftcard_timespan',
						'default'  => '0', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Gift card code pattern', GIFTCARD_TEXT_DOMAIN ),
						'desc'     => __( 'You can use [A4] for 4 random characters, use [N5] for 5 random digit and so on', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenest_giftcard_code_pattern',
						'default'  => 'Magenest[A2]xyz[N4]', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array( 'type' => 'sectionend', 'id' => 'giftcard_generate_option'),
				
				array( 'title' 		=> __( 'Gift card send friend option',  GIFTCARD_TEXT_DOMAIN  ), 'type' => 'title', 'id' => 'giftcard_options_title' ),

				array(
						'name'     => __( 'Email subject', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenest_giftcard_to_subject',
						'default'  => 'You have receveid a gift card from friend', 
						'type'     => 'textarea',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Email content', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenest_giftcard_to_content',
						'default'  => 'You have receveid a gift card from friend', 
						'type'     => 'textarea',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Attach pdf gift card', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenest_giftcard_to_pdf',
						'type'     => 'checkbox',
				),

				array( 'type' => 'sectionend', 'id' => 'giftcard_options'),
				
				array( 'title' 		=> __( 'PDF Setting ',  GIFTCARD_TEXT_DOMAIN  ), 'type' => 'title', 'id' => 'giftcard_pdf_options_title' ),

				array(
						'name'     => __( 'File name  of pdf background ( jpg,png file)', GIFTCARD_TEXT_DOMAIN ),
						'desc'     => 'Enter the name of background of pdf here . Using FTP to upload an image to folder wp-content/plugins/woocommerce-giftcard/assets and enter the name of the file here.E.g thegiftcard.png',
						
						'id'       => 'magenestgc_pdf_background',
						'default'  => '', 
						'type'     => 'text',
						'desc_tip' =>  false,
				),
				array(
						'name'     => __( 'PDF width', GIFTCARD_TEXT_DOMAIN ),
						'desc'     => __( 'Enter PDF width in point unit', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_width',
						'default'  => '321', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'PDF height', GIFTCARD_TEXT_DOMAIN ),
						'desc'     => __( 'Enter PDF height in point unit', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_height',
						'default'  => '214', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'send from', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_send_from',
						'default'  => 'From [giftcard_send_from]', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'send from x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_send_from_x' ,
						'default'  => '100', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'send from x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_send_from_y' ,
						'default'  => '100', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'send to', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_send_to',
						'default'  => 'To [giftcard_send_to]', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'send to x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_send_to_x' ,
						'default'  => '100', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'send to y', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_send_to_y' ,
						'default'  => '100', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Balance', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_balance',
						'default'  => 'Value: [giftcard_balance]', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Balance  x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_balance_x' ,
						'default'  => '208', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Balance y', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_balance_y' ,
						'default'  => '153', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Gift card code', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_code',
						'default'  => 'Value: [giftcard_code]', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Gift card  Code  x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_code_x' ,
						'default'  => '208', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Gift card  Code y', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_code_y' ,
						'default'  => '153', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Expiry date', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_expirydate',
						'default'  => 'Expiry date : [giftcard_expiry_date]', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Expiry date  x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_expirydate_x' ,
						'default'  => '100', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Expiry date y', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_expirydate_y' ,
						'default'  => '100', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Message', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_message',
						'default'  => 'Message: [giftcard_message]', 
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Message  x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_message_x' ,
						
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Message y', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_message_y' ,
					
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Number of character in a line', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_message_linelimit' ,
					     'des'     => __('If the message is too long, you may want to limit 40 character in a line for example') ,
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				
				array(
						'name'     => __( 'Barcode  x', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_barcode_x' ,
						
						'type'     => 'text',
						'desc_tip' =>  true,
				),
				array(
						'name'     => __( 'Barcode y', GIFTCARD_TEXT_DOMAIN ),
						'id'       => 'magenestgc_pdf_barcode_y' ,
						
						'type'     => 'text',
						'desc_tip' =>  true,
				),

				array( 'type' => 'sectionend', 'id' => 'giftcard_pdf_options'),

		));



		return $options;

	}
}

endif;

return new Magenest_Giftcard_Settings();
