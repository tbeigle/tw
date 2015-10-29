jQuery(document).ready(function() 
{
    jQuery('input[data-event=visible]').change(function(){ 
       
        var className = jQuery(this).attr("name") + '-' + jQuery(this).data('event');

        if(jQuery(this).attr("checked")){ 
            
            jQuery('.'+className).fadeIn();         
        } else { 
            jQuery('.'+className).fadeOut(100);
        } 
    });

    jQuery('select[data-event=visible]').change(function(){ 
        var className = jQuery(this).attr("name") + '-' + jQuery(this).data('event');

        if(jQuery(this).val() == 'disable' || (jQuery(this).val() == 'horizontal' && jQuery(this).attr('name') == 'stepsOrientation')){ 
            jQuery('.'+className).fadeOut(100);      
        } else { 
            jQuery('.'+className).fadeIn();
        }
    });
    
        jQuery('.pluginator-checkout-steps-wizard-help-tip').poshytip({
            className: 'tip-twitter',
            showTimeout:100,
            alignTo: 'target',
            alignX: 'center',
            alignY: 'bottom',
            offsetY: 5,
            allowTipHover: false,
            fade: true,
            slide: false
        });
        
        jQuery('select[name = "themeSelector"]').change(function(){
            jQuery('form[name = "themes"]').submit();
        });
        
    jQuery('input[data-event=change-slider]').change(function(){
        var sliderSelector = jQuery(this).attr('name');
        var value = jQuery(this).val(); 
        
        jQuery( "#pluginator-slider-" + sliderSelector).slider("value", value);
    });
}); 