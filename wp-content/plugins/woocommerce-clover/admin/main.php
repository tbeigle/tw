<?php

namespace Wooclover\Admin;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Main {

	private $classes = array();

	public function __construct() {

		$this->classes[ 'settings' ] = new Controllers\Settings();
		$this->classes[ 'synchronizer' ] = new Controllers\Synchronizer();
		$this->classes[ 'apiTester' ] = new Controllers\ApiTester();
	}

	public function init() {

		add_action( 'admin_enqueue_scripts', array( $this, 'registerResources' ) );
		add_action( 'admin_menu', array( $this, 'registerMenu' ) );

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();

		add_filter( "wooclover/views/get/wooclover", array( $this->classes[ 'settings' ], 'getView' ) );

		$this->wooInit();
	}

	public function registerApi() {
		if ( ! current_user_can( 'manage_options' ) )
			return;

		foreach ( $this->classes as $class ) {

			add_filter( 'admin_init', array( $class, 'init' ) );
		}
	}

	private function wooInit() {
		$productController = new Woo\ProductController();
		$productController->init();

		$orderController = new \Wooclover\Admin\Woo\OrderController();
		$orderController->init();
	}

	public function registerResources( $hook ) {

		if ( \stripos( $hook, 'wooclo' ) === FALSE ) {
			return;
		}

		self::registerStyles();
		self::registerScripts();
	}

	public function registerMenu() {

		global $submenu;

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();
		add_menu_page( 'WooClover', 'WooClover', 'manage_options', 'wooclo', array( $this, 'showApp' ), $registry->getImagesUrl() . '/icon.png' );

		add_submenu_page( 'wooclo', 'Sync', 'Sync', 'manage_options', 'wooclo#/synchronizer', array( $this, 'showApp' ) );
		add_submenu_page( 'wooclo', 'Settings', 'Settings', 'manage_options', 'wooclo#/settings', array( $this, 'showApp' ) );
		//	add_submenu_page( $menu->getParent(), $menu->getPageTitle(), $menu->getMenuTitle(), $menu->getCapability(), $this->registry->getPluginKey() . "#" . $menu->getSlug(), array( $this, 'showApp' ) );

		if ( $submenu[ 'wooclo' ] ) {
			$submenu[ 'wooclo' ] = array_slice( $submenu[ 'wooclo' ], 1 );
		}



//		echo "<pre>";
//		var_dump( $submenu[ 'wooclo' ] ); //toplevel_page_wooclo
//
//		echo "</pre>";
	}

	public function registerStyles() {

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();

		if ( $registry->isDevEnv() ) {
			wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
			wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

			wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
		} else {
			wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
		}
	}

	public function registerScripts() {

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();

		if ( $registry->isDevEnv() ) {
			wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-google-chart', $registry->getBowerComponentUrl() . "angular-google-chart/ng-google-chart.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-ui-utils', $registry->getBowerComponentUrl() . "angular-ui-utils/ui-utils.js", 'angular', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/mainApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/global/services/woo-clover-config.js', $registry->getScriptsUrl() . "admin/global/services/woo-clover-config.js", 'admin/mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/global/controllers/main-nav.js', $registry->getScriptsUrl() . "admin/global/controllers/main-nav.js", 'admin/mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/global/controllers/messages.js', $registry->getScriptsUrl() . "admin/global/controllers/messages.js", 'admin/mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/global/directives/loading.js', $registry->getScriptsUrl() . "admin/global/directives/loading.js", 'admin/mainApp', $registry->getPluginVersion() );
			
			wp_enqueue_script( 'admin/dashboard/controllers/main.js', $registry->getScriptsUrl() . "admin/dashboard/controllers/dashboard.js", 'admin/mainApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/settings/controllers/settings.js', $registry->getScriptsUrl() . "admin/settings/controllers/settings.js", 'admin/mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/settings/services/settings.js', $registry->getScriptsUrl() . "admin/settings/services/settings.js", 'admin/mainApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/api-tester/controllers/api-tester.js', $registry->getScriptsUrl() . "admin/api-tester/controllers/api-tester.js", 'admin/mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/api-tester/services/api-tester.js', $registry->getScriptsUrl() . "admin/api-tester/services/api-tester.js", 'admin/mainApp', $registry->getPluginVersion() );

			wp_enqueue_script( 'admin/synchronizer/controllers/synchronizer.js', $registry->getScriptsUrl() . "admin/synchronizer/controllers/synchronizer.js", 'admin/mainApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/synchronizer/services/synchronizer.js', $registry->getScriptsUrl() . "admin/synchronizer/services/synchronizer.js", 'admin/mainApp', $registry->getPluginVersion() );
		} else {
			wp_enqueue_script( 'admin/mainApp', $registry->getScriptsUrl() . "main.min.js", 'angular', $registry->getPluginVersion() );
		}

		wp_localize_script( 'admin/mainApp', 'WooClover', array(
		    'adminUrl' => admin_url(),
		    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		    'viewsUrl' => $registry->getViewsUrl(),
		    'ajaxLoadingPath' => $registry->getImagesUrl() . 'ajax-loader.gif',
		    'isDevEnv' => $registry->isDevEnv(),
		    'viewHandlerUrl' => $registry->getWebSiteUrl() . "/" . $registry->getAdminViewHandlerUrl()
		) );
	}

	public function showApp() {

		$registry = \Wooclover\Core\Settings\WcRegistry::instance();


		$fullPath = $registry->getPluginDir() . "index.html";
		$content = \Wooclover\Core\Loader::getFileContent( $fullPath );

		echo $content;
	}

}
