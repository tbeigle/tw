<?php
	
	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
	if ( !is_admin() ) {
	   wp_deregister_script('jquery');
	   wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"), false);
	   wp_enqueue_script('jquery');
	}
	
wp_register_script('modernizer', get_bloginfo('template_directory') . "/js/modernizr.custom.06451.js");
wp_enqueue_script('modernizer');

wp_register_script('mediaquerie', get_bloginfo('template_directory') . "/js/css3-mediaqueries.js");
wp_enqueue_script('mediaquerie');
	
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
	// Declare sidebar widget zone
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar Widgets',
    		'id'   => 'sidebar-widgets',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h2>',
    		'after_title'   => '</h2>'
    	));
    }
	
			// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'twentyten' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'twentyten' ),
		'before_widget' => '<p id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</p>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	
			// Area 3, located in the footer home page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Home', 'twentyten' ),
		'id' => 'footer-home',
		'description' => __( 'Footer Home Area', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container footer-box %2$s">',
		'after_widget' => '<div class="clear"></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
				// Area 3, located in the footer what we cook page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer What We Cook', 'twentyten' ),
		'id' => 'footer-what-we-cook',
		'description' => __( 'Footer What We Cook Area', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container footer-box %2$s">',
		'after_widget' => '<div class="clear"></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
					// Area 3, located in the area Lets Start Planing. Empty by default.
	register_sidebar( array(
		'name' => __( 'Lets Start Planing', 'twentyten' ),
		'id' => 'lets-start-planing',
		'description' => __( 'Lets Start Planing Area', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
					// Area 3, located in the footer special events page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Special Events', 'twentyten' ),
		'id' => 'special-events',
		'description' => __( 'Special Events', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container footer-box %2$s">',
		'after_widget' => '<div class="clear"></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
						// Area 3, located in the area Press and Praise. Empty by default.
	register_sidebar( array(
		'name' => __( 'Press and Praise', 'twentyten' ),
		'id' => 'press-and-praise',
		'description' => __( 'Press and Praise Area', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
						// Area 3, located in the footer Press and Praise page. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Press and Praise', 'twentyten' ),
		'id' => 'footer-press-praise',
		'description' => __( 'Footer Press and Praise Area', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container footer-box %2$s">',
		'after_widget' => '<div class="clear"></div></div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
						// Area 3, located in the area Lets Talk Form. Empty by default.
	register_sidebar( array(
		'name' => __( 'Lets Talk Form', 'twentyten' ),
		'id' => 'lets-talk',
		'description' => __( 'Lets Talk Form Area', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
							// Area 3, located in the area Lets Talk Sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Lets Talk Sidebar', 'twentyten' ),
		'id' => 'lets-talk-sidebar',
		'description' => __( 'Lets Talk Sidebar Area', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	
								// Find us
	register_sidebar( array(
		'name' => __( 'Find', 'twentyten' ),
		'id' => 'find',
		'description' => __( 'find', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
									// What we do sidebar
	register_sidebar( array(
		'name' => __( 'What we do', 'twentyten' ),
		'id' => 'do',
		'description' => __( 'What we do sidebar', 'twentyten' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


?>