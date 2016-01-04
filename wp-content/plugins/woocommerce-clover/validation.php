<?php

namespace WooClover;

/**
 * Description of Woo-validation
 *
 * @author mustela
 */
class Validation {

	public function __construct() {
		;
	}
	
	public static function isWooMissing () {

		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			self::addMenu();
			
			return true;
		}
		
		return false;
		
	}
	
	public static function addMenu(){
		add_action('admin_menu','\WooClover\Validation::init');
	}
	
	public static function init(){
		add_menu_page('WooCommerce Missing', 'WooCommerce Missing', 'manage_options', 'woo-missing', '\WooClover\Validation::showHelp' );
	}

	public static function showHelp() {
		?>
		<div class="wrap">
			<div id="icon-index" class="icon32"><br></div><h2>Clover For Woo</h2>
			<div id="welcome-panel" class="welcome-panel">
				<div class="welcome-panel-content">
					<h3>Welcome to Clover for Woo!</h3>
					<p class="about-description">Remember, you need to have <a href="http://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> installed</p>
				</div>
			</div>
		</div>
		<?php
	}

}
 
