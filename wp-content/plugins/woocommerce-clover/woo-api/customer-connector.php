<?php

namespace Wooclover\WooApi;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class CustomerConnector extends BaseConnector implements \Wooclover\Core\Interfaces\iCustomerConnector{

	/**
	 * Add or Update a customer into Woo
	 * @param \Wooclover\CloverApi\Domain\Customer $customer
	 * @return int | WP_Error
	 */
	public function addCustomer ( \Wooclover\CloverApi\Domain\Customer $customer ) {
		$userdata = array(
			'user_login' => $customer->getId(),
			'user_nicename' => $customer->getId() . '@clover.com',
			'user_url' => '',
			'user_email' => $customer->getDefaultEmail(),
			'display_name' => $customer->getId() . '@clover.com',
			'nickname' => $customer->getId() . '@clover.com',
			'first_name' => $customer->getFirstName(),
			'last_name' => $customer->getLastName(),
			'description' => 'Clover Customer',
			'user_registered' => $customer->getCustomerSince(),
			'role'=> 'customer',
			'default_address'=>$customer->getDefaultAddress(),
		);
		$user_login = $customer->getId();
		if ( username_exists( $user_login ) ) {
			$wp_user = get_user_by( 'login', $user_login );
			$userdata[ 'ID' ] = $wp_user->ID;
			$user_id = wp_update_user( $userdata );
		} else {
			$password = wp_generate_password( 8, true );
			$userdata['user_pass'] = $password;
			$user_id = wp_insert_user( $userdata );
		}
		if ( is_wp_error( $user_id ) ) {
			return $user_id->get_error_message();
		}
		update_user_meta( $user_id, Config::CloverIdMetaKey, $customer->getId() );
		update_user_meta($user_id, 'default_address', $customer->getDefaultAddress());
		return $user_id;
	}

}
