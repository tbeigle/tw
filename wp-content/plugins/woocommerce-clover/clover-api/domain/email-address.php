<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


 class EmailAddress extends CloverDomain{
	 
	 private $verifiedTime;
	 private $emailAddress;
	
	 
	 public function __construct () {
		 parent::__construct();
	 }
	 
	 public function getVerifiedTime () {
		 return $this->verifiedTime;
	 }

	 public function getEmailAddress () {
		 return $this->emailAddress;
	 }


	 public function setVerifiedTime ( $verifiedTime ) {
		 $this->verifiedTime = $verifiedTime;
	 }

	 public function setEmailAddress ( $emailAddress ) {
		 $this->emailAddress = $emailAddress;
	 }



 }

 