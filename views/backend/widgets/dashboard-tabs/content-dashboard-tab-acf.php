<?php
/**
 * ACF content dashboard tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Widgets
 * @since      1.0.0
 */

$images = get_posts( [
	'post_type'      => 'attachment',
	'post_parent'    => null,
	'post_mime_type' => 'image',
	'post_status'    => null,
	'numberposts'    => 1,
	'order'          => 'DESC'
] );

?>
<div id="content" class="dashboard-panel-content dashboard-content-content" style="display: none;">

	<h2><?php _e( 'Manage Website Content', 'sitecore' ); ?></h2>
	<p class="description"><?php _e( 'Manage blog posts and static pages, upload images, add helpful widgets&hellip;', 'sitecore' ); ?></p>

	<div class="dashboard-panel-column-container">

		<div class="dashboard-panel-column">

			<h3><?php _e( 'Media Library', 'sitecore' ); ?></h3>

			<div class="dashboard-panel-section-intro dashboard-panel-content-greeting">
				<?php if ( $images ) : ?>
					<?php foreach ( $images as $image ) :
					$thumb = wp_get_attachment_image_src( $image->ID, 'thumbnail' );
					$src   = $thumb[0];
					?>
						<figure>
							<a href="<?php echo esc_attr( admin_url( 'upload.php' ) ); ?>"><img class="avatar" src="<?php echo esc_attr( $src ); ?>" alt="<?php echo esc_attr( apply_filters( 'the_title', $image->post_title ) ); ?>" width="64" height="64"></a>
							<figcaption class="screen-reader-text"><?php echo apply_filters( 'the_title', $image->post_title ); ?></figcaption>
						</figure>
					<?php endforeach; ?>
				<?php endif; ?>
				<div>
					<h4><?php _e( 'Images & Video', 'sitecore' ); ?></h4>
					<p class="about-description"><?php _e( 'Manage the site library of visual media as well as audio files & documents.', 'sitecore' ); ?></p>

					<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo esc_url( admin_url( 'upload.php' ) ); ?>"><?php _e( 'Manage Media Library' ); ?></a></p>
					<p class="description"><?php _e( 'Add media files to the site library.', 'sitecore' ); ?></p>
				</div>
			</div>
		</div>

		<div class="dashboard-panel-column">

			<h3><?php _e( 'Manage Content', 'sitecore' ); ?></h3>

			<ul>
			<?php if ( 'page' == get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page', 'sitecore' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

				<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages', 'sitecore' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

			<?php elseif ( 'page' == get_option( 'show_on_front' ) ) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit your front page', 'sitecore' ) . '</a>', get_edit_post_link( get_option( 'page_on_front' ) ) ); ?></li>

				<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add additional pages', 'sitecore' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

				<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Add a blog post', 'sitecore' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

			<?php else : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-write-blog">' . __( 'Write your first blog post', 'sitecore' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li>

				<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add an About page', 'sitecore' ) . '</a>', admin_url( 'post-new.php?post_type=page' ) ); ?></li>

				<li><?php printf( '<a href="%s" class="welcome-icon welcome-setup-home">' . __( 'Set up your homepage', 'sitecore' ) . '</a>', current_user_can( 'customize' ) ? add_query_arg( 'autofocus[section]', 'static_front_page', admin_url( 'customize.php' ) ) : admin_url( 'options-reading.php' ) ); ?></li>
			<?php endif; ?>

			<?php if ( current_user_can( 'edit_posts' ) ) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-comments">' . __( 'Manage Comments', 'sitecore' ) . '</a>', admin_url( 'edit-comments.php' ) ); ?></li>
			<?php endif; ?>
			</ul>
		</div>

		<div class="dashboard-panel-column dashboard-panel-last">

			<h3><?php _e( 'Additional Content', 'sitecore' ); ?></h3>

			<ul>
			<?php if ( current_user_can( 'upload_files' ) ) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-media">' . __( 'Manage media', 'sitecore' ) . '</a>', admin_url( 'upload.php' ) ); ?></li>
			<?php endif; ?>

			<?php if ( current_user_can( 'switch_themes' ) && current_theme_supports( 'widgets' ) ) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-widgets">' . __( 'Manage widgets', 'sitecore' ) . '</a>', admin_url( 'widgets.php' ) ); ?></li>
			<?php endif; ?>

			<?php if ( current_user_can( 'switch_themes' ) && current_theme_supports( 'menus' ) ) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-menus">' . __( 'Manage menus', 'sitecore' ) . '</a>', admin_url( 'nav-menus.php' ) ); ?></li>
			<?php endif; ?>

			<?php if ( class_exists( 'acf' ) && ( current_user_can( 'manage_options' ) || current_user_can( 'develop' ) ) ) :
				if ( acf_get_setting( 'show_admin' ) || current_user_can( 'develop' ) ) : ?>
				<li><?php printf( '<a href="%s" class="welcome-icon welcome-acf-fields">' . __( 'Manage fields', 'sitecore' ) . '</a>', admin_url( 'edit.php?post_type=acf-field-group' ) ); ?></li>
			<?php endif; endif; ?>
			</ul>
		</div>
	</div>
</div>
