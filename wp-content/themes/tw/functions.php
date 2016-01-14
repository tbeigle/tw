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

/*
 * tw_enqueue_parent_styles
 *
 * Adds the css from the parent theme
 */
add_action( 'wp_enqueue_scripts', 'tw_enqueue_parent_styles' );
function tw_enqueue_parent_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

/*
 * tw_load_scripts
 *
 * Loads the theme scripts
 */
function tw_load_scripts(){
  wp_register_script(
    'tw-scripts',
    get_stylesheet_directory_uri() . '/js/scripts.js',
    array( 'jquery', 'icheck' )
  );
  wp_enqueue_script( 'tw-scripts' );
}
add_action('wp_enqueue_scripts', 'tw_load_scripts');

/*
 * Register some sidebars
 */
register_sidebar( array(
	'name' => __( 'Order Now', 'tw' ),
	'id' => 'ordernow',
	'description' => __( 'Only used for Woocommerce Pages', 'tw' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
) );

register_sidebar( array(
	'name' => __( 'Login', 'tw' ),
	'id' => 'login-form',
	'description' => __( 'Login form', 'tw' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
) );

/*
 * is_woocommerce_activated
 *
 * Check if Woocommerce is installed and enabled
 */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) {
			return true;
		} else {
			return false;
		}
	}
}

/*
 * Theme + Woocommerce integration if woocommerce is activated
 */
if ( is_woocommerce_activated() ) {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	add_action('woocommerce_before_main_content', 'tw_woocommerce_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'tw_woocommerce_wrapper_end', 10);

	function tw_woocommerce_wrapper_start() {
	  echo '<div id="left">';
	}

	function tw_woocommerce_wrapper_end() {
	  echo '</div>';
	}
}

/*
 * tw_woocommerce_support
 *
 * Declare theme support for Woocommerce
 */
add_action( 'after_setup_theme', 'tw_woocommerce_support' );
function tw_woocommerce_support() {
	if ( is_woocommerce_activated() ) {
    add_theme_support( 'woocommerce' );
	}
}

/*
 * tw_woocommerce_body_class
 *
 * Adds a woocommerce-active class to the body tag when Woocommerce is activated
 */
add_filter( 'body_class', 'tw_woocommerce_body_class' );
function tw_woocommerce_body_class( $classes ) {
	if ( is_woocommerce_activated() ) {
		$classes[] = 'woocommerce-active';
	}

	return $classes;
}

/*
 * Remove product images from Woocommerce
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_filter( 'woocommerce_cart_item_thumbnail', '__return_empty_string' );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

/*
 * tw_remove_related_products
 *
 * Clear the query arguments for related products so none show.
 * Add this code to your theme functions.php file.
 */
add_filter('woocommerce_related_products_args','tw_remove_related_products', 10);
function tw_remove_related_products( $args ) {
	return array();
}

/*
 * tw_remove_wc_breadcrumbs
 *
 * Remove the Woocommerce breadcrumb
 */
add_action( 'init', 'tw_remove_wc_breadcrumbs' );
function tw_remove_wc_breadcrumbs() {
  remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

/*
 * tw_redirect_on_disabled_shop
 *
 * Redirect to a new page if the Woocommerce Shop has been disabled
 */
add_action( 'wp', 'tw_redirect_on_disabled_shop' );
function tw_redirect_on_disabled_shop() {
  if(class_exists('WC_Catalog_Visibility_Options')) {
    global $wp, $wc_cvo;

    $current_uri = str_replace(get_site_url(), '', home_url(add_query_arg(array(),$wp->request)));

    // check for urls that contain order-now, cart and checkout and redirect if the shop is disabled
    if ( $wc_cvo->setting( 'wc_cvo_prices' ) != 'enabled' || $wc_cvo->setting( 'wc_cvo_atc' ) != 'enabled' ) {
      if ( preg_match('/order-now/', $current_uri) || preg_match('/cart/', $current_uri) || preg_match('/checkout/', $current_uri) ) {
        wp_redirect( '/shop-disabled' );
        exit;
      }
    }
  }
}

/*
 * tw_remove_prices
 *
 * Remove the Woocommerce prices from the product archives
 */
add_filter( 'woocommerce_variable_sale_price_html', 'tw_remove_prices', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'tw_remove_prices', 10, 2 );
add_filter( 'woocommerce_get_price_html', 'tw_remove_prices', 10, 2 );

function tw_remove_prices( $price ) {
 // do nothing
}

/*
 * tw_show_price_and_stock_after_description
 *
 * Add the price and stock after the description
 */
add_action( 'tw_woocommerce_price_after_description', 'tw_show_price_and_stock_after_description', 10 );
function tw_show_price_and_stock_after_description() {
  global $product, $wc_cvo;

  if ($product->product_type == 'simple') {
		if ( ($wc_cvo->setting( 'wc_cvo_prices' ) == 'secured' && !catalog_visibility_user_has_access()) || $wc_cvo->setting( 'wc_cvo_prices' ) == 'disabled' ) {
			echo '<span class="price"></span>'; // hide the price when the shop is disabled
		}
		else {
  		echo '<span class="price">' . woocommerce_price($product->price) . '</span>';
		}
  }

  if ($product->stock_status == 'instock') {
    echo '<span class="stock">' . number_format($product->stock, 0) . ' in stock</span>';
  }
  else {
    echo '<span class="stock">Out of stock</span>';
  }
}

/**
 * Hide Stock message
 */
add_filter( 'woocommerce_get_availability', 'tw_hide_stock', 1, 2);
function tw_hide_stock( $availability, $_product ) {
  // do nothing
}

/*
 * Removes "Showing results..." in the product archives
 */
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/*
 * Remove tabs from product details page
 */

add_filter( 'woocommerce_product_tabs', 'tw_remove_product_tabs', 98 );
function tw_remove_product_tabs( $tabs ) {
	//unset( $tabs['description'] ); // Remove the description tab
	//unset( $tabs['reviews'] ); // Remove the reviews tab
	unset( $tabs['additional_information'] ); // Remove the additional information tab

	return $tabs;
}

/*
 * tw_show_tags_in_archive
 *
 * Displays the product tags in the product archives
 */
add_action( 'woocommerce_after_shop_loop_item_title', 'tw_show_tags_in_archive', 30 );
function tw_show_tags_in_archive() {
  global $product;

  echo $product->get_tags( ' ', '<span class="tagged_as">', '</span>' );
}

/*
 * tw_hide_wc_archive_title
 *
 * Removes the title from the product archives
 */
add_filter( 'woocommerce_page_title', 'tw_remove_wc_archive_title');
function tw_remove_wc_archive_title( $page_title ) {
  // do nothing
}

/*
 * tw_load_icheck
 *
 * Loads the iCheck library to customize and theme the radio boxes in the product archives
 */
function tw_load_icheck(){
  wp_register_script(
    'icheck',
    get_stylesheet_directory_uri() . '/js/icheck/icheck.min.js',
    array( 'jquery' )
  );
  wp_enqueue_script( 'icheck' );
}
add_action('wp_enqueue_scripts', 'tw_load_icheck');

/*
 * tw_enqueue_icheck_styles
 *
 * Adds the css from the iCheck css
 */
add_action( 'wp_enqueue_scripts', 'tw_enqueue_icheck_styles' );
function tw_enqueue_icheck_styles() {
  wp_enqueue_style( 'icheck', get_stylesheet_directory_uri() . '/js/icheck/skins/square/purple.css' );
}

/*
 * tw_load_jqueryvalidate
 *
 * Loads the jQuery Validate library
 */
function tw_load_jqueryvalidate(){
  wp_register_script(
    'validate',
    get_stylesheet_directory_uri() . '/js/validate/jquery.validate.min.js',
    array( 'jquery' )
  );
  wp_enqueue_script( 'validate' );

  wp_register_script(
    'additional-methods',
    get_stylesheet_directory_uri() . '/js/validate/additional-methods.min.js',
    array( 'jquery', 'validate' )
  );
  wp_enqueue_script( 'additional-methods' );
}
add_action('wp_enqueue_scripts', 'tw_load_jqueryvalidate');

/*
 * tw_load_steps
 *
 * Loads the Steps library to customize checkout page
 */
function tw_load_steps(){
  wp_register_script(
    'steps',
    get_stylesheet_directory_uri() . '/js/steps/jquery.steps.min.js',
    array( 'jquery' )
  );
  wp_enqueue_script( 'steps' );
}
add_action('wp_enqueue_scripts', 'tw_load_steps');

/*
 * tw_enqueue_icheck_styles
 *
 * Adds the css from the iCheck css
 */
add_action( 'wp_enqueue_scripts', 'tw_enqueue_steps_styles' );
function tw_enqueue_steps_styles() {
  wp_enqueue_style( 'steps', get_stylesheet_directory_uri() . '/js/steps/jquery.steps.css' );
}

/*
 * tw_show_product_categories_hero
 *
 * Displays a big image at the top of every product archive page
 */
add_action( 'woocommerce_archive_description', 'tw_show_product_categories_hero', 0 );
function tw_show_product_categories_hero() {
  echo '<img src="' . get_stylesheet_directory_uri() . '/images/ordernow_hero.png"" class="img-responsive product-cat-hero">';
}

/*
 * tw_show_product_categories
 *
 * Displays the product categories in the product archives
 */
add_action( 'woocommerce_before_shop_loop', 'tw_show_product_categories', 30 );
function tw_show_product_categories() {
  echo '<div class="category-tabs">';
  echo '<a href="/order-now/main"><img src="' . get_stylesheet_directory_uri() . '/images/cat_mains.png" class="product-cat"></a>';
  echo '<a href="/order-now/sides"><img src="' . get_stylesheet_directory_uri() . '/images/cat_sides.png" class="product-cat"></a>';
  echo '<a href="/order-now/extra"><img src="' . get_stylesheet_directory_uri() . '/images/cat_extras.png" class="product-cat"></a>';
  echo '</div>';
}

/*
 * tw_redirect_ordernow_page
 *
 * Redirects the Order Now page to the Main category
 */
add_action( 'wp', 'tw_redirect_ordernow_page' );
function tw_redirect_ordernow_page() {
  global $wp;
  $current_uri = str_replace(get_site_url(), '', home_url(add_query_arg(array(),$wp->request)));

  if ( $current_uri == '/order-now' ) {
    wp_redirect( '/order-now/main', 301 );
    exit;
  }
}

/*
 * tw_coupon_code_message
 *
 * Changes the "coupon code" to "gift card code"
 */
add_filter( 'woocommerce_checkout_coupon_message', 'tw_coupon_code_message' );
function tw_coupon_code_message() {
  return __( 'Have a gift card code?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your gift card code', 'woocommerce' ) . '</a>';
}

/*
 * tw_login_logo
 *
 * Replaces the default WP logo in the login page for our own
 */
function tw_login_logo() { ?>
  <style type="text/css">
      .login h1 a {
        background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/login-logo.png);
      }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'tw_login_logo' );

/*
 * tw_login_logo_url
 *
 * Replaces the default WP link in the login pages for a link to our homepage
 */
function tw_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'tw_login_logo_url' );

/*
 * tw_login_logo_url
 *
 * Replaces the default WP logo title in the login pages
 */
function tw_login_logo_url_title() {
  return 'Tin Wings';
}
add_filter( 'login_headertitle', 'tw_login_logo_url_title' );