<?php

namespace Wooclover\CloverApi;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

 class MerchantConnector extends BaseConnector{
	 
	 public function __construct ( $merchantId, $apiToken ) {
		 parent::__construct( $merchantId, $apiToken, "", Domain\Merchant::getClassName() );
	 }
	 
	 public function getMerchant( $type = \Wooclover\Core\Conversor::TYPE_STRING ){
		 return parent::get($this->getDefaultConversor( $type ), "properties");
		  
	 }
	
 }