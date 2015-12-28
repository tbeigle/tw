<?php

namespace Wooclover\Core\Interfaces;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

interface iCustomerConnector {
	
	function addCustomer ( \Wooclover\CloverApi\Domain\Customer $customer );
	
}