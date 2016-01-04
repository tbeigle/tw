<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Item extends CloverDomain {


	public function copy(){
		$item = new Item();
		$item->setId( $this->getId());
		
		return $item;
	}
}
