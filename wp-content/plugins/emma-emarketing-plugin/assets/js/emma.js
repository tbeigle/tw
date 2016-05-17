jQuery(document).ready(function($) {
	$('#emma-form input#emma-form-submit').click(function(e){
		//prevent the form from actually submitting and refreshing the page
		e.preventDefault();
		e.stopPropagation();
		
		var thisForm = $(e.target).closest('#emma-subscription-form');
		var thisWrap = $(e.target).closest('.emma-wrap');
		var thisFormUnique = thisForm.attr('data-form-unique');
		
		thisForm.addClass('activeForm');
		
		// If a status already exists, fade it out in sync with the form then remove it.
		if ($('.emma-status').length > 0) {
			$('.emma-status').fadeOut({
				duration:300, 
				queue: false, 
				complete: function(){
					$('.emma-status').remove();
				}
			});
		}
		
		// Fade out the form, show a little spinner thing
		thisForm.fadeOut({
			duration: 300,
			queue: false,
			complete: function(){
				// Show the WordPress default spinner
				$('<div class="spinner"></div>').prependTo(thisWrap).show();
				
				// Now let's submit the form via AJAX
				var data = {
					'action': 'emma_ajax_form_submit',
					'emma_email': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_email"]').val(),
					'emma_firstname': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_firstname"]').val(),
					'emma_lastname': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_lastname"]').val(),
					'emma_signup_form_id': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_signup_form_id"]').val(),
					'emma_send_confirmation': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_send_confirmation"]').val(),
				};
				
				jQuery.post(ajax_object.ajax_url, data, function(response) {
					var errorClass = '';
					var hasError = false;

					response = $.parseJSON(response);
					
					// Check for errors
					if ( response.code > 800) {
						errorClass = 'emma-alert';
						hasError = true;
						response.tracking_pixel = '<p style="display:none !important;">Error occured. No tracking pixel placed.</p>';
					} else {
						errorClass = '';
					}
					
					// Display the status
					thisWrap.prepend('<div class="emma-status ' + errorClass + '" style="display:none;">' + response.status_txt + '</div>' + response.tracking_pixel);
					
					// Show/Hide stuff
					$('.spinner').delay(800).fadeOut(300, function(){
						$('.spinner').remove();
						$('.emma-status').fadeIn(300);
						
						// If we have an error, we need to show the form again
						if (hasError == true) {
							thisForm.fadeIn(300);
						}
					});
				});
			}
		});
	});
});