<?php
/**
 * Customize dashboard tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Widgets
 * @since      1.0.0
 */

// Get icon URL or fallback.
$theme_icon_path = get_theme_file_path( '/assets/images/theme-icon.jpg' );
$theme_icon_url  = get_theme_file_uri( '/assets/images/theme-icon.jpg' );
$brush_icon_url  = SCP_URL . 'assets/images/theme-brush-icon.svg';
$get_site_logo   = get_theme_mod( 'custom_logo' );
$site_logo_url   = wp_get_attachment_image_src( $get_site_logo, 'admin-avatar' );

if ( is_readable( $theme_icon_path ) ) {
	$icon_url = $theme_icon_url;
} elseif ( has_custom_logo( get_current_blog_id() ) ) {
	$icon_url = $site_logo_url[0];
} else {
	$icon_url = $brush_icon_url;
}

// Get theme data as variables.
$get_theme         = wp_get_theme();
$get_theme_name    = $get_theme->get( 'Name' );
$get_template      = $get_theme->get( 'Template' );
$get_parent        = wp_get_theme( get_template() );
$parent_name       = $get_parent->get( 'Name' );
$get_theme_uri     = $get_theme->get( 'ThemeURI' );
$get_author        = $get_theme->get( 'Author' );
$get_author_uri    = $get_theme->get( 'AuthorURI' );
$get_theme_desc    = $get_theme->get( 'Description' );
$get_theme_vers    = $get_theme->get( 'Version' );
$get_theme_min_wp  = $get_theme->get( 'RequiresWP' );
$get_theme_min_php = $get_theme->get( 'RequiresPHP' );
$get_theme_domain  = $get_theme->get( 'TextDomain' );
$get_theme_tags    = $get_theme->get( 'Tags' );
$screenshot_src    = $get_theme->get_screenshot();

// Text if data is not provided by the theme.
$not_provided = __( 'Not provided in the stylesheet header', 'sitecore' );

// Theme description.
if ( $get_theme_desc ) {
	$description = $get_theme_desc;
} else {
	$description = '';
}

// Theme link.
if ( $get_theme_uri ) {
	$theme_uri = '<a href="' . $get_theme_uri . '" target="_blank" rel="nofollow">' . $get_theme_uri . '</a>';
} else {
	$theme_uri = $not_provided;
}

// Theme author.
if ( $get_author ) {
	$author = $get_author;
} else {
	$author = $not_provided;
}

// Theme author link.
if ( $get_author_uri ) {
	$author_uri = '<a href="' . $get_author_uri . '" target="_blank" rel="nofollow">' . $get_author_uri . '</a>';
} else {
	$author_uri = $not_provided;
}

// Theme version.
if ( $get_theme_vers ) {
	$version = $get_theme_vers;
} else {
	$version = $not_provided;
}

// Theme text domain.
if ( $get_theme_domain ) {
	$domain = $get_theme_domain;
} else {
	$domain = $not_provided;
}

?>
<div id="customize" class="tab-content dashboard-panel-content dashboard-customize-content" style="display: none">

	<h2><?php _e( 'Customize Your Site', 'sitecore' ); ?></h2>
	<p class="description"><?php _e( 'Choose layout options, color schemes, and more.', 'sitecore' ); ?></p>

	<div class="dashboard-panel-column-container">

		<div class="dashboard-panel-column">

			<h3><?php _e( 'Active Theme', 'sitecore' ); ?> </h3>

			<div class="dashboard-panel-section-intro dashboard-panel-theme-greeting">

				<figure>
					<a href="<?php echo esc_url( wp_customize_url() ); ?>">
						<img class="avatar" src="<?php echo esc_attr( $icon_url ); ?>" alt="<?php _e( 'Site icon', 'sitecore' ); ?>" width="64" height="64" />
					</a>
					<figcaption class="screen-reader-text"><?php echo $get_theme_name; ?> <?php _e( 'theme', 'sitecore' ); ?></figcaption>
				</figure>

				<div>
					<h4><?php echo $get_theme_name; ?></h4>
					<p class="about-description"><?php echo $get_theme_desc; ?></p>

					<?php if ( $get_theme_tags ) {
						printf(
							'<p><strong>%s</strong> %s</p>',
							__( 'Tagged:', 'sitecore' ),
							implode( ', ', $get_theme_tags )
						);
					} ?>

					<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&return=' . site_url() ); ?>"><?php _e( 'Website Customizer' ); ?></a></p>
					<p class="description"><?php _e( 'Manage site identity & theme options.', 'sitecore' ); ?></p>
				</div>

			</div>
		</div>

		<div class="dashboard-panel-column">

			<h3><?php _e( 'Theme Details', 'sitecore' ); ?></h3>

			<ul>
				<?php if ( $get_template ) : ?>
				<li><strong><?php _e( 'Template: ', 'sitecore' ); ?></strong><?php echo $parent_name; ?></li>
				<?php endif; ?>
				<li><strong><?php esc_html_e( 'Version: ', 'sitecore' ); ?></strong><?php echo $version; ?></li>
				<?php if ( $get_theme_min_wp ) : ?>
				<li><strong><?php _e( 'System Minimum: ', 'sitecore' ); ?></strong><?php echo $get_theme_min_wp; ?></li>
				<?php endif; ?>
				<?php if ( $get_theme_min_php ) : ?>
				<li><strong><?php _e( 'PHP Minimum: ', 'sitecore' ); ?></strong><?php echo $get_theme_min_php; ?></li>
				<?php endif; ?>
				<li><strong><?php esc_html_e( 'Text Domain: ', 'sitecore' ); ?></strong><?php echo $domain; ?></li>
				<li><strong><?php esc_html_e( 'Theme URI: ', 'sitecore' ); ?></strong><?php echo $theme_uri; ?></li>
				<li><strong><?php esc_html_e( 'Author: ', 'sitecore' ); ?></strong><?php echo $author; ?></li>
				<li><strong><?php esc_html_e( 'Author URI: ', 'sitecore' ); ?></strong><?php echo $author_uri; ?></li>
			</ul>
		</div>

		<div class="dashboard-panel-column dashboard-panel-last">

			<h3><?php _e( 'Appearance Options', 'sitecore' ); ?></h3>

			<ul>
				<li><a class="dashboard-icon customize-icon-schemes" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Choose color schemes', 'sitecore' ); ?></a></li>
				<li><a class="dashboard-icon customize-icon-headers" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php _e( 'Set site & page headers', 'sitecore' ); ?></a></li>
				<li><a class="dashboard-icon customize-icon-typography" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=typography_options&return=' . site_url() ); ?>"><?php _e( 'Design your typography', 'sitecore' ); ?></a></li>
				<li><a class="dashboard-icon customize-icon-background" href="<?php echo esc_url( wp_customize_url() . '?url=' . site_url() . '&autofocus[section]=background_image&return=' . site_url() ); ?>"><?php _e( 'Site background', 'sitecore' ); ?></a></li>
				<?php do_action( 'customize_dashboard_tab_appearance_options' ); ?>
			</ul>
		</div>

	</div>
</div>
