<?php

namespace Wooclover\Core\Domain\IntelligenceReport;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class GoogleGraphRecord {
	
	private $color;
	private $value;
	private $label;
	private $randomColor = false;
	
	public function __construct() {
		;
	}
	 
	public function getColor ( ) {
		if ( $this->randomColor ) {
			return $this->getRandomColor();
		}

		return $this->color;
	}
	
	private function getRandomColor(){
		
	}

	public function getValue () {
		return $this->value;
	}

	public function getLabel () {
		return $this->label;
	}

	public function setColor ( $color ) {
		$this->color = $color;
	}

	public function setValue ( $value ) {
		$this->value = $value;
	}

	public function setLabel ( $label ) {
		$this->label = $label;
	}

	public function setRandomColor( $value ){
		$this->randomColor = $value;
	}
	
	public function isRandomColor(){
		return $this->randomColor;
	}

	
}