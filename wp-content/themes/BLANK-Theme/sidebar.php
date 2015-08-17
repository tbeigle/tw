

<aside>

    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar Widgets')) : else : ?>
    
        <!-- All this stuff in here only shows up if you DON'T have any widgets active in this zone -->


    

     <?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
<?php endif; ?>

	
	<?php endif; ?>

</aside>



