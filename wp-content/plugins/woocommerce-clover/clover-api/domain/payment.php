<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Payment extends CloverDomain {

	
	/**
	 * 'SUCCESS' or 'FAIL'
	 * @var string 
	 */
	private $result;
	
	private $clientCreatedTime;
	private $createdTime;
	private $modifiedTime;
	private $cardTransaction;
	private $amount;
	private $cashTendered;
	private $tipAmount;
	private $lineItemPayments;
	private $taxAmount;
	private $order;
	private $device;
	private $tender;
	private $refunds;
	private $employee;
	private $serviceCharge;
	private $externalPaymentId;
	private $note;
	 
	public function __construct () {
		parent::__construct();
	}
	
	public function getResult () {
		return $this->result;
	}

	public function getClientCreatedTime () {
		return $this->clientCreatedTime;
	}

	public function getCreatedTime () {
		return $this->createdTime;
	}

	public function getModifiedTime () {
		return $this->modifiedTime;
	}

	/**
	 * @collectionType \Wooclover\CloverApi\Domain\CardTransaction
	 * @return \Wooclover\CloverApi\Domain\CardTransaction[]
	 */
	public function getCardTransaction () {
		return $this->cardTransaction;
	}

	public function getAmount () {
		return $this->amount;
	}

	public function getCashTendered () {
		return $this->cashTendered;
	}

	public function getTipAmount () {
		return $this->tipAmount;
	}

	public function getLineItemPayments () {
		return $this->lineItemPayments;
	}

	public function getTaxAmount () {
		return $this->taxAmount;
	}

	public function getOrder () {
		return $this->order;
	}

	public function getDevice () {
		return $this->device;
	}

	public function getTender () {
		return $this->tender;
	}

	public function getRefunds () {
		return $this->refunds;
	}

	public function getEmployee () {
		return $this->employee;
	}

	public function getServiceCharge () {
		return $this->serviceCharge;
	}

	public function getExternalPaymentId () {
		return $this->externalPaymentId;
	}

	public function getNote () {
		return $this->note;
	}

	public function setResult ( $result ) {
		$this->result = $result;
	}

	public function setClientCreatedTime ( $clientCreatedTime ) {
		$this->clientCreatedTime = $clientCreatedTime;
	}

	public function setCreatedTime ( $createdTime ) {
		$this->createdTime = $createdTime;
	}

	public function setModifiedTime ( $modifiedTime ) {
		$this->modifiedTime = $modifiedTime;
	}

	public function setCardTransaction ( $cardTransaction ) {
		$this->cardTransaction = $cardTransaction;
	}

	public function setAmount ( $amount ) {
		$this->amount = $amount;
	}

	public function setCashTendered ( $cashTendered ) {
		$this->cashTendered = $cashTendered;
	}

	public function setTipAmount ( $tipAmount ) {
		$this->tipAmount = $tipAmount;
	}

	public function setLineItemPayments ( $lineItemPayments ) {
		$this->lineItemPayments = $lineItemPayments;
	}

	public function setTaxAmount ( $taxAmount ) {
		$this->taxAmount = $taxAmount;
	}

	public function setOrder ( $order ) {
		$this->order = $order;
	}

	public function setDevice ( $device ) {
		$this->device = $device;
	}

	public function setTender ( $tender ) {
		$this->tender = $tender;
	}

	public function setRefunds ( $refunds ) {
		$this->refunds = $refunds;
	}

	public function setEmployee ( $employee ) {
		$this->employee = $employee;
	}

	public function setServiceCharge ( $serviceCharge ) {
		$this->serviceCharge = $serviceCharge;
	}

	public function setExternalPaymentId ( $externalPaymentId ) {
		$this->externalPaymentId = $externalPaymentId;
	}

	public function setNote ( $note ) {
		$this->note = $note;
	}


	
}