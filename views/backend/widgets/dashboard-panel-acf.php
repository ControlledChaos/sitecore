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

/**
 * If there are no tabs set in the Dashboard Tabs options page
 * then use the default custom dashboard content.
 */
if ( ! $get_tabs ) {
	include_once SCP_PATH . '/views/backend/widgets/dashboard-panel.php';
	return;
}

if ( count( $get_tabs ) > 1 ) {
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

if ( count( $get_tabs ) > 1 ) : ?>

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

	if ( current_user_can( $user_cap ) ) :

		$href = "#$tab_id";

		if ( ! empty( $tab['icon'] ) ) {
			$icon = sprintf(
				'<span class="content-tab-icon %1s"></span> ',
				$tab['icon']
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
foreach ( $get_tabs as $tab ) :

	$tab_id = strtolower( str_replace( [ ' ', '-' ], '_', $tab['dashboard_content_tab_label'] ) );

	$user_cap = $tab['dashboard_content_tab_user_cap'];
	if ( ! empty( $user_cap ) ) {
		$user_cap = $user_cap;
	} else {
		$user_cap = 'read';
	}

	if ( current_user_can( $user_cap ) ) :
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
endforeach; ?>
</div>
