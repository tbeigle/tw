<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Merchant extends CloverDomain {

	private $supportEmail;
	private $autoPrint = false;
	private $removeTaxEnabled = false;
	private $trackStock = false;
	private $defaultCurrency = '';
	private $locale = '';
	private $autoLogout = false;
	private $tipsEnabled = false;
	private $vat = false;
	private $summaryHour = false;
	private $showCloseoutOrders = false;
	private $deleteOrders = false;
	private $appBillingEnabled = false;
	private $timezone = '';
	private $marketingPreferenceText = '';
	private $hardwareProfile = '';
	private $shippingAddress = '';
	private $stayInCategory = false;
	private $updateStock = false;
	private $tipRateDefault = '';
	private $supportPhone = '';
	private $vatTaxName = '';
	private $signatureThreshold = '';
	private $bankMarker = '';
	private $orderTitle = '';
	private $alternateInventoryNames = false;
	private $manualCloseout = '';
	private $onPaperTipSignatures = '';
	private $paidAppsFree = '';
	private $notesOnOrders = '';
	private $groupLineItems = '';

	
	public function getSupportEmail () {
		return $this->supportEmail;
	}

	public function getAutoPrint () {
		return $this->autoPrint;
	}

	public function getRemoveTaxEnabled () {
		return $this->removeTaxEnabled;
	}

	public function getTrackStock () {
		return $this->trackStock;
	}

	public function getDefaultCurrency () {
		return $this->defaultCurrency;
	}

	public function getLocale () {
		return $this->locale;
	}

	public function getAutoLogout () {
		return $this->autoLogout;
	}

	public function getTipsEnabled () {
		return $this->tipsEnabled;
	}

	public function getVat () {
		return $this->vat;
	}

	public function getSummaryHour () {
		return $this->summaryHour;
	}

	public function getShowCloseoutOrders () {
		return $this->showCloseoutOrders;
	}

	public function getDeleteOrders () {
		return $this->deleteOrders;
	}
 

	public function getTimezone () {
		return $this->timezone;
	}
 
 
	public function getAppBillingEnabled () {
		return $this->appBillingEnabled;
	}

	 
	public function getMarketingPreferenceText () {
		return $this->marketingPreferenceText;
	}

	public function getHardwareProfile () {
		return $this->hardwareProfile;
	}

	public function getShippingAddress () {
		return $this->shippingAddress;
	}

	public function getStayInCategory () {
		return $this->stayInCategory;
	}

	public function getUpdateStock () {
		return $this->updateStock;
	}

	public function getTipRateDefault () {
		return $this->tipRateDefault;
	}

	public function getSupportPhone () {
		return $this->supportPhone;
	}

	public function getVatTaxName () {
		return $this->vatTaxName;
	}

	public function getSignatureThreshold () {
		return $this->signatureThreshold;
	}

	public function getBankMarker () {
		return $this->bankMarker;
	}

	public function getOrderTitle () {
		return $this->orderTitle;
	}

	public function getAlternateInventoryNames () {
		return $this->alternateInventoryNames;
	}

	public function getManualCloseout () {
		return $this->manualCloseout;
	}

	public function getOnPaperTipSignatures () {
		return $this->onPaperTipSignatures;
	}

	public function getPaidAppsFree () {
		return $this->paidAppsFree;
	}

	public function getNotesOnOrders () {
		return $this->notesOnOrders;
	}

	public function getGroupLineItems () {
		return $this->groupLineItems;
	}

	public function setSupportEmail ( $supportEmail ) {
		$this->supportEmail = $supportEmail;
	}

	public function setAutoPrint ( $autoPrint ) {
		$this->autoPrint = $autoPrint;
	}

	public function setRemoveTaxEnabled ( $removeTaxEnabled ) {
		$this->removeTaxEnabled = $removeTaxEnabled;
	}

	public function setTrackStock ( $trackStock ) {
		$this->trackStock = $trackStock;
	}

	public function setDefaultCurrency ( $defaultCurrency ) {
		$this->defaultCurrency = $defaultCurrency;
	}

	public function setLocale ( $locale ) {
		$this->locale = $locale;
	}

	public function setAutoLogout ( $autoLogout ) {
		$this->autoLogout = $autoLogout;
	}

	public function setTipsEnabled ( $tipsEnabled ) {
		$this->tipsEnabled = $tipsEnabled;
	}

	public function setVat ( $vat ) {
		$this->vat = $vat;
	}

	public function setSummaryHour ( $summaryHour ) {
		$this->summaryHour = $summaryHour;
	}

	public function setShowCloseoutOrders ( $showCloseoutOrders ) {
		$this->showCloseoutOrders = $showCloseoutOrders;
	}

	public function setDeleteOrders ( $deleteOrders ) {
		$this->deleteOrders = $deleteOrders;
	}

	public function setAppBillingEnabled ( $appBillingEnabled ) {
		$this->appBillingEnabled = $appBillingEnabled;
	}

 
	public function setTimezone ( $timezone ) {
		$this->timezone = $timezone;
	}

	public function setMarketingPreferenceText ( $marketingPreferenceText ) {
		$this->marketingPreferenceText = $marketingPreferenceText;
	}

	public function setHardwareProfile ( $hardwareProfile ) {
		$this->hardwareProfile = $hardwareProfile;
	}

	public function setShippingAddress ( $shippingAddress ) {
		$this->shippingAddress = $shippingAddress;
	}

	public function setStayInCategory ( $stayInCategory ) {
		$this->stayInCategory = $stayInCategory;
	}

	public function setUpdateStock ( $updateStock ) {
		$this->updateStock = $updateStock;
	}

	public function setTipRateDefault ( $tipRateDefault ) {
		$this->tipRateDefault = $tipRateDefault;
	}

	public function setSupportPhone ( $supportPhone ) {
		$this->supportPhone = $supportPhone;
	}

	public function setVatTaxName ( $vatTaxName ) {
		$this->vatTaxName = $vatTaxName;
	}

	public function setSignatureThreshold ( $signatureThreshold ) {
		$this->signatureThreshold = $signatureThreshold;
	}

	public function setBankMarker ( $bankMarker ) {
		$this->bankMarker = $bankMarker;
	}

	public function setOrderTitle ( $orderTitle ) {
		$this->orderTitle = $orderTitle;
	}

	public function setAlternateInventoryNames ( $alternateInventoryNames ) {
		$this->alternateInventoryNames = $alternateInventoryNames;
	}

	public function setManualCloseout ( $manualCloseout ) {
		$this->manualCloseout = $manualCloseout;
	}

	public function setOnPaperTipSignatures ( $onPaperTipSignatures ) {
		$this->onPaperTipSignatures = $onPaperTipSignatures;
	}

	public function setPaidAppsFree ( $paidAppsFree ) {
		$this->paidAppsFree = $paidAppsFree;
	}

	public function setNotesOnOrders ( $notesOnOrders ) {
		$this->notesOnOrders = $notesOnOrders;
	}

	public function setGroupLineItems ( $groupLineItems ) {
		$this->groupLineItems = $groupLineItems;
	}


}
