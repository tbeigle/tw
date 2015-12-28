<?php

namespace GFSeoMarketingAddOn\Core\Db;

abstract class Mapper {

	function __construct() {
		
	}

	protected function fillObject( $object, $row ) {

		\GFSeoMarketingAddOn\Core\FillerHelper::fillObject($object, $row);
	}
 

}