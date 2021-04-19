<?php
/**
 * Drag & drop custom post and taxonomy orders
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Types_Taxes_Order {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		add_action( 'admin_init', [ $this, 'refresh' ] );

		add_action( 'admin_init', [ $this, 'update_options' ] );
		add_action( 'admin_init', [ $this, 'load_script_css' ] );

		add_action( 'wp_ajax_update-menu-order', [ $this, 'update_menu_order' ] );
		add_action( 'wp_ajax_update-menu-order-tags', [ $this, 'update_menu_order_tags' ] );

		add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );

		add_filter( 'get_previous_post_where', [ $this, 'previous_post_where' ] );
		add_filter( 'get_previous_post_sort', [ $this, 'previous_post_sort' ] );
		add_filter( 'get_next_post_where', [ $this, 'next_post_where' ] );
		add_filter( 'get_next_post_sort', [ $this, 'next_post_sort' ] );

		add_filter( 'get_terms_orderby', [ $this, 'get_terms_orderby' ], 10, 3 );
		add_filter( 'wp_get_object_terms', [ $this, 'get_object_terms' ], 10, 3 );
		add_filter( 'get_terms', [ $this, 'get_object_terms' ], 10, 3 );

	}

	/**
	 * Update options table on installation.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function order_install() {

		global $wpdb;

		$result = $wpdb->query( "DESCRIBE $wpdb->terms `term_order`" );

		if ( ! $result ) {
			$query  = "ALTER TABLE $wpdb->terms ADD `term_order` INT( 4 ) NULL DEFAULT '0'";
			$result = $wpdb->query( $query );
		}

		update_option( 'scp_order_install', 1 );
	}

	/**
	 * Add an options page for sort order settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @todo   Remove this if or when the settings are
	 *         moved to the Reading Settings page.
	 */
	public function admin_menu() {

		add_options_page(
			__( 'Posts & Taxonomies Sort Order', 'sitecore' ),
			__( 'Sort Order', 'sitecore' ),
			'manage_options',
			'sort-order-settings',
			[ $this, 'admin_page' ]
		);
	}

	/**
	 * Callback for the admin page.
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @todo   Address this if or when the settings are
	 *         moved to the Reading Settings page.
	 */
	public function admin_page() {
		require SCP_PATH . 'views/backend/forms/settings-page-posts-order.php';
	}

	/**
	 * Check where to load scripts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns an array of selected post types and taxonomies.
	 */
	public function _check_load_script_css() {

		$active  = false;
		$objects = $this->get_order_options_objects();
		$tags    = $this->get_order_options_tags();

		// Bail if no post types or taxonomies have been selected.
		if ( empty( $objects ) && empty( $tags ) ) {
			return false;
		}

		if ( isset( $_GET['orderby'] ) || strstr( $_SERVER['REQUEST_URI'], 'action=edit' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) ) {
			return false;
		}

		// Set selected post types to true for custom sorting.
		if ( ! empty( $objects ) ) {

			// If the selected is a page or a custom post type.
			if ( isset( $_GET['post_type'] ) && ! isset( $_GET['taxonomy'] ) && in_array( $_GET['post_type'], $objects ) ) {
				$active = true;
			}

			// If the selected is a post.
			if ( ! isset($_GET['post_type']) && strstr($_SERVER['REQUEST_URI'], 'wp-admin/edit.php') && in_array('post', $objects)) {
				$active = true;
			}

		}

		// Set selected taxonomies to true for custom sorting.
		if ( ! empty( $tags ) ) {

			if ( isset( $_GET['taxonomy'] ) && in_array( $_GET['taxonomy'], $tags ) ) {
				$active = true;
			}

		}

		// Return the array of selected post types and taxonomies.
		return $active;

	}

	/**
	 * Load script dependencies
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load_script_css() {

		// Script suffix.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$suffix = '';
		} else {
			$suffix = '.min';
		}

		if ( $this->_check_load_script_css() ) {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'post-tax-order', SCP_URL . 'assets/js/post-tax-order' . $suffix . '.js', [ 'jquery' ], null, true );

		}

	}

	/**
	 * Refresh the post order according to manual sorting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function refresh() {

		global $wpdb;

		$objects = $this->get_order_options_objects();
		$tags    = $this->get_order_options_tags();

		if ( ! empty( $objects ) ) {

			foreach ( $objects as $object ) {

				$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min
					FROM $wpdb->posts
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
				" );

				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max )
					continue;

				$results = $wpdb->get_results( "
					SELECT ID
					FROM $wpdb->posts
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
					ORDER BY menu_order ASC
				" );

				foreach ( $results as $key => $result ) {
					$wpdb->update(
						$wpdb->posts,
						[ 'menu_order' => $key + 1 ],
						[ 'ID'         => $result->ID ]
					);
				}

			}

		}

		if ( ! empty( $tags ) ) {

			foreach ( $tags as $taxonomy ) {

				$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(term_order) as max, min(term_order) as min
					FROM $wpdb->terms AS terms
					INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
					WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
				" );
				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}

				$results = $wpdb->get_results( "
					SELECT terms.term_id
					FROM $wpdb->terms AS terms
					INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
					WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
					ORDER BY term_order ASC
				" );

				foreach ( $results as $key => $result ) {
					$wpdb->update(
						$wpdb->terms,
						[ 'term_order' => $key + 1 ],
						[ 'term_id'    => $result->term_id ]
					);
				}

			}

		}

	}

	/**
	 * Update the post order according to manual sorting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function update_menu_order() {

		global $wpdb;

		parse_str( $_POST['order'], $data );

		if ( ! is_array( $data ) ) {
			return false;
		}

		$id_arr = [];

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = $id;
			}
		}

		$menu_order_arr = [];

		foreach ( $id_arr as $key => $id ) {

			$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval( $id ) );

			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->menu_order;
			}
		}

		sort( $menu_order_arr );

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update(
					$wpdb->posts,
					[ 'menu_order' => $menu_order_arr[$position] ],
					[ 'ID'         => intval( $id ) ]
				);
			}
		}

	}

	/**
	 * Update the yaxonomy order according to manual sorting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function update_menu_order_tags() {

		global $wpdb;

		parse_str( $_POST['order'], $data );

		if ( ! is_array( $data ) ) {
			return false;
		}

		$id_arr = [];

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$id_arr[] = $id;
			}
		}

		$menu_order_arr = [];

		foreach ( $id_arr as $key => $id ) {

			$results = $wpdb->get_results( "SELECT term_order FROM $wpdb->terms WHERE term_id = " . intval( $id ) );

			foreach ( $results as $result ) {
				$menu_order_arr[] = $result->term_order;
			}
		}

		sort( $menu_order_arr );

		foreach ( $data as $key => $values ) {
			foreach ( $values as $position => $id ) {
				$wpdb->update(
					$wpdb->terms,
					[ 'term_order' => $menu_order_arr[$position] ],
					[ 'term_id'    => intval( $id ) ]
				);
			}
		}

	}

	/**
	 * Update the post and taxonomy order options.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function update_options() {

		global $wpdb;

		if ( ! isset( $_POST['scp_posts_order_submit'] ) ) {
			return false;
		}

		check_admin_referer( 'scp_posts_order_nonce' );

		$input_options            = [];
		$input_options['objects'] = isset( $_POST['objects'] ) ? $_POST['objects'] : '';
		$input_options['tags']    = isset( $_POST['tags'] ) ? $_POST['tags'] : '';

		update_option( 'scp_order_options', $input_options );

		$objects = $this->get_order_options_objects();
		$tags    = $this->get_order_options_tags();

		if ( ! empty( $objects ) ) {

			foreach ( $objects as $object ) {

				$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min
					FROM $wpdb->posts
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
				" );

				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}

				if ( $object == 'page' ) {

					$results = $wpdb->get_results( "
						SELECT ID
						FROM $wpdb->posts
						WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
						ORDER BY post_title ASC
					" );

				} else {

					$results = $wpdb->get_results( "
						SELECT ID
						FROM $wpdb->posts
						WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
						ORDER BY post_date DESC
					" );

				}

				foreach ( $results as $key => $result ) {
					$wpdb->update(
						$wpdb->posts,
						[ 'menu_order' => $key + 1 ],
						[ 'ID'         => $result->ID ]
					);
				}

			}

		}

		if ( ! empty( $tags ) ) {

			foreach ( $tags as $taxonomy ) {

				$result = $wpdb->get_results( "
					SELECT count(*) as cnt, max(term_order) as max, min(term_order) as min
					FROM $wpdb->terms AS terms
					INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
					WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
				" );

				if ( $result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max ) {
					continue;
				}

				$results = $wpdb->get_results( "
					SELECT terms.term_id
					FROM $wpdb->terms AS terms
					INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON ( terms.term_id = term_taxonomy.term_id )
					WHERE term_taxonomy.taxonomy = '" . $taxonomy . "'
					ORDER BY name ASC
				" );

				foreach ( $results as $key => $result ) {
					$wpdb->update(
						$wpdb->terms,
						[ 'term_order' => $key + 1 ],
						[ 'term_id'    => $result->term_id ]
					);
				}

			}

		}

		wp_redirect( 'options-general.php?page=sort-order-settings&msg=updated' );

	}

	/**
	 * Previous posts in new order.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $where
	 * @return void
	 */
	public function previous_post_where( $where ) {

		global $post;

		$objects = $this->get_order_options_objects();

		if ( empty( $objects ) ) {
			return $where;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where = "WHERE p.menu_order > '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}

		return $where;

	}

	/**
	 * Previous posts in new order.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $orderby
	 * @return void
	 */
	public function previous_post_sort( $orderby ) {

		global $post;

		$objects = $this->get_order_options_objects();

		if ( empty( $objects ) ) {
			return $orderby;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
		}

		return $orderby;

	}

	/**
	 * Next posts in new order.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $where
	 * @return void
	 */
	public function next_post_where( $where ) {

		global $post;

		$objects = $this->get_order_options_objects();

		if ( empty( $objects ) ) {
			return $where;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where = "WHERE p.menu_order < '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}

		return $where;

	}

	/**
	 * Next posts in new order.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $where
	 * @return void
	 */
	public function next_post_sort( $orderby ) {

		global $post;

		$objects = $this->get_order_options_objects();

		if ( empty( $objects ) ) {
			return $orderby;
		}

		if ( isset( $post->post_type ) && in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order DESC LIMIT 1';
		}

		return $orderby;

	}

	/**
	 * Undocumented function
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $wp_query
	 * @return void
	 */
	public function pre_get_posts( $wp_query ) {

		$objects = $this->get_order_options_objects();

		if ( empty( $objects ) ) {
			return false;
		}

		if ( is_admin() ) {
			if ( isset( $wp_query->query['post_type'] ) && ! isset( $_GET['orderby'] ) ) {
				if ( in_array( $wp_query->query['post_type'], $objects ) ) {
					$wp_query->set( 'orderby', 'menu_order' );
					$wp_query->set( 'order', 'ASC' );
				}
			}

		} else {

			$active = false;

			if ( isset( $wp_query->query['post_type'] ) ) {
				if ( ! is_array( $wp_query->query['post_type'] ) ) {
					if ( in_array( $wp_query->query['post_type'], $objects ) ) {
						$active = true;
					}
				}

			} else {
				if ( in_array( 'post', $objects ) ) {
					$active = true;
				}
			}

			if ( ! $active ) {
				return false;
			}

			if ( isset( $wp_query->query['suppress_filters'] ) ) {

				if ( $wp_query->get('orderby') == 'date' ) {
					$wp_query->set('orderby', 'menu_order');
				}

				if ( $wp_query->get('order') == 'DESC' ) {
					$wp_query->set('order', 'ASC');
				}

			} else {

				if ( ! $wp_query->get( 'orderby' ) ) {
					$wp_query->set( 'orderby', 'menu_order' );
				}

				if ( ! $wp_query->get( 'order' ) ) {
					$wp_query->set( 'order', 'ASC' );
				}

			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $orderby
	 * @param  array $args
	 * @return void
	 */
	public function get_terms_orderby( $orderby, $args ) {

		if ( is_admin() ) {
			return $orderby;
		}

		$tags = $this->get_order_options_tags();

		if ( ! isset($args['taxonomy'] ) ) {
			return $orderby;
		}

		$taxonomy = $args['taxonomy'];
		if ( ! in_array( $taxonomy, $tags ) ) {
			return $orderby;
		}

		$orderby = 't.term_order';
		return $orderby;

	}

	/**
	 * Undocumented function
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $terms
	 * @return void
	 */
	public function get_object_terms( $terms ) {

		$tags = $this->get_order_options_tags();

		if ( is_admin() && isset( $_GET['orderby'] ) ) {
			return $terms;
		}

		foreach ( $terms as $key => $term ) {

			if ( is_object( $term ) && isset( $term->taxonomy ) ) {

				$taxonomy = $term->taxonomy;

				if ( ! in_array( $taxonomy, $tags ) ) {
					return $terms;
				}

			} else {
				return $terms;
			}

		}

		usort( $terms, [ $this, 'taxcmp' ] );
		return $terms;

	}

	/**
	 * Undocumented function
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $a
	 * @param  array $b
	 * @return void
	 */
	public function taxcmp( $a, $b ) {

		if ( $a->term_order == $b->term_order ) {
			return 0;
		}

		return ( $a->term_order < $b->term_order ) ? -1 : 1;

	}

	/**
	 * Undocumented function
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_order_options_objects() {

		if ( $scp_order_options = get_option( 'scp_order_options' ) ) {
			$scp_order_options = get_option( 'scp_order_options' );
		} else {
			$scp_order_options = [];
		}

		if ( isset( $scp_order_options['objects'] ) && is_array( $scp_order_options['objects'] ) ) {
			$objects = $scp_order_options['objects'];
		} else {
			$objects = [];
		}

		return $objects;

	}

	/**
	 * Undocumented function
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_order_options_tags() {

		if ( $scp_order_options = get_option( 'scp_order_options' ) ) {
			$scp_order_options = get_option( 'scp_order_options' );
		} else {
			$scp_order_options = [];
		}

		if ( isset( $scp_order_options['tags'] ) && is_array( $scp_order_options['tags'] ) ) {
			$tags = $scp_order_options['tags'];
		} else {
			$tags = [];
		}

		return $tags;

	}

}
