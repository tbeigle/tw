<?php

namespace Wooclover\Admin\Controllers;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Settings extends BaseController {

	public function init() {
		add_action( 'wp_ajax_wooclover_validateSettings', array( $this, 'isValid' ) );

		add_action( 'wp_ajax_wooclover_getSettings', array( $this, 'getSettings' ) );
		add_action( 'wp_ajax_wooclover_setSettings', array( $this, 'saveSettings' ) );

		add_action( 'wp_ajax_wooclover_getMerchant', array( $this, 'getMerchant' ) );
		add_action( 'wp_ajax_wooclover_setMerchant', array( $this, 'updateMerchant' ) );
	}

	public function isValid() {
		$registry = \Wooclover\Core\Settings\WcRegistry::instance();

		\Wooclover\Core\Output::send( array( 'valid' => $registry->isValidCredentials() ) );
	}

	public function getSettings() {

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();

		$options = $registry->getPublicOptions();
		foreach ( $options as &$option ) {
			if ( $option->getId() == "syncUser" ) {
				$option->setOptions( $this->getAdminUsers() );

				break;
			}
		}

		\Wooclover\Core\Output::sendCollection( $options );
	}

	public function getMerchant() {

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();

		$cloverMerchant = $registry->getCloverMerchant();
		$locale = $cloverMerchant->getLocale();
		$timezone = $cloverMerchant->getTimezone();
		if ( empty( $locale ) && empty( $timezone ) ) {
			//No locale or timezone, this should be an empty merchant
			//if we have a merchant id, get data from clover
			if ( $registry->getMerchantId() ) {
				$merchant = \Wooclover\CloverApi\CloverConnector::getMerchantConnector()->getMerchant( \Wooclover\Core\Conversor::TYPE_OBJECT );

				//We also need to saveit
				$registry->setCloverMerchant( $merchant );

				// Update again with the merchant object
				$registry->saveOptions();

				\Wooclover\Core\Output::send( $merchant );
			}
		} else {

			//there is already data, send 
			\Wooclover\Core\Output::send( $cloverMerchant );
		}

		//no data available
		\Wooclover\Core\Output::send( false );
	}

	public function updateMerchant() {
		$registry = \Wooclover\Core\Settings\WcRegistry::instance();

		if ( $registry->getMerchantId() ) {
			$merchant = \Wooclover\CloverApi\CloverConnector::getMerchantConnector()->getMerchant( \Wooclover\Core\Conversor::TYPE_OBJECT );

			$registry->setCloverMerchant( $merchant );

			// Update again with the merchant object
			$registry->saveOptions();

			\Wooclover\Core\Output::send( $merchant );
		}
	}

	public function saveSettings( $data ) {


		$request = \Wooclover\Core\Request::current();
		$registry = \Wooclover\Core\Settings\WcRegistry::instance();
		$options = $registry->getPublicOptions();

		// Read the settings from request
		$requestSettings = $request->getProperty( 'data' );

		foreach ( $options as $option ) {
			foreach ( $requestSettings as $setting ) {
				if ( $setting[ 'id' ] === $option->getId() ) {
					$option->setValue( $setting[ 'value' ] );
					continue;
				}
			}

			if ( $option->getId() == "syncUser" ) {
				$option->setOptions( $this->getAdminUsers() );
			}
		}

		$registry->setValidCredentials( false );

		// Update the public settings
		//$registry->saveOptions( $options );

		if ( $registry->getMerchantId() ) {
			//Test if merchant info and api token is valid.
			try {
				$merchant = \Wooclover\CloverApi\CloverConnector::getMerchantConnector()->getMerchant( \Wooclover\Core\Conversor::TYPE_OBJECT );

				$registry->setCloverMerchant( $merchant, $options );

				$registry->setValidCredentials( true );
				// Update again with the merchant object
				$registry->saveOptions();
			} catch ( \Exception $ex ) {
				//some error
				\Wooclover\Core\Output::sendCollection( $options, TRUE );
			}
		} else {
			//wee should remove merchant information
			$registry->setCloverMerchant( new \Wooclover\CloverApi\Domain\Merchant() );
			// Update again with the merchant object
			$registry->saveOptions();
		}


		\Wooclover\Core\Output::sendCollection( $options );
	}

	public function updateReferrals() {

		$request = \Wooclover\Core\Request::current();

		$entryManager = new \Wooclover\Core\EntryManager();
		$entryManager->updateReferrals();

		\Wooclover\Core\Output::send( true );
	}

	public function getView( $view ) {

		switch ( $view ) {
			case "settings":

				return $this->getAdminView( "settings", array() );
		}

		return false;
	}

	private function getAdminUsers() {
		$userManager = new \Wooclover\Core\UserManager();
		return $userManager->getAdministrators();
	}

}
