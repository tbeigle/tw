<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Output {

	private $data = array();

	/**
	 * 
	 */
	public static function send( $obj, $isError = FALSE ) {
		if ( $obj instanceof Domain\DomainObject ) {
			$obj = $obj->toArray();
		}
		if ( $obj instanceof \Wooclover\CloverApi\Domain\CloverDomain ) {
			$obj = $obj->toArray();
		}

		$data = json_encode( $obj );

		if ( $isError ) {
			header( "HTTP/1.0 500 Error" );
		}
		
		die( $data );
	}

	public static function sendString( $string ,$isError = FALSE ) {
		if ( $isError ) {
			header( "HTTP/1.0 500 Error" );
		}
		
		die( $string );
	}

	/**
	 * Print a collection of objects in json format. If the objects are DomainObject, it will ejecute the toArray function
	 * @param array $collection
	 */
	public static function sendCollection( $collection, $isError = FALSE ) {

		$response = array();
		foreach ( $collection as $item ) {
			if ( $item instanceof Domain\DomainObject ) {
				$response[] = $item->toArray();
			} else if ( $item instanceof \Wooclover\CloverApi\Domain\CloverDomain ) {
				$response[] = $item->toArray();
			} else {
				$response[] = $item;
			}
		}

		self::send( $response , $isError);
	}

	/**
	 * Add a value to the data array
	 * @param string $key
	 * @param mixed $value
	 */
	function addData( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * It lets you add an array of data objects. 
	 * @param array $data
	 */
	function addMassiveData( $data ) {

		if ( is_array( $data ) && count( $data ) > 0 ) {
			$this->data = array_merge( $this->data, $data );
		}
	}

	/**
	 * 
	 * @param string $viewName
	 * @param array $data
	 * @return string
	 */
	function getView( $viewName, $data = array() ) {

		$this->addMassiveData( $data );

		ob_start();
		extract( $this->data );
		require_once($viewName);
		$output = ob_get_clean();

		return $output;
	}

}
