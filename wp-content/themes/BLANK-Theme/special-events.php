<?php
/*
Template Name: Special Events
*/
?>

<?php get_header(); ?>

<div id="content" class="events">
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
<?php 
    echo do_shortcode("[metaslider id=92]"); 
?>
</div>
<!--end right-->




<div class="clear"></div>

<div id="planing">

<?php if ( is_active_sidebar( 'lets-start-planing' ) ) : ?>
<?php dynamic_sidebar( 'lets-start-planing' ); ?>
<?php endif; ?>
<div class="clear"></div>
</div>

<div class="clear"></div>

</div>
<!--end content-->

		</div>
<!--end wrapper-->
        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://arrow.scrolltotop.com/arrow5.js"></script>
<noscript>Not seeing a <a href="http://www.scrolltotop.com/">Scroll to Top Button</a>? Go to our FAQ page for more info.</noscript>
<div id="footer">
<div id="footer-wrapper">

<?php if ( is_active_sidebar( 'special-events' ) ) : ?>
<?php dynamic_sidebar( 'special-events' ); ?>
<?php endif; ?>

<?php get_footer(); ?>