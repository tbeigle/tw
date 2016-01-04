<?php

namespace Wooclover\Admin\Controllers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class BaseController {
	
	public function __construct () {
		;
	}

	function getAdminView ( $viewName, $data ) {

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();
		ob_start();
		extract( $data );
		require_once($registry->getPluginDir() . "/views/{$viewName}.php");
		$output = ob_get_clean();

		return $output;
	}

}
