<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class ProductCategory extends CloverDomain{
	
	private $id;
	private $name;

	public function __construct () {
		 parent::__construct();
	 }
	 
	public function getId () {
		return $this->id;
	}

	public function getName () {
		return $this->name;
	}
	
	public function setId ( $id ) {
		$this->id = $id;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}
	
}

