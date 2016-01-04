<?php

/**
 * Description of adjustments
  "name" : String
  "discountId" : String
  "note" : String
  "amount" : Long
  "percentage" : Long
  "type" : (TIP|TAX|SHIPPING|DISCOUNT|COMBO_DISCOUNT)
  "id" : String
 *
 * @author jrojas
 */

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Adjustments extends CloverDomain{
	
	public function __construct () {
		 parent::__construct();
	 }

	private $name;
	private $discountId;
	private $note;
	private $amount;
	private $percentage;
	private $type;

	public function getName () {
		return $this->name;
	}

	public function getDiscountId () {
		return $this->discountId;
	}

	public function getNote () {
		return $this->note;
	}

	public function getAmount () {
		return $this->amount;
	}

	public function getPercentage () {
		return $this->percentage;
	}

	public function getType () {
		return $this->type;
	}


	public function setName ( $name ) {
		$this->name = $name;
	}

	public function setDiscountId ( $discountId ) {
		$this->discountId = $discountId;
	}

	public function setNote ( $note ) {
		$this->note = $note;
	}

	public function setAmount ( $amount ) {
		$this->amount = $amount;
	}

	public function setPercentage ( $percentage ) {
		$this->percentage = $percentage;
	}

	public function setType ( $type ) {
		$this->type = $type;
	}


}
