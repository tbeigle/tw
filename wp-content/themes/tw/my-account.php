<?php
/*
Template Name: My Account
*/
?>

<?php get_header(); ?>

<div id="content">
  <div id="left">
  	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  		<div class="post" id="post-<?php the_ID(); ?>">
  			<div class="entry">
  				<?php the_content(); ?>
  			</div>
  		</div>
  		<?php endwhile; endif; ?>
  </div>
  <div id="right">
    <img src="<?php print get_stylesheet_directory_uri() . '/images/market_hours.png' ?>" class="img-responsive">
  	<p class="market-hours-sidebar">Tuesday-Friday 11am-6pm</p>
  	<p class="location-sidebar">816 51st Avenue North<br>Nashville, Tennessee</p>
  </div>
</div>
<div class="clear"></div>
<!--end content-->

		</div>
<!--end wrapper-->

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://arrow.scrolltotop.com/arrow5.js"></script>
<noscript>Not seeing a <a href="http://www.scrolltotop.com/">Scroll to Top Button</a>? Go to our FAQ page for more info.</noscript>
<div id="footer">
<div id="footer-wrapper">

<?php if ( is_active_sidebar( 'footer-press-praise' ) ) : ?>
<?php dynamic_sidebar( 'footer-press-praise' ); ?>
<?php endif; ?>

<?php get_footer(); ?>

