<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
	<div id="content">
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<div id="right">
	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>
	</div>

	<div id="find">
<?php if ( is_active_sidebar( 'do' ) ) : ?>
<?php dynamic_sidebar( 'do' ); ?>
<?php endif; ?>
</div>

<div class="connect1">
<img src="<?php bloginfo('template_url'); ?>/images/connect.png" class="connect-with-us" />
<a href="https://www.facebook.com/pages/TinWings/211929357754" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/facebook.png" /></a>
<a href="http://www.pinterest.com/TinWings/" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/pinterest.png" /></a>
<a href="http://instagram.com/tinwings615" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/instangram.png" /></a>
</div>
</div>

<div class="clear"></div>
</div>
</div>
<!--end content-->

		</div>
<!--end wrapper-->
        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://arrow.scrolltotop.com/arrow5.js"></script>
<noscript>Not seeing a <a href="http://www.scrolltotop.com/">Scroll to Top Button</a>? Go to our FAQ page for more info.</noscript>
<div id="footer">
<div id="footer-wrapper">

<?php if ( is_active_sidebar( 'footer-home' ) ) : ?>
<?php dynamic_sidebar( 'footer-home' ); ?>
<?php endif; ?>

<?php get_footer( 'shop' ); ?>
