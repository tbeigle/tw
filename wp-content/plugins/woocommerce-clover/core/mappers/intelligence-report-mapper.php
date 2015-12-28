<?php



namespace Wooclover\Core\Mappers;
use \Wooclover\Core\Settings\Option,	Wooclover\Core\Settings\OptionType;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class IntelligenceReportMapper extends \GFSeoMarketingAddOn\Core\Db\WpOptionMapper {

	
	public function __construct() {

		parent::__construct( "gfSeoMark-intelligence-report" );
		
	}

	 
	public function getOptions(  ) {

		$existingsOptions = $this->getOption();
		
		$adminEmail = get_bloginfo( 'admin_email' );

		$defaultOptions = array(
			'sendReportTo' => new Option( "sendReportTo", "Send Report To", $adminEmail, '', OptionType::Input ),
			'enabled' => new Option( "enabled", "Enabled", true, '', OptionType::CheckBox ),
			'daysOfTheWeek' => new Option( "daysOfTheWeek", "Days of the week", array('monday','tuesday','wednesday','thursday', 'friday'), '', OptionType::Input )
			
		);
			
		
		if ( $existingsOptions ){
			foreach ( $existingsOptions as $option ){
				if ( isset( $defaultOptions[ $option->getName() ] ) && $defaultOptions[ $option->getName() ] !== "" ) {
					$defaultOptions[ $option->getName() ]->setValue( $option->getValue() );
				}
			}
		}
		
		return $defaultOptions;
		
	}
	
	public function getDaysOfTheWeek(){
		return $this->getValue('daysOfTheWeek');
	}
	
	/**
	 * Return a setting
	 * @param string $key
	 * @return null 
	 */
	public function getValue( $key ) {

		$options = $this->getOptions();
		
		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ]->getValue();
		}

		return null;
	}
	
	private function getSetting( $key ){
		$options = $this->getOptions();
		
		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}

		return false;
	}
	public function getSendReportTo(){
		return $this->getValue('sendReportTo');
	}
 
	
	public function saveOptions( $settings ){
		$this->updateOption($settings);
	}
	
	
	public function isEnabled(){
		
		$value = $this->getValue('enabled');
		
		if (  is_bool ( $value ) ){
			return $value;
		}
		
		if ( $value === "false" ) {
			return false;
		} else if ( $value === "true" ) {
			return true;
		} else {
			return false;
		}
	}

}