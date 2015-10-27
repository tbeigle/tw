<?php
/*
 Theme Name:   Tinwings Child Theme
 Theme URI:    http://planleft.com
 Description:  Tinwings Theme with WooCommerce integration
 Author:       Plan Left, LLC
 Author URI:   http://planleft.com
 Template:     BLANK-Theme
 Version:      1.0.0
 Tags:         black, green, white, light, dark, two-columns, three-columns, left-sidebar, right-sidebar, fixed-layout, responsive-layout, custom-background, custom-header, custom-menu, editor-style, featured-images, flexible-header, full-width-template, microformats, post-formats, rtl-language-support, sticky-post, theme-options, translation-ready, accessibility-ready, responsive-layout, infinite-scroll, post-slider, design, food, journal, magazine, news, photography, portfolio, clean, contemporary, dark, elegant, modern, professional, sophisticated, woocommerce
 Text Domain:  tw
*/

add_action( 'wp_enqueue_scripts', 'tw_enqueue_parent_styles' );
function tw_enqueue_parent_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( is_woocommerce_activated() ) {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	add_action('woocommerce_before_main_content', 'tw_woocommerce_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'tw_woocommerce_wrapper_end', 10);

	function tw_woocommerce_wrapper_start() {
	  echo '<div id="left">';
	}

	function tw_woocommerce() {
	  echo '</div>';
	}
}

add_action( 'after_setup_theme', 'tw_woocommerce_support' );
function tw_woocommerce_support() {
	if ( is_woocommerce_activated() ) {
    	add_theme_support( 'woocommerce' );
	}
}

add_filter( 'body_class', 'tw_woocommerce_body_class' );
function tw_woocommerce_body_class( $classes ) {
	if ( is_woocommerce_activated() ) {
		$classes[] = 'woocommerce-active';
	}

	return $classes;
}