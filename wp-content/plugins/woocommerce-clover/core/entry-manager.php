<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class EntryManager {

	private $mapper;

	public function __construct () {

		$this->mapper = new Mappers\EntryMapper();
	}
 
	public function updateReferrals(){
		
		$entryMapper = new Mappers\EntryMapper();
		$entries = $entryMapper->getAll();
		
		foreach( $entries as $entry ){
			
			$analyzer = \GFSeoMarketingAddOn\UrlAnalyzer\Analyzer::analyze($entry->getReferral());
			$entry->setReferralType($analyzer->getKey()) ;
			
			$entryMapper->update($entry);
			
		}
		
		
	}
	

}
