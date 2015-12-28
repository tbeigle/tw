<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Conversor {

	const TYPE_OBJECT = 'object';
	const TYPE_STRING = 'string';
	const TYPE_JSON = 'json';
	const TYPE_ARRAY = 'array';
	const TYPE_STDCLASS = 'stdclass';

	private $type;
	private $className;
	private $isCollection = false;
	private $innerProperty = false;

	public function __construct ( $type, $className, $isCollection = false ) {
		$this->type = $type;
		$this->className = $className;
		$this->isCollection = $isCollection;

		// By default all the collections returns the rows inside an "elements" prop
		if ( $this->isCollection ) {
			$this->innerProperty = "elements";
		} else {
			$this->innerProperty = "element";
		}
	}

	public function getType () {
		return $this->type;
	}

	public function getClassName () {
		return $this->className;
	}

	public function isCollection () {
		return $this->isCollection;
	}

	public function setType ( $type ) {
		$this->type = $type;
	}

	public function setClassName ( $className ) {
		$this->className = $className;
	}

	public function setIsCollection ( $isCollection ) {
		$this->isCollection = $isCollection;
	}

	public function transform ( $response ) {

		switch ( $this->getType() ) {
			case self::TYPE_JSON:
				return json_encode( $response );

			case self::TYPE_OBJECT:

				return !$this->isCollection() ? $this->processSingleObject( $response ) : $this->processCollection( $response );

			case self::TYPE_STRING:
				return $response;

			case self::TYPE_ARRAY:
				return json_decode( $response, true );
				
			case self::TYPE_STDCLASS:
				return json_decode( $response );
		}
	}

	private function returnInnerProperty ( $response ) {

		if ( $this->innerProperty && isset( $response[ $this->innerProperty ] ) ) {
			$response = $response[ $this->innerProperty ];
		}

		return $response;
	}

	private function processCollection ( $response ) {

		$collection = array();
		$response = json_decode( $response, true );

		$response = $this->returnInnerProperty( $response );

		foreach ( $response as $item ) {
			$collection[] = $this->processItem( $item );
		}

		return $collection;
	}

	private function processSingleObject ( $response ) {
		$response = json_decode( $response, true );

		$response = $this->returnInnerProperty( $response );

		return $this->processItem( $response );
	}

	private function processItem ( $response ) {

		$obj = $this->getType() == self::TYPE_OBJECT ? new $this->className : null;
		\Wooclover\Core\FillerHelper::fillObject( $obj, $response );

		return $obj;
	}

	public function getInnerProperty () {
		return $this->innerProperty;
	}

	public function setInnerProperty ( $innerProperty ) {
		$this->innerProperty = $innerProperty;
	}

}
