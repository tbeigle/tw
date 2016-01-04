<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class LineItem extends CloverDomain {

	private $unitQty = 0;
	private $qty = 0;
	private $productCode;
	private $note;
	private $price = 0;
	private $name;
	private $createdTime;
	private $alternateName;
	private $printed = false;
	private $exchanged = false;
	private $refunded = false;
	
	/**
	 * Clover saves in a "item" prop inside line items, the information about the real inventory item, like ID
	 * So if we want to query by "id" we need to use this prop.
	 * @var array 
	 */
	private $item = false;

	public function __construct () {
		parent::__construct();
		
		$this->item = new Item();
	}
	
	public function isPrinted () {
		return $this->printed;
	}

	public function setPrinted ( $printed ) {
		$this->printed = $printed;
	}
	
	public function isExchanged () {
		return $this->exchanged;
	}

	public function setExchanged ( $exchanged ) {
		$this->exchanged = $exchanged;
	}

	public function getUnitQty () {
		return $this->unitQty;
	}

	public function getQty () {
		return $this->qty;
	}

	public function getProductCode () {
		return $this->productCode;
	}

	public function getNote () {
		return $this->note;
	}

	public function getPrice () {
		return $this->price;
	}

	public function setUnitQty ( $unitQty ) {
		$this->unitQty = $unitQty;
	}

	public function setQty ( $unitQty ) {
		$this->unitQty = $unitQty;
	}

	public function setProductCode ( $productCode ) {
		$this->productCode = $productCode;
	}

	public function setNote ( $note ) {
		$this->note = $note;
	}

	public function setPrice ( $price ) {
		$this->price = $price;
	}

	public function getName () {
		return $this->name;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	public function getCreatedTime () {
		return $this->createdTime;
	}

	public function setCreatedTime ( $createdTime ) {
		$this->createdTime = $createdTime;
	}
	
		public function getAlternateName () {
		return $this->alternateName;
	}

	public function setAlternateName ( $alternateName ) {
		$this->alternateName = $alternateName;
	}

	public function isRefunded () {
		return $this->refunded;
	}

	public function setRefunded ( $refunded ) {
		$this->refunded = $refunded;
	}

	/**
	 * 
	 * @return \Wooclover\CloverApi\Domain\Item
	 */
	public function getItem () {
		return $this->item;
	}

	public function setItem ( \Wooclover\CloverApi\Domain\Item $item ) {
		$this->item = $item;
	}
		
	
	public function getInventoryId(){
		if ( $this->item && $this->item->getId() ){
			return $this->item->getId();
		}
		
		return false;
	}
	
	public function getExistingID(){
		return $this->getInventoryId()?$this->getInventoryId():$this->getId();
	}
	
	
	public function copy () {
		
		$lineItem = new LineItem();
		$lineItem->setId( $this->getId() );
		$lineItem->setName( $this->getName() );
		$lineItem->setNote( $this->getNote() );
		$lineItem->setProductCode( $this->getProductCode() );
		$lineItem->setQty( $this->getQty( ) );
		$lineItem->setUnitQty( $this->getUnitQty() );
		$lineItem->setPrice( $this->getPrice() );
		$lineItem->setCreatedTime( $this->getCreatedTime() );
		$lineItem->setAlternateName( $this->getAlternateName() );
		$lineItem->setPrinted( $this->isPrinted() );
		$lineItem->setExchanged( $this->isExchanged() );
		$lineItem->setRefunded( $this->isRefunded() );
		$lineItem->setItem( $this->getItem()->copy() );
		
		return $lineItem;
		
	}
	


}
