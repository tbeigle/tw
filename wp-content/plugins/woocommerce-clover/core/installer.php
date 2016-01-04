<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Installer {

	public function __construct() {
		;
	}

	public function install() {
		global $wpdb;

		$create = array(
		   
		    
		);

		foreach ( $create AS $sql ) {
			$wpdb->query( $sql );
		}
	}

	public function uninstall() {
		
	}

}
