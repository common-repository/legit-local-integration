jQuery(function($){
	if( ! $('body').hasClass('settings_page_legit-local-settings') ) return;

	$('#js-ll-ninja-form-select').on('change', function( event ){
		$('#js-ll-name-select').replaceWith( '<div id="js-ll-name-select"><img src="/wp-admin/images/loading.gif"></div>' );
		$('#js-ll-contact-select').replaceWith( '<div id="js-ll-contact-select"><img src="/wp-admin/images/loading.gif"></div>' );
		$.post( ajaxurl, { action: 'get_new_ninja_forms_fields', form_id: event.target.value }, function( result ){
			if( ! result.success ){
				$('#form-ajax-error-message').text(
					'Make sure to click "Save Changes" below. Then select the appropriate fields for this form.'
				);
			}else{
				$('#js-ll-name-select').replaceWith( result.data.name_field );
				$('#js-ll-contact-select').replaceWith( result.data.contact_field );
			}
		});
	});
});
