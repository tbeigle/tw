<?php

namespace Wooclover\CloverApi;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class NotificationConnector extends BaseConnector {

	public function __construct ( $merchantId, $apiToken ) {
		parent::__construct( $merchantId, $apiToken, "notifications", Domain\Notification::getClassName() );
	}

	public function send ( ) {
		
	}

}
