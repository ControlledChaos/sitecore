<?php
/**
 * Custom dashboard panel ACF output
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Widgets
 * @since      1.0.0
 */

// Get ACF fields from registered options page.
$get_tabs = get_field( 'dashboard_content_tabs', 'option' );

// Count active tabs.
$tab_count = 0;
if ( $get_tabs ) {
	foreach ( $get_tabs as $tab ) {
		if ( $tab['dashboard_content_tab_active'] ) {
			$tab_count++;
		}
	}
}

/**
 * If there are no tabs set in the Dashboard Tabs options page
 * then use the default custom dashboard content.
 */
if ( 0 == $tab_count || ! get_field( 'dashboard_content_tabs_active', 'option' ) ) {
	include_once SCP_PATH . '/views/backend/widgets/dashboard-panel.php';
	return;
}

if ( $tab_count > 1 ) {
	$tabbed         = ' data-tabbed="tabbed"';
	$wrap_class     = 'dashboard-panel-content registered-content-wrap admin-tabs';
	$content_class  = 'registered-content tab-content';
} else {
	$tabbed         = '';
	$wrap_class     = 'dashboard-panel-content registered-content-wrap';
	$content_class  = 'registered-content';
}

?>
<div class="<?php echo $wrap_class; ?>" <?php echo $tabbed; ?> data-tabdeeplinking="true" >

<?php

if ( $tab_count > 1 ) : ?>

<ul class="admin-tabs-list hide-if-no-js">
<?php
foreach ( $get_tabs as $tab ) :

	$tab_id   = strtolower( str_replace( [ ' ', '-' ], '_', $tab['dashboard_content_tab_label'] ) );

	$user_cap = $tab['dashboard_content_tab_user_cap'];
	if ( ! empty( $user_cap ) ) {
		$user_cap = $user_cap;
	} else {
		$user_cap = 'read';
	}

	if ( current_user_can( $user_cap ) && $tab['dashboard_content_tab_active'] ) :

		$href = "#$tab_id";

		if ( ! empty( $tab['dashboard_content_tab_icon'] ) ) {
			$icon = sprintf(
				'<span class="content-tab-icon dashicons %1s"></span> ',
				$tab['dashboard_content_tab_icon']
			);
		} else {
			$icon = null;
		}
		?>
			<li class="content-tab">
				<a href="<?php echo esc_url( $href ); ?>" aria-controls="<?php echo esc_attr( $tab_id ); ?>">
					<?php echo $icon . $tab['dashboard_content_tab_label']; ?>
				</a>
		<?php
	endif;
endforeach;

?>
</ul>
<?php endif; ?>

<?php
if ( $tab_count > 0 ) :
foreach ( $get_tabs as $tab ) :

	$tab_id = strtolower( str_replace( [ ' ', '-' ], '_', $tab['dashboard_content_tab_label'] ) );

	$user_cap = $tab['dashboard_content_tab_user_cap'];
	if ( ! empty( $user_cap ) ) {
		$user_cap = $user_cap;
	} else {
		$user_cap = 'read';
	}

	if ( current_user_can( $user_cap ) && $tab['dashboard_content_tab_active'] ) :
	?>
	<div id="<?php echo esc_attr( $tab_id ); ?>" class="<?php echo $content_class; ?>">
		<?php

		printf(
			'<h2>%s</h2>',
			$tab['dashboard_content_tab_heading']
		);
		echo $tab['dashboard_content_tab_content'];

		?>
	</div>
	<?php
	endif;
endforeach;
endif; ?>
</div>
