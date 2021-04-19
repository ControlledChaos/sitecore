<?php
/**
 * User Toolbar class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Users
 * @since      1.0.0
 *
 * @todo Add toolbar menus to admin settings page(s).
 */

namespace SiteCore\Classes\Users;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class User_Toolbar {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Register nav menus for the admin bar.
		add_action( 'init', [ $this, 'register_menus' ] );

		// Add the menus to the backend toolbar.
		add_action( 'admin_bar_menu', [ $this, 'backend_main' ], 35 );
		add_action( 'admin_bar_menu', [ $this, 'backend_site' ], 35 );
		add_action( 'admin_bar_menu', [ $this, 'backend_user' ], 35 );

		// Add the menus to the frontend toolbar.
		add_action( 'admin_bar_menu', [ $this, 'frontend_main' ], 35 );
		add_action( 'admin_bar_menu', [ $this, 'frontend_site' ], 35 );
		add_action( 'admin_bar_menu', [ $this, 'frontend_user' ], 35 );
	}

	/**
	 * Register menus
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function register_menus() {

		register_nav_menus(
			[
			'backend_toolbar_site'  => esc_html__( 'Admin Toolbar: Site Name', 'sitecore' ),
			'backend_toolbar_main'  => esc_html__( 'Admin Toolbar: Main', 'sitecore' ),
			'backend_toolbar_user'  => esc_html__( 'Admin Toolbar: User', 'sitecore' ),
			'frontend_toolbar_site' => esc_html__( 'Frontend Toolbar: Site Name', 'sitecore' ),
			'frontend_toolbar_main' => esc_html__( 'Frontend Toolbar: Main', 'sitecore' ),
			'frontend_toolbar_user' => esc_html__( 'Frontend Toolbar: User', 'sitecore' )
			]
		);
	}

	/**
	 * Backend main
	 *
	 * Menu in the main part of the backend toolbar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function backend_main( $wp_admin_bar ) {

		if ( is_admin() && is_user_logged_in() && ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'backend_toolbar_main' ] ) ) {

			$menu = wp_get_nav_menu_object( $locations[ 'backend_toolbar_main' ] );

			if ( false != $menu ) {

				$menu_items = wp_get_nav_menu_items( $menu->term_id );

				foreach ( (array) $menu_items as $key => $menu_item ) {

					if ( $menu_item->classes ) {
						$classes = implode( ' ', $menu_item->classes );
					} else {
						$classes = '';
					}

					$meta = [
						'class'   => $classes,
						'onclick' => '',
						'target'  => $menu_item->target,
						'title'   => $menu_item->attr_title
					];

					if ( $menu_item->menu_item_parent ) {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => $menu_item->menu_item_parent,
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					} else {
						$wp_admin_bar->add_menu(
							[
								'id'    => $menu_item->ID,
								'title' => $menu_item->title,
								'href'  => $menu_item->url,
								'meta'  => $meta
							]
						);
					}
				}
			}
		}
	}

	/**
	 * Backend site
	 *
	 * Menu under the site name in the backend toolbar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function backend_site( $wp_admin_bar ) {

		if ( is_admin() && is_user_logged_in() && ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'backend_toolbar_site' ] ) ) {

			$menu = wp_get_nav_menu_object( $locations[ 'backend_toolbar_site' ] );

			if ( false != $menu ) {

				$menu_items = wp_get_nav_menu_items( $menu->term_id );

				foreach ( (array) $menu_items as $key => $menu_item ) {

					if ( $menu_item->classes ) {
						$classes = implode( ' ', $menu_item->classes );
					} else {
						$classes = '';
					}

					$meta = [
						'class'   => $classes,
						'onclick' => '',
						'target'  => $menu_item->target,
						'title'   => $menu_item->attr_title
					];

					if ( $menu_item->menu_item_parent ) {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => $menu_item->menu_item_parent,
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					} else {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => 'site-name',
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					}
				}
			}
		}
	}

	/**
	 * Backend user
	 *
	 * Menu under the user account name in the backend toolbar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function backend_user( $wp_admin_bar ) {

		if ( is_admin() && is_user_logged_in() && ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'admin_toolbar_account' ] ) ) {

			$menu = wp_get_nav_menu_object( $locations[ 'admin_toolbar_account' ] );

			if ( false != $menu ) {

				$menu_items = wp_get_nav_menu_items( $menu->term_id );

				foreach ( (array) $menu_items as $key => $menu_item ) {

					if ( $menu_item->classes ) {
						$classes = implode( ' ', $menu_item->classes );
					} else {
						$classes = '';
					}

					$meta = [
						'class'   => $classes,
						'onclick' => '',
						'target'  => $menu_item->target,
						'title'   => $menu_item->attr_title
					];

					if ( $menu_item->menu_item_parent ) {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => $menu_item->menu_item_parent,
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					} else {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => 'my-account',
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					}
				}
			}
		}
	}

	/**
	 * Frontend main
	 *
	 * Menu in the main part of the frontend toolbar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_main( $wp_admin_bar ) {

		if ( ! is_admin() && is_user_logged_in() && ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'frontend_toolbar_main' ] ) ) {

			$menu = wp_get_nav_menu_object( $locations[ 'frontend_toolbar_main' ] );

			if ( false != $menu ) {

				$menu_items = wp_get_nav_menu_items( $menu->term_id );

				foreach ( (array) $menu_items as $key => $menu_item ) {

					if ( $menu_item->classes ) {
						$classes = implode( ' ', $menu_item->classes );
					} else {
						$classes = '';
					}

					$meta = [
						'class'   => $classes,
						'onclick' => '',
						'target'  => $menu_item->target,
						'title'   => $menu_item->attr_title
					];

					if ( $menu_item->menu_item_parent ) {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => $menu_item->menu_item_parent,
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					} else {
						$wp_admin_bar->add_menu(
							[
								'id'    => $menu_item->ID,
								'title' => $menu_item->title,
								'href'  => $menu_item->url,
								'meta'  => $meta
							]
						);
					}
				}
			}
		}
	}

	/**
	 * Frontend site
	 *
	 * Menu under the site name in the frontend toolbar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_site( $wp_admin_bar ) {

		if ( ! is_admin() && is_user_logged_in() && ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'frontend_toolbar_site' ] ) ) {

			$menu = wp_get_nav_menu_object( $locations[ 'frontend_toolbar_site' ] );

			if ( false != $menu ) {

				$menu_items = wp_get_nav_menu_items( $menu->term_id );

				foreach ( (array) $menu_items as $key => $menu_item ) {

					if ( $menu_item->classes ) {
						$classes = implode( ' ', $menu_item->classes );
					} else {
						$classes = '';
					}

					$meta = [
						'class'   => $classes,
						'onclick' => '',
						'target'  => $menu_item->target,
						'title'   => $menu_item->attr_title
					];

					if ( $menu_item->menu_item_parent ) {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => $menu_item->menu_item_parent,
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					} else {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => 'site-name',
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					}
				}
			}
		}
	}

	/**
	 * Frontend user
	 *
	 * Menu under the user account name in the frontend toolbar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_user( $wp_admin_bar ) {

		if ( ! is_admin() && is_user_logged_in() && ( $locations = get_nav_menu_locations() ) && isset( $locations[ 'frontend_toolbar_account' ] ) ) {

			$menu = wp_get_nav_menu_object( $locations[ 'frontend_toolbar_account' ] );

			if ( false != $menu ) {

				$menu_items = wp_get_nav_menu_items( $menu->term_id );

				foreach ( (array) $menu_items as $key => $menu_item ) {

					if ( $menu_item->classes ) {
						$classes = implode( ' ', $menu_item->classes );
					} else {
						$classes = '';
					}

					$meta = [
						'class'   => $classes,
						'onclick' => '',
						'target'  => $menu_item->target,
						'title'   => $menu_item->attr_title
					];

					if ( $menu_item->menu_item_parent ) {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => $menu_item->menu_item_parent,
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					} else {
						$wp_admin_bar->add_menu(
							[
								'id'     => $menu_item->ID,
								'parent' => 'my-account',
								'title'  => $menu_item->title,
								'href'   => $menu_item->url,
								'meta'   => $meta
							]
						);
					}
				}
			}
		}
	}
}
