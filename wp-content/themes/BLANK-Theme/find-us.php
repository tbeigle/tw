<?php
/*
Template Name: Find Us
*/
?>

<?php get_header(); ?>

<div id="content">
<div id="left">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
		<div class="post" id="post-<?php the_ID(); ?>">

			<div class="entry">

				<?php the_content(); ?>

				<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>

			</div>

			<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

		</div>
		
		<?php // comments_template(); ?>

		<?php endwhile; endif; ?>
        
        </div>
<!--end left-->


<div id="right">

<?php get_sidebar(); ?>

</div>

<div id="find">
<?php if ( is_active_sidebar( 'find' ) ) : ?>
<?php dynamic_sidebar( 'find' ); ?>
<?php endif; ?>

<div class="clear"></div>
</div>
<!--end content-->

<div class="connect1">
<img src="<?php bloginfo('template_url'); ?>/images/connect.png" class="connect-with-us" />
<a href="https://www.facebook.com/pages/TinWings/211929357754" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/facebook.png" /></a>
<a href="http://www.pinterest.com/TinWings/" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/pinterest.png" /></a>
<a href="http://instagram.com/tinwings615" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/instangram.png" /></a>
</div>
</div>

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

