(function(jQuery) {
    //checkUserPostcode

    function checkPostCode(type)
    {        
        result = jQuery(".form-row#" + type + "_postcode_field").length > 0
                 && jQuery("#" + type + "_postcode").val() != false
                 && jQuery("#" + type + "_country").length > 0
                 && jQuery("#" + type + "_country").val() != false;
           
        if (result) {

            showBlockUi("form.checkout");
            
            var data = {
                action: 'valid_post_code',
                country: jQuery("#" + type + "_country").val(),
                postCode: jQuery("#" + type + "_postcode").val()
            };

            jQuery.post(fesiCheckoutSteps.ajaxurl, data, function(response) {
                if (response == false) {
                   jQuery("#" + type + "_postcode").parent().addClass("pluginator-wizard-post-code-error");
                   jQuery("#" + type + "_postcode").parent().removeClass("woocommerce-validated").addClass("woocommerce-invalid woocommerce-invalid-required-field");
                } else {
                    jQuery("#" + type + "_postcode").parent().removeClass("pluginator-wizard-post-code-error");
                }
                hideBlockUi("form.checkout");
            })
        }
    }
    
    checkPostCode('billing');
    checkPostCode('shipping');
    
    function showBlockUi(element)
    {
        jQuery(element).fadeTo("400","0.6").block(
            {
                message:null,
                overlayCSS:
                {
                    background:"transparent url('" + fesiCheckoutSteps.imagesUrl + "ajax-loader.gif') no-repeat center",
                    opacity:.6
                }
            }
        );
    } // end showBlockUi
    
    function hideBlockUi(element)
    {
        jQuery(element).unblock().fadeTo("400","1");
    } // end hideBlockUi
        
    jQuery('body').on('change', '#billing_country', function() 
    {
        checkPostCode('billing');
    });
    
    jQuery('body').on('change', '#billing_postcode', function() 
    {
        checkPostCode('billing');
    });
    

    jQuery('body').on('change', '#shipping_country', function() 
    { 
        checkPostCode('shipping');
    });
    
    jQuery('body').on('change', '#shipping_postcode', function() 
    {
        checkPostCode('shipping');
    });

    // Init Wizard

    if (fesiCheckoutSteps.isAuthorizedUser == false && jQuery.inArray('login', fesiCheckoutSteps.disableSteps) < 0) {
       var nextButtonTitle = fesiCheckoutSteps.noAccountButton
    } else{
       var nextButtonTitle = fesiCheckoutSteps.nextButton
    }
    var nextButtonTitle
    
    function checkTermsAndConditionsField(currentIndex)
    {
        jQuery('#pluginator-checkout-steps-wizard-p-' + currentIndex +' p.form-row.terms').removeClass("pluginator-error");
        
        if (jQuery('#pluginator-checkout-steps-wizard-p-' + currentIndex +' p.form-row.terms').length <= 0) {
            return true; 
        }
        
        errorStatus = false;
        
        jQuery('#pluginator-checkout-steps-wizard-p-' + currentIndex +' p.form-row.terms').each(function() {
            if (jQuery(this).find("input[type=checkbox]").is(':checked') == true) {
                return;
            }
            
            jQuery(this).addClass("pluginator-error");
            errorStatus = true;
        });

        return !errorStatus;
    }
    
    
    function validationForm(currentIndex)
    {
        var validStatus = true;
        
        if (typeof fesiCheckoutSteps.requireFieldsNotify != "undefined") {
            addLabelToRequiredFields();
        }
        
        termsAndConditionsValidStatus = checkTermsAndConditionsField(currentIndex)
         
        var selector = '#pluginator-checkout-steps-wizard-p-' + currentIndex + ' p.form-row';

        requuireValidStatus = validateFields(currentIndex, '');
        
        var selector = '#pluginator-checkout-steps-wizard-p-' + currentIndex + ' p.form-row.validate-email';
        
        emailValidStatus = validateFields(currentIndex, 'validate-email');
        
        var selector = '#pluginator-checkout-steps-wizard-p-' + currentIndex + ' p.form-row.validate-phone';
        
        phoneValidStatus = validateFields(currentIndex, 'validate-phone');
        
        var selector = '#pluginator-checkout-steps-wizard-p-' + currentIndex + ' p.form-row.validate-postcode';
        
        postcodeValidStatus = validateFields(currentIndex, 'validate-postcode');
        
        validStatus = requuireValidStatus && emailValidStatus && phoneValidStatus && postcodeValidStatus;
        
        return validStatus && termsAndConditionsValidStatus;
    }
    
    function validateFields(currentIndex, type)
    {
        var validStatus = true;
        
        if (type != '') {
            type = '.' + type;
        }
        
        var selector = '#pluginator-checkout-steps-wizard-p-' + currentIndex + ' ' + 'p.form-row' + type;
        
        jQuery(selector).each(function(key) {
            if (type == '') {
                valid = requireFieldsValidate(this)

            } else if(type == '.validate-email') {
                valid = emailFieldsValidate(this);
            } else if(type == '.validate-phone') {
                valid = phoneFieldsValidate(this);
            } else if(type == '.validate-postcode') {
                valid = postcodeFieldsValidate(this);
            }
            
            if (!valid) {
                validStatus = false;
            }
        });

        return validStatus;
    } // end validateFields
    
    function getCurrentIndex()
    {
        var selector = jQuery("#pluginator-checkout-steps-wizard .content .current");
        var currentIndex = selector.attr('id');
        
        currentIndex = currentIndex.replace('pluginator-checkout-steps-wizard-h-', '');
        currentIndex = parseInt(currentIndex);
        
        return currentIndex;
    } // end getCurrentIndex
    
    function requireFieldsValidate(selector)
    {
        var validStatus = true;

        var selectorId = jQuery(selector).attr("id");

        if (!selectorId) {
            return validStatus;
        }
        
        selectorId = selectorId.substring(0, selectorId.length - 6);
        
        if (jQuery(selector).hasClass('validate-required') && isVisibleField(selector)) {
             if (jQuery('#' + selectorId).val() == false) {
                    validStatus = false;
                    jQuery(selector).addClass("pluginator-invalid-field");
                    jQuery('#' + selectorId).next("label.pluginator-require-field").addClass("pluginator-active");
                    jQuery('#' + selectorId).addClass("pluginator-invalid");
             } else {
                jQuery(selector).removeClass("pluginator-invalid-field");
                jQuery('#' + selectorId).next("label.pluginator-require-field").removeClass("pluginator-active");
                jQuery('#' + selectorId).removeClass("pluginator-invalid");
             }
        }    

        return validStatus;
    } // end requireFieldsValidate
    
    function emailFieldsValidate(selector)
    {
        var validStatus = true;

        var selectorId = jQuery(selector).attr("id");

        if (!selectorId) {
            return validStatus;
        }

        selectorId = selectorId.substring(0, selectorId.length - 6);

        if (isVisibleField(selector)) {
                                                                     
             if (jQuery('#' + selectorId).val() != false && !isEmail(jQuery('#' + selectorId).val())) {
                    validStatus = false;
                    jQuery(selector).addClass("pluginator-invalid-email-field");
                    jQuery('#' + selectorId).addClass("pluginator-invalid-email");
             } else {
                jQuery(selector).removeClass("pluginator-invalid-email-field");
                jQuery('#' + selectorId).removeClass("pluginator-invalid-email");
             }
        }    

        return validStatus;
    } // end emailFieldsValidate
    
    function phoneFieldsValidate(selector)
    {
        var validStatus = true;

        var selectorId = jQuery(selector).attr("id");

        if (!selectorId) {
            return validStatus;
        }

        selectorId = selectorId.substring(0, selectorId.length - 6);
        


        if (isVisibleField(selector)) {
             if (jQuery('#' + selectorId).val() != false && !isPhone(jQuery('#' + selectorId).val())) {
                    validStatus = false;
                    jQuery(selector).addClass("pluginator-invalid-phone-field");
                    jQuery('#' + selectorId).addClass("pluginator-invalid-phone");
             } else {
                jQuery(selector).removeClass("pluginator-invalid-phone-field");
                jQuery('#' + selectorId).removeClass("pluginator-invalid-phone");
             }
        }    

        return validStatus;
    } // end phoneFieldsValidate
    
    function postcodeFieldsValidate(selector)
    {
        var validStatus = true;

        var selectorId = jQuery(selector).attr("id");

        if (!selectorId) {
            return validStatus;
        }

        selectorId = selectorId.substring(0, selectorId.length - 6);

        if (isVisibleField(selector)) {
             if (jQuery('#' + selectorId).val() != false && !isValidPostcode(selector)) {
                    validStatus = false;
                    jQuery(selector).addClass("pluginator-invalid-postcode-field");
                    jQuery('#' + selectorId).addClass("pluginator-invalid-postcode");
             } else {
                jQuery(selector).removeClass("pluginator-invalid-postcode-field");
                jQuery('#' + selectorId).removeClass("pluginator-invalid-postcode");
             }
        }    

        return validStatus;
    } // end postcodeFieldsValidate
    
    function isValidPostcode(selector)
    {
         return !jQuery(selector).hasClass('pluginator-wizard-post-code-error');
    } // end isValidPostcode
    
    function isEmail(email)
    {

        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    } // end isEmail
    
    function isPhone(phone)
    {
        phone = phone.replace(/[\s\#0-9_\-\+\(\)]/g, '');
        phone = jQuery.trim(phone);
        
        return phone == '';
    } // end isPhone
    
    function isVisibleField(selector)
    {
        var isFieldVisible = jQuery(selector).css('display') != 'none';
        
        var isVisibleParents = true;
        
        jQuery(selector).parents().each(function () {
            if (jQuery(this).css('display') == 'none') {
                isVisibleParents = false;
                return false;
            }
        });

        return isFieldVisible && isVisibleParents;
    } // end isVisibleField
    
    
    jQuery('body').on('change', '.pluginator-invalid', function() 
    {
        var selector = jQuery(this).parent();

        requireFieldsValidate(selector);
    });
    
    jQuery('body').on('change', '.pluginator-invalid-phone', function() 
    {
        var selector = jQuery(this).parent();

        phoneFieldsValidate(selector);
    });
    
    jQuery('body').on('change', '.pluginator-invalid-postcode', function() 
    {
        var selector = jQuery(this).parent();

        postcodeFieldsValidate(selector);
    });
    
    jQuery('body').on('change', '.pluginator-invalid-email', function() 
    {
        var selector = jQuery(this).parent();

        emailFieldsValidate(selector); 
    });
    
    //Wizard Orientation
    if (typeof fesiCheckoutSteps.stepsOrientation != "undefined") {
        var stepsOrientation = fesiCheckoutSteps.stepsOrientation
    } else {
        var stepsOrientation = 'horizontal' 
    }

    jQuery("#pluginator-checkout-steps-wizard").steps(
        {
            transitionEffectSpeed: 500,
            startIndex: 0,
            autoFocus: true,
            enableAllSteps: false,
            stepsOrientation: stepsOrientation,
            transitionEffect: parseInt(fesiCheckoutSteps.transitionEffect),
            titleTemplate: fesiCheckoutSteps.titleTemplate,
            cssClass: "pluginator-wizard",
            labels: {
                cancel: "Cancel",
                pagination: "Pagination",
                finish: fesiCheckoutSteps.finishButton,
                next: nextButtonTitle,
                previous: fesiCheckoutSteps.previousButton,
                loading: "Loading ..."
            },
            onFinishing: function (event, currentIndex) {
                 return validationForm(currentIndex); 
            }, 
            onFinished: function (event, currentIndex) {
                jQuery("#place_order").click();
            },
            onStepChanged: function (event, currentIndex, priorIndex)
            {
                fixChosenContainer();

                if (typeof fesiCheckoutSteps.hideComplitedSteps != "undefined") {
                    jQuery('#pluginator-checkout-steps-wizard-t-' + currentIndex).parent().removeClass('pluginator-hide-completed');                
                    changeTabWidth();
                }
                
                resizeContentBlock(currentIndex);
                
                if (currentIndex == 0 && fesiCheckoutSteps.isAuthorizedUser == false && jQuery.inArray('login', fesiCheckoutSteps.disableSteps) < 0) {
                    jQuery('#pluginator-checkout-steps-wizard a[href="#next"]').html(fesiCheckoutSteps.noAccountButton);
                    jQuery('#pluginator-checkout-steps-wizard a[href="#previous"]').hide();
                } else {
                    jQuery('#pluginator-checkout-steps-wizard a[href="#next"]').html(fesiCheckoutSteps.nextButton);
                    jQuery('#pluginator-checkout-steps-wizard a[href="#previous"]').show();
                }
            },
            onStepChanging: function (event, currentIndex, newIndex)
            {
                jQuery("body").trigger( "onStepChangingWizardInit");
                    
                if (newIndex < currentIndex ) {
                    prpareToResizeContentBlock(currentIndex, newIndex);
                    return true;
                }
                
                if (validationForm(currentIndex) == true) {
                    prpareToResizeContentBlock(currentIndex, newIndex);
                    if (typeof fesiCheckoutSteps.hideComplitedSteps != "undefined") {
                        jQuery('#pluginator-checkout-steps-wizard-t-' + currentIndex).parent().addClass('pluginator-hide-completed');                
                        changeTabWidth();
                    }
                    return true;
                }
                
                return false;
            }
        }
    );
    
    function prpareToResizeContentBlock(index, newIndex)
    {
        var height = jQuery('#pluginator-checkout-steps-wizard-p-' + index).outerHeight();
        jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard .content').css('height', height + 'px');
        jQuery('#pluginator-checkout-steps-wizard-p-' + index).css('position', 'absolute');
        jQuery('#pluginator-checkout-steps-wizard-p-' + newIndex).css('position', 'absolute');
    } // end resizeContentBlock
    
    function resizeContentBlock(index)
    {
        var height = jQuery('#pluginator-checkout-steps-wizard-p-' + index).outerHeight();
        jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard .content').animate(
            {
                height:height + 'px'
            },
            {
                duration:500,
                complete:  function() {
                    jQuery('#pluginator-checkout-steps-wizard-p-' + index).css('position', 'relative');
                    jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard .content').css('height', '');
                }
            }
        );
        
    } // end resizeContentBlock
    
    
    /*form  chosen-container fix (woocommerce country select)*/
    var fixChosenContainerResult = false;
    function fixChosenContainer()
    {
        if (fixChosenContainerResult != false) {
            return true;    
        }

        jQuery('form.checkout .chosen-container').css('width', '100%');
        fixChosenContainerResult = true;
    }
    
    function changeTabWidth()
    {
        if (jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard').hasClass('vertical')) {
            return false;
        }
        
        countAllSteps = jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard .steps  li').size();
        countHidenSteps = jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard .steps  li.pluginator-hide-completed').size();
        var maxWidth = 100;
        var tabWidth = Math.round(maxWidth/(countAllSteps - countHidenSteps));
        jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard .steps  li').css('width', tabWidth + '%');
    }
    
    var height = jQuery('#pluginator-checkout-steps-wizard-p-0').css('position', 'relative');
    jQuery('#pluginator-checkout-steps-wizard.pluginator-wizard .content').css('height', height + 'px');
    
    jQuery("#pluginator-checkout-steps-wizard").show();
    jQuery('form[name="checkout"]').css('visibility', 'visible');

    
    if (fesiCheckoutSteps.isAuthorizedUser == false && jQuery.inArray('login', fesiCheckoutSteps.disableSteps) < 0) {
       
    }

    // User Login
    function appendErrorRequiredClasses(selector)
    {
        jQuery('form.login input#' + selector).parent().removeClass("woocommerce-validated");
        jQuery('form.login input#' + selector).parent().addClass("woocommerce-invalid woocommerce-invalid-required-field");
        jQuery('form.login input#' + selector).parent().addClass("validate-required");
    }
    
    jQuery('form.login').submit(function() 
    {
        var form = 'form.login';
        var error = false;
        
        if (jQuery(form + ' input#username').val() == false) {
            error = true;
            appendErrorRequiredClasses('username');
        }
        
        if (jQuery(form + ' input#password').val() == false) {
           error = true;
           appendErrorRequiredClasses('password');
        }
        
        if (error != false)
        {
            return false;
        }
        
        var formSelector = this;
        showBlockUi(formSelector);

        if (jQuery(form + ' input#rememberme').is(':checked') == false) {
            rememberme = false;
        } else {
            rememberme = true; 
        }

        var data = {
            action: 'login_user_wizard_step',
            username: jQuery(form + ' input#username').val(),
            password: jQuery(form + ' input#password').val(),
            rememberme: rememberme
        };
        
         jQuery.post(fesiCheckoutSteps.ajaxurl, data, function(response) {
            if (response == 'successfully') {
                location.reload();
            } else {
                jQuery('div.pluginator-wizard-login-error').remove();
                jQuery('form.login').prepend(response);
                hideBlockUi(formSelector);
            }
        })
        return false;
    });
    
    //terms and Conditions
    jQuery('#terms').click(function() 
    {
        if (jQuery(this).is(':checked') == true) {
            jQuery(this).parent().removeClass("pluginator-error");
        } else {
            jQuery(this).parent().addClass("pluginator-error");
        }
    });
    
    if (jQuery.inArray('shipping', fesiCheckoutSteps.disableSteps) >= 0 && jQuery('div.woocommerce-shipping-fields #ship-to-different-address-checkbox').is(':checked')) {
        jQuery('div.woocommerce-shipping-fields #ship-to-different-address-checkbox').click();  
    }

    function addLabelToRequiredFields()
    {
        var selector = 'p.form-row';
        
        jQuery(selector).each(function(key) {
                var selectorId = jQuery(this).attr("id");
                if (!selectorId) {
                    return;
                }

                selectorId = selectorId.substring(0, selectorId.length - 6);
                
                if (jQuery(this).find('label.pluginator-require-field').length < 1)
                {
                    jQuery('#' + selectorId).after( "<label class='pluginator-require-field'>"+ fesiCheckoutSteps.requireFieldsText +"</label>" );
                }   
        });
    }
    
    jQuery('.pluginator-wizard-step-view-order').on('change', 'table.shop_table .shipping input', function() 
    {      
       showBlockUi('table.shop_table');
    });
    
    jQuery('.pluginator-wizard-step-view-order').on('change', 'table.shop_table .shipping select', function() 
    {      
       showBlockUi('table.shop_table');
    });
}(jQuery));