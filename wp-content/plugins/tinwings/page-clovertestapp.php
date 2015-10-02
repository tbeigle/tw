<?php
$params = array();
$params['args'] = get_query_var('args', '');
$params['code'] = get_query_var('code', '');
$params['merchant_id'] get_query_var('merchant_id', '');
$params['employee_id'] get_query_var('employee_id', '');
$params['client_id'] get_query_var('client_id', '');

switch ($params['args']) {
  case 'listen': {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $twc = new TinWingsClover($_POST);
    }
    wp_redirect(home_url());
    exit;
    break;
  }
  case 'oauth-auth': {
    break;
  }
  case 'oauth-receive': {
    break;
  }
  default: {
    
    break;
  }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="robots" content="noindex, nofollow" />

	<title>Clover Test App</title>
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
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
</div>

<!--end header-->

<div id="content">
<div id="left">
  <pre><?php print_r($params); ?></pre>
</div>
<!--end left-->

<div id="right">
<?php get_sidebar(); ?>
</div>
<!--end right-->

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

