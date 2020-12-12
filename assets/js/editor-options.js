( function( wp ) {
	if ( ! wp ) {
		return;
	}

	wp.plugins.registerPlugin( 'editor-options', {
		render: function() {
			var createElement = wp.element.createElement;
			var PluginMoreMenuItem = wp.editPost.PluginMoreMenuItem;
			var url = wp.url.addQueryArgs( document.location.href, { 'editor-options': '', 'default-editor__forget': '' } );
			var linkText = lodash.get( window, [ 'editorOptionsL10n', 'linkText' ] ) || 'Switch to rich text editor ';

			return createElement(
				PluginMoreMenuItem,
				{
					icon: 'edit-page',
					href: url,
				},
				linkText
			);
		},
	} );
} )( window.wp );
