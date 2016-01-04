<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class Product extends CloverDomain{
	
	private $id;
	private $name;
	private $code;
	private $price;
	private $taxable;
	private $priceType;
	private $unitName;
	private $cost;
	private $defaultTaxRates;
	private $hidden;
	private $revenue;
	private $stockCount;
	
	/*Stores the id used by the shop plugin on wordpress*/
	private $localId;
	
	/**
	 *
	 * * @var \Wooclover\CloverApi\Domain\ProductCategory[]
	 */
	private $categories;
	
	
	
	public function __construct () {
		 parent::__construct();
	 }
	 
	public function getId () {
		return $this->id;
	}

	public function getName () {
		return $this->name;
	}

	public function getCode () {
		return $this->code;
	}

	public function getPrice () {
		return $this->price;
	}

	public function isTaxable () {
		return $this->taxable;
	}

	public function getPriceType () {
		return $this->priceType;
	}

	public function getUnitName () {
		return $this->unitName;
	}

	public function getCost () {
		return $this->cost;
	}

	public function getDefaultTaxRates () {
		return $this->defaultTaxRates;
	}

	public function getHidden () {
		return $this->hidden;
	}

	public function isRevenue () {
		return $this->revenue;
	}

	public function setId ( $id ) {
		$this->id = $id;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	public function setCode ( $code ) {
		$this->code = $code;
	}

	public function setPrice ( $price ) {
		$this->price = $price;
	}

	public function setTaxable ( $taxable ) {
		$this->taxable = $taxable;
	}

	public function setPriceType ( $priceType ) {
		$this->priceType = $priceType;
	}

	public function setUnitName ( $unitName ) {
		$this->unitName = $unitName;
	}

	public function setCost ( $cost ) {
		$this->cost = $cost;
	}

	public function setDefaultTaxRates ( $defaultTaxRates ) {
		$this->defaultTaxRates = $defaultTaxRates;
	}

	public function setHidden ( $hidden ) {
		$this->hidden = $hidden;
	}

	public function setRevenue ( $revenue ) {
		$this->revenue = $revenue;
	}
	
	public function getStockCount() {
		return $this->stockCount;
	}

	public function setStockCount( $stockCount ) {
		$this->stockCount = $stockCount;
	}
	
	public function getLocalId() {
		return $this->localId;
	}

	public function setLocalId( $localId ) {
		$this->localId = $localId;
	}

	/**
	 * 
	 * @param \Wooclover\CloverApi\Domain\ProductCategory[] $categories
	 */
	public function setCategories(  $categories ) {
		$this->categories = $categories;
	}

	public function addCategory ( \Wooclover\CloverApi\Domain\ProductCategory $category ) {
		$this->categories[] = $category;
	}

	/**
	 * @collectionType \Wooclover\CloverApi\Domain\ProductCategory
	 * @return \Wooclover\CloverApi\Domain\ProductCategory[]
	 */
	public function getCategories () {
		return $this->categories;
	}

	 
}