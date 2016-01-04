<?php

namespace Wooclover\Core\Settings;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

use Wooclover\Core\Settings\Option;

class WcRegistry extends WordpressRegistry {

	/**
	 *
	 * @var \Wooclover\Core\Settings\WcRegistry 
	 */
	private static $instance;

	protected function __construct() {

		parent::__construct();
	}

	/**
	 * 
	 * @return \Wooclover\Core\Settings\WcRegistry
	 */
	static function instance() {


		if ( ! isset( self::$instance ) ) {

			$adminEmail = get_bloginfo( 'admin_email' );

			$defaultOptions = array(
			    new Option(
				    "merchantId", "Merchant ID", '', '', OptionType::Input, false, true
			    ),
			    new Option(
				    "apiToken", "Api Token", '', '', OptionType::Password, false, true, 'The token need to have inventory write permissions'
			    ),
			    new Option(
				    "sendReportTo", "Send Report To", $adminEmail, '', OptionType::Input, true, false
			    ),
			    new Option(
				    "syncUser", "Sync User ID", '', '', OptionType::DropDown, false, true, 'This user will be associated to every imported product.'
			    ),
			    new Option(
				    "cloverMerchant", "Clover Merchant", '', '', OptionType::Input, true, false
			    ),
			    new Option(
				    "validCredentials", "Valid Credentials", FALSE, FALSE, OptionType::CheckBox, true, false
			    )
			);

			self::$instance = new self( );
			self::$instance->setOptions( $defaultOptions );
		}

		return self::$instance;
	}

	public function setCloverMerchant( \Wooclover\CloverApi\Domain\Merchant $merchant ) {

		$serializedMerchant = serialize( $merchant );
		$this->set( 'cloverMerchant', $serializedMerchant );
	}

	/**
	 * 
	 * @return \Wooclover\CloverApi\Domain\Merchant()
	 */
	public function getCloverMerchant() {

		$value = $this->getValue( 'cloverMerchant' );

		if ( ! $value ) {
			$value = new \Wooclover\CloverApi\Domain\Merchant();
		} else {
			$value = unserialize( $value );
		}

		return $value;
	}

	public function getSyncUserId() {
		$value = $this->getValue( 'syncUser' );

		if ( ! $value ) {
			throw new \Exception( 'Sync User ID is required!!' );
		}

		return $value;
	}

	public function getAdminViewHandlerUrl() {
		return "wp-admin/wooclover/view/";
	}

	public function getFullViewHandlerUrl() {
		return $this->getPluginUrl() . "wooclover/view/";
	}

	public function getMerchantId() {
		return $this->getValue( 'merchantId' );
	}

	public function getApiToken() {
		return $this->getValue( 'apiToken' );
	}

	public function isValidCredentials() {
		return $this->getValue( 'validCredentials' );
	}

	public function setValidCredentials( $value ) {
		$this->set( 'validCredentials', $value );
	}

	public function getLicensePlugin( \Wooclover\Settings\Registry $registry ) {

		$registedPlugins = $this->getRegisteredPluginsLicensing();

		if ( $registedPlugins && isset( $registedPlugins[ $registry->getPluginKey() ] ) ) {
			return $registedPlugins[ $registry->getPluginKey() ];
		}

		return '';
	}

	public function getRegisteredPluginsLicensing() {
		return $this->getValue( 'registeredPluginsLicensing' );
	}

	public function getTimeZone() {

		if ( ! parent::getTimeZone() ) {

			$current_offset = get_option( 'gmt_offset' );
			$tzstring = get_option( 'timezone_string' );

			if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
				if ( 0 == $current_offset )
					$tzstring = 'UTC+0';
				elseif ( $current_offset < 0 )
					$tzstring = 'UTC' . $current_offset;
				else
					$tzstring = 'UTC+' . $current_offset;
			}

			$allowed_zones = timezone_identifiers_list();

			if ( in_array( $tzstring, $allowed_zones ) ) {
				parent::setTimeZone( new \DateTimeZone( $tzstring ) );
			} else {
				parent::setTimeZone( new \DateTimeZone( 'UTC' ) );
			}
		}

		return parent::getTimeZone();
	}

	public function getDateFormat() {

		if ( ! parent::getDateFormat() ) {
			parent::setDateFormat( get_option( 'date_format' ) );
		}

		return parent::getDateFormat();
	}

	public function getEmailThemesPath() {
		return $this->getPluginDir() . 'assets/templates/email/';
	}

	public function getEmailThemesUrl() {
		return $this->getPluginUrl() . 'assets/templates/email/';
	}

	public function getEmailTemplate() {
		return $this->getValue( 'emailTemplate' );
	}

	public function getCurrentEmailThemePath() {
		return $this->getEmailThemesPath() . $this->getEmailTemplate() . ".html";
	}

	public function getWebSiteUrl() {
		return get_site_url();
	}

	public function getOrganizationName() {
		return get_bloginfo();
	}

	public function init() {

		parent::init();
	}

	public function isDevEnv() {
		return defined( 'DEV_ENV' ) && DEV_ENV;
	}

	public function getStylesUrl() {
		return $this->getPluginUrl() . "styles/";
	}

	public function getImagesUrl() {
		return $this->getPluginUrl() . "images/";
	}

	public function getScriptsUrl() {
		return $this->getPluginUrl() . "scripts/";
	}

	public function getBowerComponentUrl() {
		return $this->getPluginUrl() . "bower_components/";
	}

}
