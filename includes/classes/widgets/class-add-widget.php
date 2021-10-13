<?php
/**
 * Add a widget type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Widgets
 * @since      1.0.0
 */

namespace SiteCore\Classes\Widgets;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Add_Widget extends \WP_Widget {

	/**
	 * Widget base ID
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $type_base = '';

	/**
	 * Whether or not the widget has been registered yet.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean
	 */
	protected $registered = false;

	/**
	 * Name for this widget type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $type_name = '';

	/**
	 * Description for this widget type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string Default is empty.
	 */
	protected $type_desc = '';

	/**
	 * Selective refresh
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Default is true.
	 */
	protected $type_refresh = true;

	/**
	 * Show instance in REST
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Default is true.
	 */
	protected $show_in_rest = true;

	/**
	 * Use title fallback
	 *
	 * Whether to use a fallback title if
	 * this widget's title field is empty.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Default is false.
	 */
	protected $use_title_fallback = false;

	/**
	 * Title fallback
	 *
	 * The fallback title, if used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Default is empty.
	 */
	protected $title_fallback = '';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Run the parent constructor.
		parent :: __construct(
			$this->type_base,
			$name = $this->type_name(),
			$this->options(),
			$this->filter_controls()
		);

		// Register this widget.
		add_action( 'widgets_init', function() {
			register_widget( $this );
		} );

		// If there is at least one instance of this widget on the screen.
		if ( is_active_widget( false, false, $this->id_base, true ) ) {

			// Add a frontend body class.
			add_filter( 'body_class', [ $this, 'body_class' ] );

			// Add actions and filters for this widget.
			$this->widget_instance();
		}
	}

	/**
	 * Widget active body class
	 *
	 * Add a class to the frontend body element if there is
	 * at least one instance of this widget on the screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns a modified array of body classes.
	 */
	public function body_class( $classes ) {

		$class = sprintf(
			'has-%s-instance',
			str_replace( '_', '-', $this->type_base )
		);

		return array_merge(
			$classes,
			[ $class ]
		);
	}

	/**
	 * Widget instance
	 *
	 * Runs if there is at least one instance of this widget on the screen.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return self
	 */
	protected function widget_instance() {

		// Sample action adds an HTML comment to the head element.
		add_action( 'wp_head', function() {
			printf(
				"\n" . __( '<!-- At least on instance of the %s widget is active. -->', 'sitecore' ) . "\n",
				$this->type_name()
			);
		}, 99 );
	}

	/**
	 * Filter prefix
	 *
	 * Uses the widget base to create a prefix for applied filters.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the prefix with trailing underscore.
	 */
	protected function prefix() {

		$base   = $this->type_base;
		$prefix = str_replace( '-', '_', $base );

		// Return the prefix with trailing underscore.
		return $prefix . '_';
	}

	/**
	 * Widget wrapper class
	 *
	 * Uses the `widget_` prefix in keeping with core widgets convention.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the class name.
	 */
	protected function type_class() {

		return apply_filters(
			$this->prefix() . 'widget_type_class',
			'widget_' . $this->type_base
		);
	}

	/**
	 * Widget name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the widget name.
	 */
	protected function type_name() {

		if ( is_string( $this->type_name ) && ! empty( $this->type_name ) ) {
			$name = __( ucwords( $this->type_name ), 'sitecore' );

		} else {
			$base = str_replace( [ '-', '_' ], ' ', $this->type_base );
			$name = __( ucwords( $base ), 'sitecore' );
		}

		return apply_filters( $this->prefix() . 'widget_type_name', $name );
	}

	/**
	 * Widget description
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the widget description.
	 */
	protected function type_desc() {

		if ( is_string( $this->type_desc ) && ! empty( $this->type_desc ) ) {
			$type_desc = __( $this->type_desc, 'sitecore' );
		} else {
			$type_desc = '';
		}
		return apply_filters( $this->prefix() . 'widget_type_desc', $type_desc );
	}

	/**
	 * Title fallback
	 *
	 * The fallback title, if used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the widget fallback title.
	 */
	protected function title_fallback() {

		if ( is_string( $this->title_fallback ) && ! empty( $this->title_fallback ) ) {
			$title_fallback = __( ucwords( $this->title_fallback ), 'sitecore' );
		} else {
			$title_fallback = $this->type_name();
		}
		return apply_filters( $this->prefix() . 'widget_title_fallback', $title_fallback );
	}

	/**
	 * Widget frontend title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array $instance Current widget settings.
	 * @return string Returns the title or an empty string.
	 */
	protected function front_title( $instance ) {

		if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];

		} elseif ( $this->use_title_fallback ) {
			$title = $this->title_fallback();

		} else {
			$title = '';
		}
		return apply_filters( $this->prefix() . 'widget_front_title', $title );
	}

	/**
	 * Widget options
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns an array of options.
	 */
	protected function options() {

		// Options array.
		$options = [
			'classname'                   => $this->type_class(),
			'description'                 => $this->type_desc(),
			'customize_selective_refresh' => $this->type_refresh,
			'show_instance_in_rest'       => $this->show_in_rest
		];
		return apply_filters( $this->prefix() . 'widget_options', $options );
	}

	/**
	 * Widget control options
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns an array of control options.
	 */
	protected function controls() {

		// Control options array.
		$options = [
			'width'  => 250,
			'height' => 200
		];
		return $options;
	}

	/**
	 * Filter widget control options
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns a filtered array of control options.
	 */
	protected function filter_controls() {
		return apply_filters( $this->prefix() . 'widget_control_options', $this->controls() );
	}

	/**
	 * Add hooks for enqueueing assets when registering all widget instances of this widget class.
	 *
	 * @param int $number Optional. The unique order number of this widget instance
	 *                    compared to other instances of the same class. Default -1.
	 */
	public function _register_one( $number = -1 ) {

		parent::_register_one( $number );
		if ( $this->registered ) {
			return;
		}
		$this->registered = true;

		// wp_add_inline_script( 'text-widgets', sprintf( 'wp.textWidgets.idBases.push( %s );', wp_json_encode( $this->id_base ) ) );

		if ( $this->is_preview() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'preview_enqueue_scripts' ] );
		}

		// Enqueue admin scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		// Print admin widgets scripts.
		add_action( 'admin_print_scripts-widgets.php', [ $this, 'admin_print_widgets_scripts' ] );

		// Print admin scripts.
		add_action( 'admin_print_scripts', [ $this, 'admin_print_scripts' ] );

		// Enqueue admin styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );

		// Print admin widgets styles.
		add_action( 'admin_print_styles-widgets.php', [ $this, 'admin_print_widgets_styles' ] );

		// Print admin styles.
		add_action( 'admin_print_styles', [ $this, 'admin_print_styles' ] );

		// Print admin footer widgets scripts.
		add_action( 'admin_print_footer_scripts-widgets.php', [ $this, 'admin_print_footer_widgets_scripts' ] );

		// Print admin footer scripts.
		add_action( 'admin_print_footer_scripts', [ $this, 'admin_print_footer_scripts' ] );

		// Widgets footer.
		add_action( 'admin_footer-widgets.php', [ $this, 'admin_footer_widgets' ] );
	}

	/**
	 * Enqueue preview scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function preview_enqueue_scripts() {}

	/**
	 * Enqueue admin scripts
	 *
	 * Fires on all admin screens.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {}

	/**
	 * Print admin widgets scripts
	 *
	 * Fires on the widgets screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_widgets_scripts() {}

	/**
	 * Print admin scripts
	 *
	 * Fires on all admin screens.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_scripts() {}

	/**
	 * Enqueue admin scripts
	 *
	 * Fires on all admin screens.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_styles() {}

	/**
	 * Print admin widgets scripts
	 *
	 * Fires on the widgets screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_widgets_styles() {}

	/**
	 * Print admin scripts
	 *
	 * Fires on all admin screens.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_styles() {}

	/**
	 * Print admin footer widgets scripts
	 *
	 * Fires on the widgets screen.
	 * Also fires in the customizer via
	 * `WP_Customize_Widgets :: print_scripts()`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_footer_widgets_scripts() {}

	/**
	 * Print admin footer scripts
	 *
	 * Fires on all screens.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_footer_scripts() {}

	/**
	 * Widgets footer
	 *
	 * Last hook to fire on the widgets screen.
	 * Use for whatever needs to run late.
	 *
	 * Also fires in the customizer via
	 * `WP_Customize_Widgets :: print_footer_scripts()`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_footer_widgets() {}

	/**
	 * Widget views directory
	 *
	 * The directory where files for this widget's
	 * backend form and frontend display are found.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string
	 */
	protected function widget_views() {

		if ( is_admin() ) {
			$dir = 'views/backend/widgets';
		} else {
			$dir = 'views/frontend/widgets';
		}
		return apply_filters( $this->prefix() . 'views_directory', trailingslashit( $dir ) );
	}

	/**
	 * Widget backend form file name
	 *
	 * The file needs to be this widget's base name,
	 * with dash separators, followed by `-form`.
	 *
	 * These are kept in the widget views directory
	 * as defined in `widget_views()`.
	 *
	 * @example `sample-widget-form.php`
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string
	 */
	protected function form_file() {
		$file = str_replace( '_', '-', $this->type_base );
		return apply_filters( $this->prefix() . 'form_file', $file . '-form.php' );
	}

	/**
	 * Widget frontend display file name
	 *
	 * The file needs to be this widget's base name,
	 * with dash separators, followed by `-display`.
	 *
	 * These are kept in the widget views directory
	 * as defined in `widget_views()`.
	 *
	 * @example `sample-widget-display.php`
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string
	 */
	protected function widget_file() {
		$file = str_replace( '_', '-', $this->type_base );
		return apply_filters( $this->prefix() . 'widget_file', $file . '-display.php' );
	}

	/**
	 * Widget UI form
	 *
	 * @since  1.0.0
	 * @access public
	 * @param array $instance Current widget settings.
	 * @return void
	 */
	public function form( $instance ) {

		// Look for the file.
		$file = SCP_PATH . $this->widget_views() . $this->form_file();

		// Include the file if it exists.
		if ( file_exists( $file ) ) {
			include $file;

		// Otherwise echo nothing.
		} else {
			echo '';
		}
	}

	/**
	 * Widget frontend display
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args Display arguments including 'before_title', 'after_title',
	 *                     'before_widget', and 'after_widget'.
	 * @param  array $instance Settings for the current widget instance.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		// Look for the file.
		$file = SCP_PATH . $this->widget_views() . $this->widget_file();

		// Include the file if it exists.
		if ( file_exists( $file ) ) {
			include $file;

		// Otherwise echo nothing.
		} else {
			echo '';
		}
	}

	/**
	 * Update the widget form
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $new_instance New settings for this instance as input by the user via
	 *                             WP_Widget::form().
	 * @param  array $old_instance Old settings for this instance.
	 * @return array Updated settings.
	 */
	public function update( $new_instance, $old_instance ) {}
}
