(function($) {
  $(document).ready(function(){
    /*$('.prod-variation input').iCheck({
      checkboxClass: 'icheckbox_square-purple',
      radioClass: 'iradio_square-purple',
    });*/

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
  });
})( jQuery );