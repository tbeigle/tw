<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class UserManager {

	public function __construct() {
		
	}

	public function getAdministrators() {
		$users = get_users( array( 'role' => 'administrator', 'fields' => 'all_with_meta' ) );

		$returnUsers = array();
		foreach ( $users as $user ) {

			$newUser = array();
			$newUser[ 'firstName' ] = $user->first_name;
			$newUser[ 'lastName' ] = $user->last_name;
			$newUser[ 'login' ] = $user->user_login;
			$newUser[ 'email' ] = $user->user_email;
			$newUser[ 'name' ] = $user->display_name;
			$newUser[ 'id' ] = "{$user->ID}";
			$returnUsers[] = $newUser;
		}

		return $returnUsers;
	}

}
