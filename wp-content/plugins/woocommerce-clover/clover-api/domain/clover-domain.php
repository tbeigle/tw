<?php

namespace Wooclover\CloverApi\Domain;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

abstract class CloverDomain {

	private $id;
	private $sendNulls = false;

	public function __construct () {
		;
	}

	public function getId () {
		return $this->id;
	}

	public function setId ( $id ) {
		$this->id = $id;
	}

	public function getSendNulls () {
		return $this->sendNulls;
	}

	public function setSendNulls ( $sendNulls ) {
		$this->sendNulls = $sendNulls;
	}

	private function toArrayMagic ( $obj ) {

		$return = array();

		$reflect = new \ReflectionClass( $obj );
		$props = $reflect->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED );

		foreach ( $props as $prop ) {

			$methodNameGet = "get" . $prop->getName();

			//This is for boolean properties
			$methodNameIs = "is" . $prop->getName();

			//Check if it is public, we won't add private/protected properties
			if ( is_callable( array( $obj, $methodNameGet ) ) ) {

				$value = $obj->{$methodNameGet}();

				if ( is_object( $value ) ) {
					$return[ $prop->getName() ] = $this->toArrayMagic( $value );
				} elseif ( is_array( $value ) ) {

					$return[ $prop->getName() ] = array();
					foreach ( $value as $newValue ) {

						if ( is_object( $newValue ) ) {

							$return[ $prop->getName() ][] = $this->toArrayMagic( $newValue );
						} elseif ( is_array( $value ) ) {

							$return[ $prop->getName() ] = array();

							foreach ( $value as $newValue ) {

								if ( is_object( $newValue ) ) {

									$return[ $prop->getName() ][] = $this->toArrayMagic( $newValue );
								} elseif ( is_array( $value ) ) {

									$return[ $prop->getName() ] = array();

									foreach ( $value as $newValue ) {

										if ( is_object( $newValue ) ) {

											$return[ $prop->getName() ][] = $this->toArrayMagic( $newValue );
										} else {
											$return = $this->setValue( $return, $prop->getName(), $value );
										}
									}
								} else {
									$return = $this->setValue( $return, $prop->getName(), $value );
								}
							}
						} else {
							$return = $this->setValue( $return, $prop->getName(), $value );
						}
					}
				} else {
					$return = $this->setValue( $return, $prop->getName(), $value );
				}
			} else if ( is_callable( array( $obj, $methodNameIs ) ) ) {
				//Cast the value to boolean
				$return[ $prop->getName() ] = ( bool ) $obj->{$methodNameIs}();
			}
		}

		//Not sure why it doesn't read the id property, so that's way we are adding it manually.
		if ( is_callable( array( $obj, 'getId' ) ) ) {
			if ( $obj->getId() ) {
				$return[ 'id' ] = $obj->getId();
			}
		}

		return $return;
	}

	private function setValue ( $props, $propName, $value ) {
		if ( !$this->sendNulls ) {
			if ( !\Wooclover\Core\Utils::isEmpty( $value ) ) {
				$props[ $propName ] = $value;
			}
		} else {
			$props[ $propName ] = $value;
		}

		return $props;
	}

	public function toArray () {

		$return = $this->toArrayMagic( $this );

		return $return;
	}

	public static function getClassName () {
		return get_called_class();
	}

}
