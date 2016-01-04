<?php

namespace Wooclover\Core\Settings;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class WordpressRegistry extends \Wooclover\Core\Settings\Registry {

	/**
	 *
	 * @var WordpressRegistry 
	 */
	private $settingKey = false;

	/**
	 * 
	 * @param \Maven\Settings\Option[] $defaultValues
	 */
	protected function __construct () {
		
	}

	public function reset () {

		delete_option( $this->getSettingKey() );
	}

	/**
	 * 
	 * @param \Maven\Settings\Option[] $options
	 */
	public function saveOptions ( $options = false ) {

		if ( $options ) {
			//Save the options in the WP table
			update_option( $this->getSettingKey(), $options );

			$this->setOptions( $options );
		}else{
			update_option( $this->getSettingKey(), $this->getOptions() );
		}
	}

	private function getSettingKey () {

		//We need to sanitize the key just one time
		if ( !$this->settingKey ) {
			$this->settingKey = sanitize_key( $this->getPluginName() ) . "-settings";
		}

		return $this->settingKey;
	}

	public function getPluginKey () {

		if ( !parent::getPluginKey() ) {
			parent::setPluginKey( sanitize_key( $this->getPluginName() ) );
		}

		return parent::getPluginKey();
	}

	public function getAbsPath () {
		return ABSPATH;
	}

	public function getWpIncludesPath ( $full = false ) {

		if ( $full ) {
			return ABSPATH . WPINC . "/";
		}

		return WPINC;
	}

	public function init () {

		// Get the options from the db
		$existingsOptions = get_option( $this->getSettingKey() );

		// Get the saved options in the object
		$options = $this->getOptions();


		// If options exists we need to merge them with the default ones
		if ( $existingsOptions ) {
			foreach ( $existingsOptions as $option ) {
				if ( isset( $options[ $option->getName() ] ) && $options[ $option->getName() ] !== "" ) {
					$options[ $option->getName() ]->setValue( $option->getValue() );
				}
			}
		}


		parent::setOptions( $options );
	}

	function getEmailNotificationsTo () {
		return false;
	}

}
