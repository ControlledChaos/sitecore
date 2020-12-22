jQuery(function ($) {

	$(document).ready(function () {

		// Toggle "sub" fields that should be shown when admin column option is enabled.
		$( '#acf-field-group-fields' ).on( 'change', '.aac-field-settings-acf-column-enabled', function () { // @todo Hook into acf action if possible.
			var $admin_column_els = $(this).parents( '.acf-field-object' ).find( '.acf-field-setting-acf-column-post-types, .acf-field-setting-acf-column-taxonomies' );
			$admin_column_els.toggle($(this).parents( '.acf-field-object' ).find( 'input.aac-field-settings-acf-column-enabled' ).prop( 'checked' ) );
		});

		// Hide optional options if main option is not enabled when showing a field.
		acf.addAction( 'render_field_settings', function ( $el ) {
			var $admin_column_els = $el.find( '.acf-field-setting-acf-column-post-types, .acf-field-setting-acf-column-taxonomies' );
			$admin_column_els.toggle( $el.find( 'input.aac-field-settings-acf-column-enabled' ).prop( 'checked' ) );
		});

		// Hide optional options if main option is not enabled and populate checkboxes with default on when creating a new field.
		acf.addAction( 'new_field_object', function ( field ) {

			var $admin_column_els = field.$el.find( '.acf-field-setting-acf-column-post-types, .acf-field-setting-acf-column-taxonomies' ),

			// In case of a cloned field with active option.
			option_enabled = field.$el.find( 'input.aac-field-settings-acf-column-enabled' ).prop( 'checked' );

			// Show/hide sub fields depending on option enabled or not.
			$admin_column_els.toggle( option_enabled );

			// If option is not enabled we can expect a new field has been created instead of a duplicate.
			if ( ! option_enabled ) {
				$admin_column_els.find( 'input[type="checkbox"]' ).attr( 'checked', true);
			}

		})
	});
});