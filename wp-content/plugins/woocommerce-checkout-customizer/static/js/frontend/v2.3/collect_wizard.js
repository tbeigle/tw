(function(jQuery) {

    jQuery(".woocommerce-info a.showlogin").parent().detach();
    jQuery(".woocommerce").css('visibility', 'visible');
    
    if (jQuery.inArray('login', fesiCheckoutSteps.disableSteps) < 0 && fesiCheckoutSteps.isAuthorizedUser != true) {
        jQuery("form.login").appendTo('.pluginator-wizard-step-login');
        jQuery(".pluginator-wizard-step-login form.login").show();
    }
    
    jQuery("#prepare-pluginator-checkout-steps-wizard .prepare-pluginator-wizard-step-billing").appendTo('.pluginator-wizard-step-billing');

    if (jQuery.inArray('shipping', fesiCheckoutSteps.disableSteps) < 0) {
        jQuery("#prepare-pluginator-checkout-steps-wizard .prepare-pluginator-wizard-step-shipping").appendTo('.pluginator-wizard-step-shipping');
    }
    
    if (jQuery.inArray('reviewOrder', fesiCheckoutSteps.disableSteps) < 0) {
    	jQuery("#prepare-pluginator-checkout-steps-wizard .prepare-pluginator-wizard-step-view-order").appendTo('.pluginator-wizard-step-view-order');
    }

    if (jQuery.inArray('payment', fesiCheckoutSteps.disableSteps) < 0) {
        jQuery("#order_review").appendTo('.pluginator-wizard-step-payment');
    } else {
        jQuery("#order_review").hide();
        jQuery("#place_order").hide();
        jQuery("#place_order").appendTo(".woocommerce .checkout");
    }

    if (jQuery('#order_review p.form-row.terms').length > 0 && jQuery('#order_review p.form-row.terms').length == jQuery('p.form-row.terms').length) {
        jQuery('#order_review p.form-row.terms').appendTo('.pluginator-wizard-step-' + fesiCheckoutSteps.termsLocation);
    }
    
    if (jQuery("#pluginator-checkout-steps-wizard .form-row.terms").length > 1) {
        jQuery("#order_review .form-row.terms").remove();
    }

    jQuery('.pluginator-wizard-step-payment').on('change', '#payment input[name="payment_method"]', function() 
    {      
        var selector = jQuery(this).attr('id');
        jQuery('.payment_box').slideUp(200);
        jQuery('.payment_box.' + selector).slideDown(200);
    });
    
    if (jQuery('.pluginator-wizard-step-custom').length > 0) {
        jQuery('.pluginator-wizard-step-custom').append( "<div class='pluginator-woocommerce-custom-fields'></div>" );
        
        jQuery.each(fesiCheckoutSteps.customFields, function() {
            jQuery("#" + this + "_field").appendTo('.pluginator-woocommerce-custom-fields');
        });
    }

    jQuery("div#pluginator-checkout-steps-wizard");
    jQuery(".woocommerce .checkout").html('');
    
    var pluginatorWizardBlock = jQuery("div#pluginator-checkout-steps-wizard");
    pluginatorWizardBlock.prependTo(".woocommerce .checkout");
    
    jQuery("#order_review_heading").detach();
    jQuery(".woocommerce-billing-fields h3").detach();
}(jQuery));