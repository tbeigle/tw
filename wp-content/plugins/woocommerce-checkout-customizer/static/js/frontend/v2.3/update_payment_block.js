jQuery(document).ready(function( jQuery ) {

    // only fire after the checkout ajax has finished loading
    jQuery( 'body' ).bind( 'updated_checkout', function() {
        if (jQuery('#order_review p.form-row.terms').length > 0 && jQuery('#order_review p.form-row.terms').length == jQuery('p.form-row.terms').length) {
            jQuery('#order_review p.form-row.terms').appendTo('.pluginator-wizard-step-' + fesiCheckoutSteps.termsLocation);
        }
        
        if (jQuery("#pluginator-checkout-steps-wizard .form-row.terms").length > 1) {
            jQuery("#order_review .form-row.terms").remove();
        }
        jQuery("body").trigger( "onRemoveRepeatingPaymentBlock");

    });

});
