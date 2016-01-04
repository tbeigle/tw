<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Customer extends CloverDomain {

	private $marketingAllowed = false;

	/**
	 * Phones numbers
	 * @var \Wooclover\CloverApi\Domain\PhoneNumber[]
	 */
	private $phoneNumbers = array();

	/**
	 * Addresses
	 * @var \Wooclover\CloverApi\Domain\Address[]
	 */
	private $addresses = array();
	private $firstName;
	private $lastName;

	/**
	 *
	 * @var int 
	 */
	private $numId;

	/**
	 *
	 * @var string 
	 */
	private $customerSince;

	/**
	 *
	 * @var \Wooclover\CloverApi\Domain\Order[] 
	 */
	private $orders;

	/**
	 *
	 * @var \Wooclover\CloverApi\Domain\EmailAddress[] 
	 */
	private $emailAddresses = array();

	public function __construct () {
		parent::__construct();
	}

	public function getMarketingAllowed () {
		return $this->marketingAllowed;
	}

	/**
	 * Get phone numbers
	 * @collectionType: \Wooclover\CloverApi\Domain\PhoneNumber
	 * @return \Wooclover\CloverApi\Domain\PhoneNumber[] 
	 */
	public function getPhoneNumbers () {
		return $this->phoneNumbers;
	}

	/**
	 * Get Addresses
	 * @collectionType: \Wooclover\CloverApi\Domain\Address
	 * @return \Wooclover\CloverApi\Domain\Address[] 
	 */
	public function getAddresses () {
		return $this->addresses;
	}

	public function getFirstName () {
		return $this->firstName;
	}

	public function getLastName () {
		return $this->lastName;
	}

	public function getNumId () {
		return $this->numId;
	}

	public function getCustomerSince () {
		return $this->customerSince;
	}

	public function getOrders () {
		return $this->orders;
	}

	/**
	 * Get email addresses
	 * @collectionType: \Wooclover\CloverApi\Domain\EmailAddress
	 * @return \Wooclover\CloverApi\Domain\EmailAddress[] 
	 */
	public function getEmailAddresses () {
		return $this->emailAddresses;
	}

	public function getDefaultEmail () {
		if ( count( $this->emailAddresses ) > 0 ) {
			return $this->emailAddresses[ 0 ]->getEmailAddress();
		} else {
			return $this->getId() . '@clover.com';
		}
	}

	public function getDefaultAddress () {
		if ( count( $this->addresses ) > 0 ) {
			return $this->addresses[ 0 ];
		} else {
			return new Address();
		}
	}

	public function setMarketingAllowed ( $marketingAllowed ) {
		$this->marketingAllowed = $marketingAllowed;
	}

	public function setPhoneNumbers ( \Wooclover\CloverApi\Domain\PhoneNumber $phoneNumbers ) {
		$this->phoneNumbers = $phoneNumbers;
	}

	public function setAddresses ( $addresses ) {
		$this->addresses = $addresses;
	}

	public function setFirstName ( $firstName ) {
		$this->firstName = $firstName;
	}

	public function setLastName ( $lastName ) {
		$this->lastName = $lastName;
	}

	public function setNumId ( $numId ) {
		$this->numId = $numId;
	}

	public function setCustomerSince ( $customerSince ) {
		$this->customerSince = $customerSince;
	}

	public function setOrders ( \Wooclover\CloverApi\Domain\Order $orders ) {
		$this->orders = $orders;
	}

	public function setEmailAddresses ( $emailAddresses ) {
		$this->emailAddresses = $emailAddresses;
	}

}
