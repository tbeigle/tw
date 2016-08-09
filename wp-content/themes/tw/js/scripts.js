(function($) {
  $(document).ready(function(){
    /*$('.prod-variation input').iCheck({
      checkboxClass: 'icheckbox_square-purple',
      radioClass: 'iradio_square-purple',
    });*/

    // find all the products
    var forms = $("li.product").find("form.variations_form");

    // iterate through all the products
    $.each(forms, function(i, form) {
      // the product id
      var pid = parseInt($(form).data('product_id'), 10);

      // the variations as checkboxes, we use
      // form[data-product_id='" + pid + "'] to only affect one element, not all
      var radio = "form[data-product_id='" + pid + "'] .variations input:radio";

      // is the variation checkbox checked?
      var checked = $(radio + ':checked').length;

      // hide the price if no variation is selected
      if (!checked) {
        $("form[data-product_id='" + pid + "'] .woocommerce-variation").css('visibility', 'hidden');
      }

      // if someone clicks on a variation we need to show the price
      // we just unset what we've set before
      $(radio).change(function() {
        $("form[data-product_id='" + pid + "'] .woocommerce-variation").css('visibility', '');
      });
    });

    $('#createaccount').attr('checked', true);

    var form = $("form.checkout.woocommerce-checkout");

    var checkout_steps = form.steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        enableFinishButton: false,
        titleTemplate: '#title#',
        errorClass: "invalid",
        validClass: "valid",
        onStepChanging: function (event, currentIndex, newIndex) {
            form.validate({
                ignore: ":disabled,:hidden",
                rules: {
                    billing_first_name: "required",
                    billing_last_name: "required",
                    billing_email: {
                      required: true,
                      email: true
                    },
                    billing_phone: "required",
                    billing_address_1: "required",
                    billing_city: "required",
                    billing_state: "required",
                    billing_postcode: {
                        required: true,
                        zipcodeUS: true,
                    }
                }
            });
            return form.valid();
        }
    });

    //$('.woocommerce-shipping-fields').append('<p>Special requests? Yes please! We always try to make you happy. Someone will contact you by Monday to confirm all the details & give you a price.</p>');
  });
})( jQuery );