<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CardTransaction extends CloverDomain {

	/**
	 * Authorization code (if successful)
	 * @var string 
	 */
	private $authCode;
	
	/**
	 * ['SWIPED' or 'KEYED' or 'VOICE' or 'VAULTED' or 'OFFLINE_SWIPED' or 'OFFLINE_KEYED' or 'EMV_CONTACT' or 'EMV_CONTACTLESS' or 'MSD_CONTACTLESS' or 'PINPAD_MANUAL_ENTRY'],
	 * @var string 
	 */
	private $entryType;
	
	/**
	 * Extra info to be stored as part of gateway/card transaction,
	 * @var map 
	 */
	private $extra;
	
	/**
	 * ['PENDING' or 'CLOSED']
	 * @var string 
	 */
	private $state;
	
	private $referenceId;
	
	/**
	 * ['AUTH' or 'PREAUTH' or 'PREAUTHCAPTURE' or 'ADJUST' or 'VOID' or 'VOIDRETURN' or 'RETURN' or 'REFUND' or 'NAKEDREFUND'],
	 * @var string 
	 */
	private $type;
	private $last4;
	
	/**
	 * ['VISA' or 'MC' or 'AMEX' or 'DISCOVER' or 'DINERS_CLUB' or 'JCB' or 'MAESTRO' or 'SOLO' or 'LASER' or 'CHINA_UNION_PAY' or 'CARTE_BLANCHE' or 'UNKNOWN']
	 * @var type 
	 */
	private $cardType;
	
	
	public function __construct () {
		parent::__construct();
	}
	 
	public function getAuthCode () {
		return $this->authCode;
	}

	public function getEntryType () {
		return $this->entryType;
	}

	public function getExtra () {
		return $this->extra;
	}

	public function getState () {
		return $this->state;
	}

	public function getReferenceId () {
		return $this->referenceId;
	}

	public function getType () {
		return $this->type;
	}

	public function getLast4 () {
		return $this->last4;
	}

	public function getCardType () {
		return $this->cardType;
	}

	public function setAuthCode ( $authCode ) {
		$this->authCode = $authCode;
	}

	public function setEntryType ( $entryType ) {
		$this->entryType = $entryType;
	}

	public function setExtra ( map $extra ) {
		$this->extra = $extra;
	}

	public function setState ( $state ) {
		$this->state = $state;
	}

	public function setReferenceId ( $referenceId ) {
		$this->referenceId = $referenceId;
	}

	public function setType ( $type ) {
		$this->type = $type;
	}

	public function setLast4 ( $last4 ) {
		$this->last4 = $last4;
	}

	public function setCardType ( type $cardType ) {
		$this->cardType = $cardType;
	}


}