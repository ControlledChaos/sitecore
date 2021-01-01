/**
 * User bio script
 *
 * Removes the textarea before displaying visual editor.
 */

( function($) {
	$( '#description' ).parents( 'tr' ).remove();
} )(jQuery);