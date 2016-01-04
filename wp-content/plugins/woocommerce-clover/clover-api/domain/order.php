<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Order extends CloverDomain {

	public function __construct () {
		parent::__construct();
	}

	private $locale;
	private $adjustments;
	private $isVat;
	private $currency;
	private $testMode;
	private $timezone;
	private $accountId;
	private $clientTimestamp;
	private $employeeId;
	private $title;
	private $orderType;
	private $serviceCharge;
	private $note;
	private $state = "open";
	private $total;

	/**
	 *
	 * @var \Wooclover\CloverApi\Domain\LineItem[]
	 */
	private $lineItems;
	private $manualTransaction;
	private $timestamp;
	private $payType;
	private $groupLineItems;
	private $credits;
	private $address;
	private $customer;
	private $refunds;
	private $customerId;
	
	/**
	 *
	 * @var \Wooclover\CloverApi\Domain\Payment[]
	 */
	private $payments;
	private $taxRemoved;
	private $employeeName;
	private $createdTime;
	
	/*Stores the id used by the shop plugin on wordpress*/
	private $localId;

	public function getLocale () {
		return $this->locale;
	}

	public function getAdjustments () {
		return $this->adjustments;
	}

	public function getIsVat () {
		return $this->isVat;
	}

	public function getCurrency () {
		return $this->currency;
	}

	public function getTestMode () {
		return $this->testMode;
	}

	public function getTimezone () {
		return $this->timezone;
	}

	public function getAccountId () {
		return $this->accountId;
	}

	public function getClientTimestamp () {
		return $this->clientTimestamp;
	}

	public function getEmployeeId () {
		return $this->employeeId;
	}

	public function getTitle () {
		return $this->title;
	}

	public function getOrderType () {
		return $this->orderType;
	}

	public function getServiceCharge () {
		return $this->serviceCharge;
	}

	public function getNote () {
		return $this->note;
	}

	public function getState () {
		return $this->state;
	}
	

	/**
	 * @collectionType \Wooclover\CloverApi\Domain\LineItem
	 * @return \Wooclover\CloverApi\Domain\LineItem[]
	 */
	public function getLineItems () {
		return $this->lineItems;
	}

	/**
	 * It returns the group line itemms sumarized
	 * @return \Wooclover\CloverApi\Domain\LineItem[]
	 */
	public function getSummarizedLineItems () {

		if ( !$this->lineItems ) {
			return array();
		}

		usort( $this->lineItems, array( $this, 'sortById' ) );

		$id = false;
		$sumLineItems = array();
		$sumLineItem = false;
		
		foreach ( $this->lineItems as $lineItem ) {
			
			$price = 0;
			if ( $id != $lineItem->getExistingID() ) {

				$sumLineItem = $lineItem->copy();
				
				// We need to reset the quantity and price, since we are copying the object, so we would be coping that props too.
				$sumLineItem->setQty(0);
				$sumLineItem->setPrice(0);
				$sumLineItems[] = $sumLineItem;
				
				$id = $lineItem->getExistingID();
				
			} 

			// By default, the items has no quantity amount, so we need to set it to 1
			$qty = $lineItem->getQty() ? $lineItem->getQty() : 1;
			
			$sumLineItem->setQty( $sumLineItem->getQty() + $qty );
			$sumLineItem->setPrice( $sumLineItem->getPrice() + $lineItem->getPrice() );
		}

		return $sumLineItems;
	}

	public function sortById ( \Wooclover\CloverApi\Domain\LineItem $first, \Wooclover\CloverApi\Domain\LineItem $second ) {

		if ( $first->getId() == $second->getId() ) {
			return 1;
		}

		return 0;
	}

	public function getManualTransaction () {
		return $this->manualTransaction;
	}

	public function getTimestamp () {
		return $this->timestamp;
	}

	public function getPayType () {
		return $this->payType;
	}

	public function getGroupLineItems () {
		return $this->groupLineItems;
	}

	public function getCredits () {
		return $this->credits;
	}

	public function getAddress () {
		return $this->address;
	}

	public function getCustomer () {
		return $this->customer;
	}

	public function getRefunds () {
		return $this->refunds;
	}

	public function getCustomerId () {
		return $this->customerId;
	}

	/**
	 * @collectionType \Wooclover\CloverApi\Domain\Payment
	 * @return \Wooclover\CloverApi\Domain\Payment[]
	 */
	public function getPayments () {
		return $this->payments;
	}

	public function getTaxRemoved () {
		return $this->taxRemoved;
	}

	public function getEmployeeName () {
		return $this->employeeName;
	}

	public function setLocale ( $locale ) {
		$this->locale = $locale;
	}

	public function setAdjustments ( $adjustments ) {
		$this->adjustments = $adjustments;
	}

	public function setIsVat ( $isVat ) {
		$this->isVat = $isVat;
	}

	public function setCurrency ( $currency ) {
		$this->currency = $currency;
	}

	public function setTestMode ( $testMode ) {
		$this->testMode = $testMode;
	}

	public function setTimezone ( $timezone ) {
		$this->timezone = $timezone;
	}

	public function setAccountId ( $accountId ) {
		$this->accountId = $accountId;
	}

	public function setClientTimestamp ( $clientTimestamp ) {
		$this->clientTimestamp = $clientTimestamp;
	}

	public function setEmployeeId ( $employeeId ) {
		$this->employeeId = $employeeId;
	}

	public function setTitle ( $title ) {
		$this->title = $title;
	}

	public function setOrderType ( $orderType ) {
		$this->orderType = $orderType;
	}

	public function setServiceCharge ( $serviceCharge ) {
		$this->serviceCharge = $serviceCharge;
	}

	public function setNote ( $note ) {
		$this->note = $note;
	}

	public function setState ( $state ) {
		$this->state = $state;
	}

	public function addLineItem ( \Wooclover\CloverApi\Domain\LineItem $lineItem ) {
		$this->lineItems[] = $lineItem;
	}

	/**
	 * 
	 * @param \Wooclover\CloverApi\Domain\LineItem[] $lineItems
	 */
	public function setLineItems ( $lineItems ) {
		$this->lineItems = $lineItems;
	}

	public function setManualTransaction ( $manualTransaction ) {
		$this->manualTransaction = $manualTransaction;
	}

	public function setTimestamp ( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	public function setPayType ( $payType ) {
		$this->payType = $payType;
	}

	public function setGroupLineItems ( $groupLineItems ) {
		$this->groupLineItems = $groupLineItems;
	}

	public function setCredits ( $credits ) {
		$this->credits = $credits;
	}

	public function setAddress ( $address ) {
		$this->address = $address;
	}

	public function setCustomer ( $customer ) {
		$this->customer = $customer;
	}

	public function setRefunds ( $refunds ) {
		$this->refunds = $refunds;
	}

	public function setCustomerId ( $customerId ) {
		$this->customerId = $customerId;
	}

	public function setPayments ( $payments ) {
		$this->payments = $payments;
	}

	public function setTaxRemoved ( $taxRemoved ) {
		$this->taxRemoved = $taxRemoved;
	}

	public function setEmployeeName ( $employeeName ) {
		$this->employeeName = $employeeName;
	}

	public function getCreatedTime () {
		return $this->createdTime;
	}

	public function setCreatedTime ( $createdTime ) {
		$this->createdTime = $createdTime;
	}

	public function getTotal () {
		return $this->total;
	}

	public function setTotal ( $total ) {
		$this->total = $total;
	}
	
	public function getLocalId () {
		return $this->localId;
	}

	public function setLocalId ( $localId ) {
		$this->localId = $localId;
	}



}
