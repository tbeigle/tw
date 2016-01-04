<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


 class Address extends CloverDomain{
	 
	 private $city;
	 private $name;
	 private $zip;
	 private $address1;
	 private $address2;
	 private $address3;
	 private $state;
	 private $country;
	 
	 public function __construct () {
		 parent::__construct();
	 }
	 
	 public function getCity () {
		 return $this->city;
	 }

	 public function getName () {
		 return $this->name;
	 }

	 public function getZip () {
		 return $this->zip;
	 }

	 public function getAddress1 () {
		 return $this->address1;
	 }

	 public function getAddress2 () {
		 return $this->address2;
	 }

	 public function getAddress3 () {
		 return $this->address3;
	 }

	 public function getState () {
		 return $this->state;
	 }

	 public function getCountry () {
		 return $this->country;
	 }


	 public function setCity ( $city ) {
		 $this->city = $city;
	 }

	 public function setName ( $name ) {
		 $this->name = $name;
	 }

	 public function setZip ( $zip ) {
		 $this->zip = $zip;
	 }

	 public function setAddress1 ( $address1 ) {
		 $this->address1 = $address1;
	 }

	 public function setAddress2 ( $address2 ) {
		 $this->address2 = $address2;
	 }

	 public function setAddress3 ( $address3 ) {
		 $this->address3 = $address3;
	 }

	 public function setState ( $state ) {
		 $this->state = $state;
	 }

	 public function setCountry ( $country ) {
		 $this->country = $country;
	 }

 }
 