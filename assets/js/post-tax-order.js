/**
 * The following handles the drag & drop post order.
 *
 * @since 1.0.0
 *
 * @link  https: //wordpress.org/plugins-wp/simple-custom-post-order/
 */
(function ($) {
	$('table.posts #the-list, table.pages #the-list').sortable({
		'items': 'tr',
		'axis': 'y',
		'helper': fixHelper,
		'update': function (e, ui) {
			$.post(ajaxurl, {
				action: 'update-menu-order',
				order: $('#the-list').sortable('serialize'),
			});
		}
	});

	$('table.tags #the-list').sortable({
		'items': 'tr',
		'axis': 'y',
		'helper': fixHelper,
		'update': function (e, ui) {
			$.post(ajaxurl, {
				action: 'update-menu-order-tags',
				order: $('#the-list').sortable('serialize'),
			});
		}
	});
	var fixHelper = function (e, ui) {
		ui.children().children().each(function () {
			$(this).width($(this).width());
		});
		return ui;
	};

})(jQuery)