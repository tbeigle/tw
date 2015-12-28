<?php

namespace Wooclover\CloverApi;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CustomerConnector extends BaseConnector {

	public function __construct ( $merchantId, $apiToken ) {
		parent::__construct( $merchantId, $apiToken, "customers", Domain\Customer::getClassName() );
	}

	public function getCustomer ( $customerId, $type = \Wooclover\Core\Conversor::TYPE_STRING ) {
		return parent::get( $this->getDefaultConversor( $type ), "{$customerId}" );
	}

	public function getCustomers ( $type = \Wooclover\Core\Conversor::TYPE_STRING ) {
		return parent::get( $this->getDefaultConversor( $type, true ), "", array( 'emailAddresses', 'addresses' ) );
	}

	public function updateName ( $customerId, $firstName, $lastName ) {

		$result = parent::post( "{$customerId}/name", array( "firstName" => $firstName, "lastName" => $lastName ) );

		return $result;
	}

}
