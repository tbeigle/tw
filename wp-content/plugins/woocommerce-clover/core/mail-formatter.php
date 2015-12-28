<?php

namespace Wooclover\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class MailFormatter {

	public function __construct () {
		;
	}

	public static function init () {

		HookManager::instance()->addFilter( 'wp_mail', array( __CLASS__, 'processEmail' ) );
	}
 
	public static function prepareContentEmail ( $message ) {

		$registry = \GFSeoMarketingAddOn\Core\Settings\GfRegistry::instance();

		// We need to get the template file  
		$templateFile = $registry->getCurrentEmailThemePath();
		
		$templateContent = \GFSeoMarketingAddOn\Core\Loader::getFileContent( $templateFile );

		$date = current_time('mysql');
		$date = new \DateTime($date);
		$mailVariables = array(
			'year' => date( "Y" ),
			'month' => date( 'M' ),
			'date' => $date->format( 'm/d/Y'),
			'message' => $message,
			'title' => "",
			'organization_name' => $registry->getOrganizationName(),
			//'organization_logo' => $registry->getOrganizationLogoFullUrl(),
			'website_url' => $registry->getWebSiteUrl(),
			//'signature' => $registry->getSignature()
		);
		
	 
		$templateProcessor = new \GFSeoMarketingAddOn\Core\TemplateProcessor( $message, $mailVariables );

		$message = $templateProcessor->getProcessedTemplate();

		// Process the whole template
		$filledTemplate = $templateProcessor->getProcessedTemplate( $templateContent );
 		
		return $filledTemplate;
	}

}
