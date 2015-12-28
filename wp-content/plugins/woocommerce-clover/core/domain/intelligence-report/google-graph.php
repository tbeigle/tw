<?php

namespace Wooclover\Core\Domain\IntelligenceReport;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class GoogleGraph extends Element{
	
	private $url;
	private $width = "300";
	private $height = "225";
	
	/**
	 *
	 * @var \GFSeoMarketingAddOn\Core\Domain\IntelligenceReport\GoogleGraphRecord
	 */
	private $records = array();
 
	
	public function __construct() {
		;
	}
	 
	
	public function toHtml(){
		
		if ( ! $this->url ){
			$this->url = $this->buildUrl();
		}
		return $this->processTemplate('google-graph', $this);
		
	}
	
	private function buildUrl(){
		
		if ( !\GFSeoMarketingAddOn\Core\Utils::isEmpty( $this->records ) ) {
			return "";
		}

		$chco = "";
		$chd = "";
		$chdl = "";
		foreach( $this->records as $record ){
			
			$chco = $chco ? $chco . "|" . $record->getColor( ): $record->getColor( );
			$chd = $chd ? $chd . "," . $record->getValue( ): $record->getValue( );
			$chdl = $chdl ? $chdl . "|" . $record->getLabel( ): $record->getLabel( );
			
		}
	

//		$gGraph->setTitle( 'Sales' );
//		$gGraph->setUrl( "http://chart.googleapis.com/chart?chs=300x225&cht=p&chco=00A2FF|80C65A|FF0000&chd=t:{$countReceived},{$countCompleted},{$countError}&chdl=Received|Completed|Error" );

		$url = "http://chart.googleapis.com/chart?chs={$this->width}x{$this->height}&cht=p&chco={$chco}&chd=t:{$chd}&chdl={$chdl}";
		return $url;
		
	}
	
	
	public function getUrl() {
		return $this->url;
	}

	public function setUrl( $url ) {
		$this->url = $url;
	}
	
	public function getWidth() {
		return $this->width;
	}

	public function setWidth( $width ) {
		$this->width = $width;
	}

	public function getHeight() {
		return $this->height;
	}

	public function setHeight( $height ) {
		$this->height = $height;
	}
	
	
	public function addRecord( \GFSeoMarketingAddOn\Core\Domain\IntelligenceReport\GoogleGraphRecord $record ){
		$this->records[] = $record;
	}
	
	public function getRecords(){
		return $this->records;
	}
	
	public function setRecords( $records ){
		$this->records = $records;
	}
	
	

//		$gGraph->setTitle( 'Sales' );
//		$gGraph->setUrl( "http://chart.googleapis.com/chart?chs=300x225&cht=p&chco=00A2FF|80C65A|FF0000&chd=t:{$countReceived},{$countCompleted},{$countError}&chdl=Received|Completed|Error" );

	
}