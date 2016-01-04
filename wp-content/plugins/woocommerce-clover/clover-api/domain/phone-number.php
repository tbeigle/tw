<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


 class PhoneNumber extends CloverDomain{
	 
	 private $id;
	 private $phoneNumber;
	 
	 public function __construct () {
		 parent::__construct();
	 }
	 
	 public function getId () {
		 return $this->id;
	 }

	 public function getPhoneNumber () {
		 return $this->phoneNumber;
	 }

	 public function setId ( $id ) {
		 $this->id = $id;
	 }

	 public function setPhoneNumber ( $phoneNumber ) {
		 $this->phoneNumber = $phoneNumber;
	 }


 
 }