jQuery( document ).ready( function( $ ) {

	$( document ).bind( 'cac_model_ready', function( e, type ) {

		var container = $( '.columns-container[data-type=' + type + ']' );

		if ( !container.length ) {
			return;
		}

		var layouts = container.find( '.sidebox.layouts' );

		// Bind events once
		if ( layouts.hasClass( 'events-binded' ) ) {
			return;
		}
		layouts.addClass( 'events-binded' );

		// Add new layout
		layouts.find( 'a.add-new' ).click( function( e ) {
			e.preventDefault();
			$( this ).closest( '.layouts' ).find( '.new' ).slideToggle();
		} );

		// Add new error message
		layouts.find( '.new form' ).on( 'submit', function( e ) {
			e.preventDefault();
			$name = $( this ).find( '.row.name' );

			if ( !$name.find( 'input' ).val() ) {
				$name.addClass( 'save-error' );
				return;
			}
			$( this ).unbind( 'submit' ).submit();
		} );

		// Role events
		layouts.find( "select.roles" ).each( function() {
			$( this ).select2( {
					placeholder : cpac_layouts.roles,
				} )
				.on( "select2:select", function( e ) {
					$( this ).select2( 'open' );
				} )
				.on( "select2:open", function( e ) {
					setTimeout( function() {
						$( '.select2-container.select2-container--open .select2-dropdown li[role=group]' ).each( function() {
							if ( $( this ).find( 'li[aria-selected=false]' ).length > 0 ) {
								$( this ).show();
							}
							else {
								$( this ).hide();
							}
						} );
					}, 1 );

				} );

		} );

		// Toggle body
		layouts.find( '.layout .head' ).click( function( e ) {
			e.preventDefault();
			var $layout = $( this ).closest( '.layout' );
			$layout.toggleClass( 'open' ).find( '.body' ).slideToggle( 200 );
		} );
		layouts.find( '.layout .head a, .layout .head input' ).click( function( e ) {
			e.stopPropagation();
		} );

		// Name events
		layouts.find( 'input.name' ).bind( 'keyup change', function() {

			var value = $( this ).val();
			var screen = $( this ).closest( '.layout' ).data( 'screen' );

			// Display default value if the input is empty
			if ( !value.trim() ) {
				value = $( this ).data( 'value' );
			}

			// menu label
			$( this ).closest( '.columns-container' ).find( '.layout-selector li[data-screen="' + screen + '"] a' ).text( value );

			// layout box label
			var $title = $( this ).closest( '.layout' ).find( '.head .title' ).text( value );
		} );

		layouts.find( '.layout' ).each( function( e ) {
			var storage_model = $( this ).closest( '.layouts' ).data( 'type' );
			var screen = $( this ).data( 'screen' );
			$( this ).ac_update_layout( storage_model, screen );
		} );

		// User events
		layouts.find( "select.users" ).select2( {
			placeholder : cpac_layouts.users,
			multiple : true,
			minimumInputLength : 1,
			ajax : {
				type : 'POST',
				url : ajaxurl,
				dataType : 'json',
				delay : 350,
				data : function( params ) {
					return {
						action : 'ac_layout_get_users',
						plugin_id : 'cpac',
						_ajax_nonce : cpac_layouts._nonce,
						search : params.term
					};
				},
				processResults : function( response ) {

					if ( response ) {
						if ( response.success && response.data ) {
							return {
								results : response.data
							};
						}
					}

					return { results : [] };
				},
				cache : true
			}
		} );
	} );

	// Store layouts on main update event
	$( document ).bind( 'cac_update', function( e, container ) {
		$( container ).find( '.layout input.save' ).trigger( 'click' );
	} );
} );

// Store layout per item
jQuery.fn.ac_update_layout = function( storage_model, screen ) {

	var $layout = jQuery( this );
	var $button = $layout.find( '.save' );

	$button.click( function( e ) {

		e.preventDefault();
		$layout.addClass( 'loading' );

		var $name = $layout.find( 'input.name' );
		var $button = jQuery( this ).attr( 'disabled', 'disabled' );

		$layout.find( '.row.name' ).removeClass( 'save-error' );

		jQuery.post( ajaxurl, {
				plugin_id : 'cpac',
				action : 'ac_update_layout',
				_ajax_nonce : cpac_layouts._nonce,
				storage_model : storage_model,
				data : $layout.find( 'form' ).serialize(),
			},

			// JSON or empty response
			function( response ) {

				if ( response ) {

					// Success
					if ( response.success ) {
						$name.data( 'value', $name.val() ); // when input is empty we use the data attribute as a fallback
						$layout.find( '.save-message' ).slideDown().delay( 1000 ).slideUp();
						$layout.find( '.title-div span.description' ).text( response.data.title_description );
					}

					// Error
					else if ( response.data ) {
						if ( 'empty-name' === response.data ) {
							$layout.addClass( 'open' ).find( '.body' ).slideDown().find( '.row.name' ).addClass( 'save-error' );
						}
					}
				}

				// No response
				else {
				}

			}, 'json' )

			// PHP error or notices
			.fail( function( error ) {

			} )

			// Finish
			.always( function() {
				$button.removeAttr( 'disabled' );
				$layout.removeClass( 'loading' );
			} );
	} );

	// Do update on enter key
	$layout.find( 'input' ).keypress( function( e ) {
		if ( 13 === e.which ) {
			$button.trigger( 'click' );
		}
	} );
};
