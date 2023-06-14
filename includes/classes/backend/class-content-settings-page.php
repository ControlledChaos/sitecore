<?php
/**
 * Content settings class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Content_Settings_Page extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => $this->page_title(),
			'menu_title'  => __( 'Content', 'sitecore' ),
			'description' => $this->description()
		];

		$options = [
			'capability'    => 'read',
			'settings'      => [
				'print_form' => true,
				'capability' => 'manage_options'
			],
			'menu_slug'     => 'custom-content',
			'icon_url'      => 'dashicons-edit',
			'position'      => 25,
			'tabs_hashtags' => true
		];

		parent :: __construct(
			$labels,
			$options,
			9
		);
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page title.
	 */
	protected function page_title() {

		if ( ! current_user_can( 'edit_posts' ) ) {
			$title = __( 'Website Content', 'sitecore' );
		} else {
			$title = __( 'Custom Content', 'sitecore' );
		}
		return $title;
	}

	/**
	 * Page description
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page description.
	 */
	protected function description() {

		if ( ! current_user_can( 'edit_posts' ) ) {
			$description = __( 'This is an overview of this website\'s content.', 'sitecore' );
		} else {
			$description = __( 'Manage how the content of this website is edited and displayed.', 'sitecore' );
		}
		return sprintf(
			'<p class="description">%s</p>',
			$description
		);
	}

	/**
	 * Tabbed content
	 *
	 * Add content to the tabbed section of the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function tabs() {

		if ( get_option( 'enable_sample_files' ) ) :
		$this->add_content_tab( [
			'capability' => 'read',
			'id'         => 'content-settings-intro',
			'tab'        => __( 'Intro', 'sitecore' ),
			'heading'    => __( 'About This Website\'s Content', 'sitecore' ),
			'icon'       => 'dashicons-info',
			'content'    => '',
			'callback'   => [ $this, 'intro_tab' ]
		] );
		endif;

		$this->add_content_tab( [
			'capability' => 'manage_options',
			'id'         => 'content-settings',
			'tab'        => __( 'Options', 'sitecore' ),
			'heading'    => __( 'Website Content Options', 'sitecore' ),
			'icon'       => 'dashicons-admin-generic',
			'content'    => '',
			'callback'   => [ $this, 'settings_tab' ]
		] );

		if ( get_option( 'enable_meta_tags', true ) ) :
			$this->add_content_tab( [
				'capability' => 'read',
				'id'         => 'content-settings-meta-tags',
				'tab'        => __( 'Meta', 'sitecore' ),
				'heading'    => __( 'Frontend Meta Tags', 'sitecore' ),
				'icon'       => 'dashicons-share1',
				'content'    => '',
				'callback'   => [ $this, 'meta_tags_tab' ]
			] );
			endif;
	}

	/**
	 * Intro tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function intro_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-content-intro.php';
	}

	/**
	 * Settings callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function settings_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-content.php';
	}

	/**
	 * Meta tags callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function meta_tags_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-content-meta-tags.php';
	}

	/**
	 * Enqueue page scripts
	 *
	 * Enqueues scripts for the media modal.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_media();
	}

	/**
	 * Print page scripts
	 *
	 * Hooks into the page slug (suffix).
	 *
	 * Script employs the media modal for choosing
	 * images, and it modifies the image field buttons.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_scripts() {

		wp_print_scripts( 'media-upload' );

		?>
		<script type="text/javascript">
		jQuery(document).ready( function($) {

			var blog_uploader;
			$( '#meta-image-blog-index-upload-button' ).click( function(e) {
				e.preventDefault();

				// If the uploader object has already been created, reopen the dialog.
				if ( blog_uploader ) {
					blog_uploader.open();
					return;
				}
				// Extend the wp.media object.
				blog_uploader = wp.media.frames.file_frame = wp.media( {
					title  : "<?php _e( 'Choose Image', 'sitecore' ); ?>",
					button : {
						text : "<?php _e( 'Choose Image', 'sitecore' ); ?>"
					},
					library : {
						type : 'image'
					},
					multiple : false,
					close    : true
				} );

				/**
				 * When a file is selected, get the URL
				 * and set it as the text field's value,
				 * as well as thr preview src value.
				 */
				blog_uploader.on( 'select', function() {

					// @todo Get src of meta-image size rather than full size.
					attachment = blog_uploader.state().get( 'selection' ).first().toJSON();

					$( '#meta-image-blog-index-upload-field' ).val( attachment.id );
					$( '#meta-image-blog-index-preview' ).show().attr( 'src', attachment.url );
					$( '#meta-image-blog-index-upload-button' ).val( "<?php _e( 'Replace Image', 'sitecore' ); ?>" );
					$( '#meta-image-blog-index-remove-button' ).removeAttr( 'disabled' );
				} );

				// Open the uploader dialog.
				blog_uploader.open();
			} );

			var archive_uploader;
			$( '#meta-image-archive-upload-button' ).click( function(e) {
				e.preventDefault();

				// If the uploader object has already been created, reopen the dialog.
				if ( archive_uploader ) {
					archive_uploader.open();
					return;
				}
				// Extend the wp.media object.
				archive_uploader = wp.media.frames.file_frame = wp.media( {
					title  : "<?php _e( 'Choose Image', 'sitecore' ); ?>",
					button : {
						text : "<?php _e( 'Choose Image', 'sitecore' ); ?>"
					},
					library : {
						type : 'image'
					},
					multiple : false,
					close    : true
				} );

				/**
				 * When a file is selected, get the URL
				 * and set it as the text field's value,
				 * as well as thr preview src value.
				 */
				archive_uploader.on( 'select', function() {

					// @todo Get src of meta-mage size rather than full size.
					attachment = archive_uploader.state().get( 'selection' ).first().toJSON();

					$( '#meta-image-archive-upload-field' ).val( attachment.id );
					$( '#meta-image-archive-preview' ).show().attr( 'src', attachment.url );
					$( '#meta-image-archive-upload-button' ).val( "<?php _e( 'Replace Image', 'sitecore' ); ?>" );
					$( '#meta-image-archive-remove-button' ).removeAttr( 'disabled' );
				} );

				// Open the uploader dialog.
				archive_uploader.open();
			} );

			$( '#meta-image-blog-index-remove-button' ).click( function(e) {
				$( '#meta-image-blog-index-upload-field' ).val( '' );
				$( '#meta-image-blog-index-upload-button' ).val( "<?php _e( 'Add Image', 'sitecore' ); ?>" );
				$( '#meta-image-blog-index-preview' ).hide();
				$(this).attr( 'disabled', true );
				return false;
			} );

			$( '#meta-image-archive-remove-button' ).click( function(e) {
				$( '#meta-image-archive-upload-field' ).val( '' );
				$( '#meta-image-archive-upload-button' ).val( "<?php _e( 'Add Image', 'sitecore' ); ?>" );
				$( '#meta-image-archive-preview' ).hide();
				$(this).attr( 'disabled', true );
				return false;
			} );
		} );
		</script>
		<?php
	}
}
