/**
 * User bio script
 *
 * Removes the textarea before displaying visual editor.
 */

( function($) {
	$( '#description' ).parents( 'tr' ).remove();
	$( '#profile-page > form h2:nth-of-type(4),	#profile-page > form table:nth-of-type(4)' ).remove();
	$( '#profile-page > form > div > div h2:nth-of-type(4),	#profile-page > form > div > div table:nth-of-type(4)' ).remove();
} )(jQuery);