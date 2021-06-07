<?php
/**
 * Posts list tables
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Posts_List_Table extends Classes\Base {

	/**
	 * Constructor method
	 *
	 * Calls the parent constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {
		parent :: __construct();

		// Dropdown select box.
		add_action( 'restrict_manage_posts', [ $this, 'filter_dropdown' ] );

		// Perform the filtering.
		add_filter( 'request', [ $this, 'filter_post_list' ] );

		// Add template column to posts lists.
		add_filter( 'manage_posts_columns', [ $this, 'template_columns_head' ], 9 );
		add_filter( 'manage_pages_columns', [ $this, 'template_columns_head' ], 9 );

		// Template column content.
		add_action( 'manage_posts_custom_column', [ $this, 'template_columns_content' ], 9, 2 );
		add_action( 'manage_pages_custom_column', [ $this, 'template_columns_content' ], 9, 2 );

		// Add image column to posts lists.
		add_filter( 'manage_posts_columns', [ $this, 'image_column_head' ] );
		add_filter( 'manage_pages_columns', [ $this, 'image_column_head' ] );

		// Image column content.
		add_action( 'manage_posts_custom_column', [ $this, 'image_column_content' ], 10, 2 );
		add_action( 'manage_pages_custom_column', [ $this, 'image_column_content' ], 10, 2 );
	}

	/**
	 * The filter dropdown select box.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function filter_dropdown() {

		// Exclude the Media Library screen.
		if ( 'upload.php' === $GLOBALS['pagenow'] ) {
			return;
		}

		// If a page template has been selected show posts using that template.
		if ( isset( $_GET['page_template_filter'] ) ) {
			$template = $_GET['page_template_filter'];

		// Otherwise show all posts.
		} else {
			$template = 'all';
		}

		// The HTML of the dropdown select box abave the table.
		?>
		<select name="page_template_filter" id="page_template_filter">
			<option value="all"><?php _e( 'All Page Templates', 'sitecore' ); ?></option>
			<option value="default" <?php echo ( $template == 'default' ) ? ' selected="selected" ' : ''; ?>><?php echo _e( 'Default Template', 'sitecore' ); ?></option>
			<?php page_template_dropdown( $template ); ?>
		</select>
		<?php
	}

	/**
	 * Perform the filtering.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $vars
	 * @return array
	 */
	public function filter_post_list( $vars ) {

		if ( ! isset( $_GET['page_template_filter'] ) ) {
			return $vars;
		}

		$template = trim( $_GET['page_template_filter'] );

		if ( $template == '' || $template == 'all' ) {
			return $vars;
		}

		$vars = array_merge(
			$vars,
			[
				'meta_query' => [
					[
						'key'     => '_wp_page_template',
						'value'   => $template,
						'compare' => '=',
					],
				],
			]
		);
		return $vars;
	}

	/**
	 * Add new Template column to post list.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $columns
	 * @return array
	 */
	public function template_columns_head( $columns ) {

		// The column heading name to new `template` column.
		$columns['template'] = __( 'Template', 'sitecore' );

		// Return the heading name.
		return $columns;
	}

	/**
	 * Template column content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $column_name
	 * @return void
	 */
	public function template_columns_content( $column_name ) {

		// If the column is the `template` column established above.
	    if ( $column_name == 'template' ) {

			// Get the post template by post ID.
			$template = get_post_meta( get_the_ID(), '_wp_page_template' , true );

			// If a template has been applied to the post.
	        if ( $template ) {

				// If it's the default template.
	        	if ( $template == 'default' ) {

					echo sprintf(
						'<span title="%1s">%2s</span>',
						__( 'Default Template', 'sitecore' ),
						__( 'Default Template', 'sitecore' )
					);

				// If it's not the default template.
	        	} else {

					// Get theme templates as a variable.
	        		$templates = wp_get_theme()->get_page_templates( get_the_ID(), get_post_type() );

					// If the template is found.
	        		if ( isset( $templates[ $template ] ) ) {
	        			echo sprintf(
							'<span title="%1s %2s">%3s</span>',
							__( 'Template file:', 'sitecore' ),
							$template,
							$templates[ $template ]
						);

					// If the template cannot be found.
	        		} else {
	        			echo sprintf(
							'<span title="%1s">%2s</span>',
							__( 'This template file does not exist', 'sitecore' ),
							$template
						);
					}
	        	}
			}
		}
	}

	/**
	 * Get column image
	 *
	 * Gets post thumbnail for use in admin columns.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param int $post_ID Returns the post ID.
	 * @return string Returns the path to the featured image.
	 */

	// Get featured image.
	private function get_column_image( $post_ID ) {

		// Get the post thumbnail ID as a variable.
		$post_thumbnail_id = get_post_thumbnail_id( $post_ID );

		/**
		 * Column thumbnail size.
		 *
		 * @see includes/classes/media/class-media.php
		 */
		$size  = 'column-thumbnail';

		// Apply a filter for conditional modification.
		$thumb = apply_filters( 'scp_column_thumbnail_size', $size );

		// If there is an ID (if the post has a featured image).
		if ( $post_thumbnail_id ) {

			// Get the src for the Thumbnail size.
			$post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, $thumb );

			// Return the image src for use below.
			return $post_thumbnail_img[0];
		}
	}

	/**
	 * Image column head
	 *
	 * Adds a new post admin column for the featured image.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $defaults Gets the array of default admin columns.
	 * @return string Returns the name of the new column head.
	 */
	public function image_column_head( $defaults ) {

		// The column heading name.
		$name    = __( 'Featured Image', 'sitecore' );

		// Apply a filter for conditional modification.
		$heading = apply_filters( 'scp_image_column_head', $name );

		// The column heading name to new `featured_image` column.
		$defaults['featured_image'] = esc_html__( $heading );

		// Return the heading name.
		return $defaults;
	}

	/**
	 * Image column content
	 *
	 * Adds the featured image to post admin columns.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $column_name
	 * @param  int $post_ID
	 * @return string Returns the image tag for the featured image.
	 */
	public function image_column_content( $column_name, $post_ID ) {

		// If the column is the `featured_image` column established above.
		if ( 'featured_image' == $column_name ) {

			// Get the image src established above.
			$post_featured_image = $this->get_column_image( $post_ID );

			/**
			 * The image tag to be added to the column/post row.
			 *
			 * The tag uses a style attribute for the width, and no width
			 * or height attributes are used, because the image size may
			 * be filtered externally to use a different aspect ratio.
			 */

			// If the post has a featured image.
			if ( $post_featured_image ) {
				echo '<img src="' . esc_url( $post_featured_image ) . '" alt="' . get_the_title( $post_ID ) . __( ' — featured image', 'sitecore' ) . '" width="48px" height="48px" />';

			// If the post doen't have a featured image then use the fallback image.
			} else {
				echo '<img src="' . esc_url( SCP_URL . 'assets/images/featured-image-placeholder.png' ) . '" alt="' . __( 'No featured image available', 'sitecore' ) . '" width="48px" height="48px" />';
			}
		}
	}
}
