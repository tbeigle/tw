<?php

namespace Wooclover\Core\Domain\IntelligenceReport;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class HeadLine extends Element{
	
	private $text;
	
	public function __construct () {
		parent::__construct();
	}
	
	public function getText () {
		return $this->text;
	}

	public function setText ( $text ) {
		$this->text = $text;
	}

	public function toHtml(){
		
		return $this->processTemplate( "head-line", $this );
		
	}
	
}