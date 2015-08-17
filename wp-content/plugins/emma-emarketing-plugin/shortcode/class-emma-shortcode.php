<?php

include_once( EMMA_EMARKETING_PATH . '/class-emma-form.php' );

// Register shortcode [emma_form]
add_shortcode( 'emma_form', 'emma_form_shortcode' );

function emma_form_shortcode() {

    // call the dynamic stylesheet
    $emma_style = new Emma_Style();
    // dump it in the footer.
    add_action( 'wp_footer', array( $emma_style, 'output' ), 10 );

    $emma_form = new Emma_Form();

	$returned = $emma_form->generate_form();
	
	// this just cracks me up.
	return $returned;

}