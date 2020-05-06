<?php

class Noo_Timetable_Elementor_Extension
{
	private static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

	}

	public function i18n() {

		load_plugin_textdomain( 'noo-timetable' );

	}

	public function init()
	{

		// Check if Elementor installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
	}

	public function widget_scripts() {
		global $noo_imgcomp_uri;
		//wp_register_script( 'noo-timetable-elementor-script', plugins_url( '/assets/js/noo-elementor-widget.js', __FILE__ ), [ 'jquery' ], false, true );
	}

	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'noo-before-after' ),
			'<strong>' . esc_html__( 'Noo Before After', 'noo-before-after' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'noo-before-after' ) . '</strong>'
		);

		//printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function init_widgets() {
		global $noo_imgcomp_dir;
		// Include Widget files
		require_once( $noo_imgcomp_dir . 'inc/elementor/noo_before_after_widget.php' );

		// Register widget
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Noo_Before_After_Elementor_Widget() );

	}

	public function init_controls() {


	}
}

Noo_Before_After_Elementor_Extension::instance();