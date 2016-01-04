<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	
	<?php if (is_search()) { ?>
	   <meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

	<title>
		   <?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }
		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		          bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>
	</title>
	
	<link rel="shortcut icon" href="/favicon.ico">
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php if ( is_singular() ) wp_enqueue_script('comment-reply'); ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="wrapper">

<div id="top">
<img src="<?php bloginfo('template_url'); ?>/images/logo.jpg" />

<?php wp_nav_menu(array('menu' => "Main Menu")); ?>

<div class="clear"></div>
</div>
<!--end top-->
	
	

<div id="header">
<?php 
    echo do_shortcode("[metaslider id=84]"); 
?>
</div>

<!--end header-->

<div id="content">
<div id="full-width">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
		<div class="post" id="post-<?php the_ID(); ?>">

			<div class="entry">

				<?php the_content(); ?>

				<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>

			</div>

		</div>
		
		<?php // comments_template(); ?>

		<?php endwhile; endif; ?>
        
        </div>
<!--end content-->
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

<?php get_footer(); ?>