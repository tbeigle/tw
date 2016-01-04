<?php

namespace Wooclover\CloverApi;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class BaseConnector {

	private $merchantId;
	private $apiToken;
	private $baseUrl = "https://api.clover.com/v3/merchants";
	private $resource;
	private $defaultClassName;

	public function __construct( $merchantId, $apiToken, $resource = "", $defaultClassName = "" ) {
		$this->merchantId = $merchantId;
		$this->apiToken = $apiToken;
		$this->resource = $resource;
		$this->defaultClassName = $defaultClassName;
	}

	/**
	 * 
	 * @param string $type
	 * @param boolean $isCollection
	 * @return \Wooclover\Core\Conversor
	 */
	protected function getDefaultConversor( $type, $isCollection = false ) {
		return new \Wooclover\Core\Conversor( $type, $this->defaultClassName, $isCollection );
	}

	/**
	 * 
	 * @param \Wooclover\Core\Conversor $conversor
	 * @param string $query
	 * @param array $expands
	 * @return string
	 */
	protected function get( \Wooclover\Core\Conversor $conversor, $query = "", $expands = false ) {

		$response = @file_get_contents( $this->getUrl( $query, $expands ) );

		if ( $response === FALSE ) {
			//Something goes wrong
			throw new \Exception( "Can\'t connect to API. Check your credentials." );
		}

		return $conversor->transform( $response );
	}

	private function transformContent( $something ) {

		$content = false;

		if ( $something instanceof Domain\CloverDomain ) {
			$content = json_encode( $something->toArray() );
		} else {
			if ( is_array( $something ) && count( $something ) > 0 ) {
				$content = json_encode( $something );
			}
		}

		return $content;
	}

	/**
	 * 
	 * @param string $query
	 * @param object $something
	 * @return type
	 */
	protected function post( $query = "", $something = false, \Wooclover\Core\Conversor $conversor = null ) {

		$content = $this->transformContent( $something );
		error_log( $content );
		$httpOptions = array(
		    'method' => 'POST',
		    'header' => 'Content-type: application/x-www-form-urlencoded'
		);

		if ( $content ) {
			$httpOptions[ 'header' ] = 'Content-type: application/json; charset=UTF-8' . PHP_EOL .
				'Content-Length: ' . strlen( $content ) . PHP_EOL;
			$httpOptions[ 'content' ] = $content;
		}

//		error_log( $content );
//		error_log( print_r( $httpOptions, true ) );

		$opts = array( 'http' =>
		    $httpOptions
		);


		$context = stream_context_create( $opts );

		$response = @file_get_contents( $this->getUrl( $query ), false, $context );
		
		if ( $response === FALSE ) {
			//Something goes wrong
			throw new \Exception( "Can\'t connect to API. Check your credentials." );
		}

		if ( $conversor ) {
			return $conversor->transform( $response );
		}

		return $response;
	}

	private function getUrl( $query, $expands = false ) {

		$resource = $this->resource ? "/{$this->resource}" : "";

		$expandParameter = "";
		if ( $expands ) {
			$expandParameter = "&expand=" . join( ',', $expands );
		}

		$query = $query ? "/$query" : $query;

		$fullUrl = "{$this->baseUrl}/{$this->merchantId}{$resource}{$query}?access_token={$this->apiToken}{$expandParameter}";

//		error_log($expandParameter);
//		error_log( $fullUrl );

		return $fullUrl;
	}

}
