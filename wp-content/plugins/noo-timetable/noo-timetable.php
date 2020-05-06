<?php
/*
Plugin Name: NOO Timetable
Plugin URI: https://www.nootheme.com
Description: NOO Timetable is a super flexible schedule plugin for WordPress, with fully responsive interface and fascinating UI. It is absolutely suitable for fitness, yoga classes, medical departments, any kind of event calendars and so on. NOO Timetable will help you easily create a timetable with custom data just in a few minutes.
Version: 2.0.6.1
Author: NooTheme
Author URI: https://www.nootheme.com
Text Domain: noo-timetable
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
* Main Noo Timetable class.
*/
if ( ! class_exists( 'Noo__Timetable__Main' ) ) {
	/**
	 * Noo Timetable Class
	 *
	 * This is where all the important processing
	 */
	class Noo__Timetable__Main {

		// A reference to an instance of this class.
        private static $instance;

		// Returns an instance of this class.
        public static function get_instance() {

            if( null == self::$instance ) {
                self::$instance = new Noo__Timetable__Main();
            }
            return self::$instance;

        }

        // Returns an init of this class.
        public static function init() {
        	$class = __CLASS__;
	        new $class;
	    }

        // Initializes the plugin by setting filters and administration functions.
        public function __construct() {

        	// Loader globals
        	self::setup();

        	// Loader language plugin
			load_plugin_textdomain( 'noo-timetable', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

			// After Theme setup
        	add_action( 'after_setup_theme', array( &$this, 'theme_setup' ) );

			// Enqueue script Back End
			if ( is_admin() ) :
				add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_script_back_end' ));
			endif;

			// Enqueue script Front End
			if ( !is_admin() ) :
				add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_script_front_end' ));
			endif;

        	/**
        	 * Load Libraries
        	 */
        	// [Functions]
        	require_once $this->includes_dir . '/functions/template-func.php';
        	require_once $this->includes_dir . '/functions/noo-css.php';
        	require_once $this->includes_dir . '/functions/noo-design.php';

        	// [Framework]
        	require_once $this->includes_dir . '/framework/meta-boxes/class-helper.php';
        	require_once $this->includes_dir . '/framework/meta-boxes/generate-meta-box.php';
        	// require_once $this->includes_dir . '/framework/shortcodes/_init.php';

        	// [Setting]
        	require_once $this->includes_dir . '/framework/settings/noo-setting.php';
        	require_once $this->includes_dir . '/functions/setting-general.php';
        	require_once $this->includes_dir . '/functions/setting-schedule.php';
        	require_once $this->includes_dir . '/functions/setting-update.php';

        	// [Post Type]
        	require_once $this->includes_dir . '/post-types/noo_class.php';
        	require_once $this->includes_dir . '/post-types/noo_trainer.php';
        	require_once $this->includes_dir . '/post-types/noo_event.php';

        	// [Add Meta Boxes]
        	require_once $this->includes_dir . '/post-types/add-meta-boxes/class-meta-boxes.php';
        	require_once $this->includes_dir . '/post-types/add-meta-boxes/trainer-meta-boxes.php';
        	require_once $this->includes_dir . '/post-types/add-meta-boxes/event-meta-boxes.php';

        	// [Widget]
        	require_once $this->includes_dir . '/widgets/_init.php';
        	require_once $this->includes_dir . '/widgets/class-widgets.php';
        	require_once $this->includes_dir . '/widgets/event-widgets.php';

        	// [Shortcodes]
        	require_once $this->includes_dir . '/shortcodes/ntt_init.php';

        	// [Elementor Widgets]
	        //require_once $this->includes_dir . '/elementor/index.php';

        	// [Import Classes]
        	require_once $this->includes_dir . '/legacy-import/noo-legacy-import.php';

        	// [Import Demo]
        	require_once $this->includes_dir . '/importer/noo-settup-install.php';


        	// Filter
        	add_filter( 'body_class', 'noo_timetable_body_class' );

        	// Add Menu
			if ( is_admin() ) :
				add_action( 'admin_menu', array( &$this, 'admin_menu' ), 9 );
				add_action( 'admin_menu', array( &$this, 'settings_menu' ), 50 );
				add_action( 'admin_menu', array( &$this, 'shortcodes_menu' ), 50 );
				add_action( 'admin_menu', array( &$this, 'automatic_update_menu' ), 50 );
				add_action( 'admin_menu', array( &$this, 'import_demo_menu' ), 50 );
			endif;

			if ( function_exists('noo_timetable_customizer_css_generator') ) {
				add_action( 'wp_head', 'noo_timetable_customizer_css_generator', 100, 0 );
			}

			if ( function_exists('noo_timetable_customizer_css_generator_color') ) {
				add_action( 'wp_head', 'noo_timetable_customizer_css_generator_color', 100, 0 );
			}


		}

		/**
		 * After setup theme
		 */
		public function theme_setup() {

			// Check version of plugin
        	require_once $this->includes_dir . '/functions/noo-check-version.php';

	        if ( is_admin() ) {
	            $license_manager = new Noo_Timetable_Check_Version(
	                'noo-timetable',
	                'Noo Timetable',
	                'http://update.nootheme.com/api/license-manager/v1',
	                'plugin',
		            __FILE__,
	                false
	            );
	        }
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public static function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public static function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Get the template path.
		 * @return string
		 */
		public static function template_path() {
			return apply_filters( 'noo_timetable_template_path', 'noo-timetable/' );
		}

		/**
		 * Setup
		 */
		private function setup() {

			// VERSION
			$this->version = '1.0.0';

			// Setup path
			$this->file         = __FILE__;
			$this->basename     = apply_filters( 'noo_timetable_plugin_basenname', plugin_basename( $this->file ) );
			$this->plugin_dir   = apply_filters( 'noo_timetable_plugin_dir_path', plugin_dir_path( $this->file ) );
			$this->plugin_url   = apply_filters( 'noo_timetable_plugin_dir_url', plugin_dir_url( $this->file ) );
			//[ Assets ]
			$this->assets_dir   = apply_filters( 'noo_timetable_includes_dir', trailingslashit( $this->plugin_dir . 'assets'  ) );
			$this->assets_url   = apply_filters( 'noo_timetable_includes_url', trailingslashit( $this->plugin_url . 'assets'  ) );



			//[ Includes ]
			$this->includes_dir = apply_filters( 'noo_timetable_includes_dir', trailingslashit( $this->plugin_dir . 'inc'  ) );
			$this->includes_url = apply_filters( 'noo_timetable_includes_url', trailingslashit( $this->plugin_url . 'inc'  ) );

			//[ Framework Assets ]
			$this->framework_assets_dir   = apply_filters( 'noo_timetable_includes_dir', trailingslashit( $this->includes_dir . 'framework/assets'  ) );
			$this->framework_assets_url   = apply_filters( 'noo_timetable_includes_url', trailingslashit( $this->includes_url . 'framework/assets'  ) );

			//[ Languages ]
			$this->lang_dir     = apply_filters( 'noo_timetable_lang_dir', trailingslashit( $this->plugin_dir . 'languages' ) );

			//[ Plugins ]
			$this->plugins_dir  = apply_filters( 'noo_timetable_plugins_dir', trailingslashit( $this->plugin_dir . 'views' ) );
			$this->plugins_url  = apply_filters( 'noo_timetable_plugins_url', trailingslashit( $this->plugin_url . 'views' ) );

			//[ Templates ]
			$this->themes_dir   = apply_filters( 'noo_timetable_themes_dir', trailingslashit( get_template_directory() . 'noo-timetable' ) );
			$this->themes_url   = apply_filters( 'noo_timetable_themes_url', trailingslashit( get_template_directory_uri() . 'noo-timetable' ) );
		}



		/**
		 * Enqueue script in Back-End
		 */
		public function enqueue_script_back_end( $hook ) {
			/**
			 * Required css
			 */

			wp_register_style( 'noo-timetable-admin', $this->framework_assets_url . 'css/noo-admin.css' );
			wp_enqueue_style( 'noo-timetable-admin' );

			wp_register_style( 'chosen-css', $this->framework_assets_url . 'css/noo-chosen.css', null, null, 'all' );

			wp_register_style( 'noo-timetable-meta-boxes', $this->framework_assets_url . 'css/noo-meta-boxes.css' );
			wp_enqueue_style( 'noo-timetable-meta-boxes' );

			wp_enqueue_style( 'noo-jquery-ui-slider', $this->framework_assets_url . 'css/noo-jquery-ui.slider.css', null, null, 'all' );
			wp_enqueue_style( 'noo-jquery-ui', $this->framework_assets_url . 'css/noo-jquery-ui.css', array(), NULL, NULL);

			wp_register_style( 'datetimepicker', $this->framework_assets_url . 'vendor/datetimepicker/jquery.datetimepicker.css' );
			wp_register_style( 'timepicker', $this->framework_assets_url . 'vendor/timepicker/jquery.ui.timepicker.css' );

			 // Font Awesome
			wp_register_style( 'font-awesome', $this->assets_url . 'vendor/fontawesome/css/font-awesome.min.css', array(), '4.6.3' );
			wp_enqueue_style( 'font-awesome' );


			/**
			 * Required js
			 */

			wp_register_script( 'noo-admin-js', $this->framework_assets_url . 'js/noo-admin.js', array( 'jquery' ), null, true );
			wp_enqueue_script( 'noo-admin-js' );

			wp_register_script( 'chosen-js', $this->framework_assets_url . 'js/chosen.jquery.min.js', array( 'jquery'), null, true );
			wp_register_script( 'chosen-order-js', $this->framework_assets_url . 'js/chosen.order.jquery.min.js', array( 'jquery'), null, true );

			wp_register_script( 'noo-timetable-meta-boxes-js', $this->framework_assets_url . 'js/noo-meta-boxes.js', array( 'jquery', 'media-upload', 'thickbox' ), null, true );
			wp_enqueue_script( 'noo-timetable-meta-boxes-js' );

			wp_register_script( 'datetimepicker', $this->framework_assets_url . 'vendor/datetimepicker/jquery.datetimepicker.js', array( 'jquery' ), null, true );
			wp_register_script( 'timepicker', $this->framework_assets_url . 'vendor/timepicker/jquery.ui.timepicker.js', array( 'jquery' ), null, true );

			$nooDateTimePicker = array(
				'lang'  => noo_timetable_getJqueryUII18nLocale(),
            );
            wp_localize_script( 'datetimepicker', 'nooDateTimePicker', $nooDateTimePicker );

			wp_enqueue_script( 'datetimepicker' );
            wp_enqueue_style( 'datetimepicker' );

			wp_enqueue_script( 'chosen-js' );
			wp_enqueue_style( 'chosen-css' );
			wp_enqueue_script( 'chosen-order-js' );

			/**
			 * Js for Event
			 */

			global $post;

            if ( $hook == 'post-new.php' || $hook == 'post.php' ) {

                if ( 'noo_event' === $post->post_type ) {

                    $google_map_latitude = NOO_Settings()->get_option( 'noo_google_map_latitude', '51.508742' );
		            $google_map_longitude = NOO_Settings()->get_option( 'noo_google_map_longitude', '-0.120850' );

                    $lat = get_post_meta( $post->ID, '_noo_event_gmap_latitude', true );
                    if( !empty( $lat ) )
                        $google_map_latitude = $lat;

                    $long = get_post_meta( $post->ID, '_noo_event_gmap_longitude', true );
                    if( !empty( $long ) )
                        $google_map_longitude = $long;

                    $nooFrameEventMap = array(
                        'latitude'          => $google_map_latitude,
                        'longitude'         => $google_map_longitude,
                        'localtion_disable' => false
                    );

                    $google_api = NOO_Settings()->get_option( 'noo_google_map_api_key', '' );

                    wp_register_script(
                        'google-map',
                        'http' . ( is_ssl() ? 's' : '' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places'. ( !empty( $google_api ) ? '&key=' .$google_api : '' ),
                        array('jquery'),
                        '1.0',
                        false
                    );
                    wp_register_script(
                        'noo-frame-map-event',
                        $this->framework_assets_url . 'js/noo-frame-map-event.js',
                        array( 'jquery', 'google-map' ),
                        null,
                        true
                    );

                    wp_localize_script( 'noo-frame-map-event', 'nooFrameEventMap', $nooFrameEventMap );
                    wp_enqueue_script( 'noo-frame-map-event' );

                }

            }

		}

		/**
		 * Enqueue script in Front-End
		 */
		public function enqueue_script_front_end() {
			/**
			 * Required css
			 */

			// Stylesheet core
			wp_enqueue_style( 'noo-timetable', $this->assets_url . 'css/noo-timetable.css', array(), NULL, NULL);
			wp_enqueue_style( 'noo-timetable-schedule', $this->assets_url . 'css/noo-timetable-schedule.css', array(), NULL, NULL);

			 // Font Awesome
			wp_enqueue_style( 'font-awesome', $this->assets_url . 'vendor/fontawesome/css/font-awesome.min.css', array(), '4.6.1' );

			wp_register_style( 'carousel', $this->assets_url . 'vendor/owl-carousel/owl.carousel.css', array(), '1.0.0' );
			wp_register_style( 'carousel-theme', $this->assets_url . 'vendor/owl-carousel/owl.theme.default.min.css', array(), '1.0.0' );
			//wp_register_style( 'calendar', $this->assets_url . 'vendor/fullcalendar-2.8.0/fullcalendar.css');
			wp_register_style( 'calendar', $this->assets_url . 'vendor/fullcalendar-3.9.0/fullcalendar.css');
			wp_register_style( 'nifty-modal', $this->assets_url . 'vendor/nifty-modal/css/component.css', array(), NULL, NULL);
			wp_enqueue_style( 'dashicons' );

			/**
			 * Required js
			 */


   			wp_register_script( 'noo-timetable-script', $this->assets_url . 'js/noo-timetable.js', array( 'jquery' ), null, true );
			$nooL10n = array(
				'ajax_url'        => admin_url( 'admin-ajax.php', 'relative' ),
				'home_url'        => home_url( '/' )
			);
			wp_localize_script('noo-timetable-script', 'nooL10n', $nooL10n);
			wp_enqueue_script( 'noo-timetable-script' );

			wp_enqueue_script( 'noo-readmore', $this->assets_url . 'vendor/readmore.js', array('jquery'), null, true );

   			wp_register_script( 'wow', $this->assets_url . 'vendor/wow/wow.min.js', array( 'jquery'), null, true );

   			//v2.8.0
   			/*wp_register_script( 'calendar-moment', $this->assets_url . 'vendor/fullcalendar-2.8.0/lib/moment.min.js',null, null, true );
			wp_register_script( 'calendar-lang', $this->assets_url . 'vendor/fullcalendar-2.8.0/locale-all.js',null, null, true );
			wp_register_script( 'calendar', $this->assets_url . 'vendor/fullcalendar-2.8.0/fullcalendar.custom.js', array( 'calendar-moment', 'jquery' ), null, true );
			wp_register_script( 'scheduler', $this->assets_url . 'vendor/fullcalendar-2.8.0/scheduler.js', array( 'calendar-moment', 'jquery' ), null, true );*/

			//v3.9.0
			wp_register_script( 'calendar-moment', $this->assets_url . 'vendor/fullcalendar-3.9.0/lib/moment.min.js',null, null, true );
			wp_register_script( 'calendar-lang', $this->assets_url . 'vendor/fullcalendar-3.9.0/locale-all.js',null, null, true );
			wp_register_script( 'calendar', $this->assets_url . 'vendor/fullcalendar-3.9.0/fullcalendar.custom.js', array( 'calendar-moment', 'jquery' ), null, true );
			wp_register_script( 'scheduler', $this->assets_url . 'vendor/fullcalendar-3.9.0/scheduler.js', array( 'calendar-moment', 'jquery' ), null, true );

			wp_register_script( 'nifty-modal', $this->assets_url . 'vendor/nifty-modal/js/classie.js', array( 'jquery'), null, true );

			wp_register_script( 'ics', $this->assets_url . 'vendor/ics/ics.js', array( 'jquery'), null, true );
			wp_register_script( 'ics-deps', $this->assets_url . 'vendor/ics/ics.deps.min.js', array( 'jquery'), null, true );
			wp_register_script( 'carousel', $this->assets_url . 'vendor/owl-carousel/owl.carousel.min.js', array( 'jquery'), null, true );

			wp_register_script( 'imagesloaded', $this->assets_url . 'vendor/isotope-imageloaded/imagesloaded.pkgd.min.js', array( 'jquery'), null, true );
			wp_register_script( 'isotope', $this->assets_url . 'vendor/isotope-imageloaded/isotope.pkgd.min.js', array( 'jquery'), null, true );

			wp_register_script( 'noo-class', $this->assets_url . 'js/noo_class.js', array( 'jquery'), null, true );
			wp_register_script( 'noo-event', $this->assets_url . 'js/noo_event.js', array( 'jquery'), null, true );

			$google_api = NOO_Settings()->get_option( 'noo_google_map_api_key', '' );
    		wp_register_script( 'maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp' . ( !empty( $google_api ) ? '&key=' .$google_api : '' ), array( 'jquery' ), null, false );
    		wp_register_script( 'noo-event-maps', $this->assets_url . 'js/noo-event-maps.js', array( 'maps' ), null, false );
    		wp_localize_script( 'noo-event-maps', 'nooEventMaps', array(
				'assets_url'	=> $this->assets_url,
			));

		}

		/**
		 * Add menu items.
		 */
		public function admin_menu() {
			$noo_icon = $this->framework_assets_url . 'css/images/noo20x20.png';
			add_menu_page( __( 'NooTimetable', 'noo-timetable' ), __( 'NooTimetable', 'noo-timetable' ), 'manage_timetable', 'noo_timetable', null, $noo_icon, 46 );
		}

		/**
		 * Add menu item.
		 */
		public function settings_menu() {
			add_submenu_page( 'noo_timetable', __( 'NooTimetable Settings', 'noo-timetable' ),  __( 'Settings', 'noo-timetable' ) , 'edit_theme_options', 'noo-timetable-settings', array( $this, 'settings_page' ) );
		}

		/**
		 * Add menu item.
		 */
		public function shortcodes_menu() {
			add_submenu_page( 'noo_timetable', __( 'NooTimetable Schedule', 'noo-timetable' ),  __( 'Schedule', 'noo-timetable' ) , 'edit_theme_options', 'noo-timetable-schedule', array( $this, 'settings_page' ) );
		}

		/**
		 * Add menu item.
		 */
		public function automatic_update_menu() {
			add_submenu_page( 'noo_timetable', __( 'NooTimetable Schedule', 'noo-timetable' ),  __( 'Automatic Update', 'noo-timetable' ) , 'edit_theme_options', 'noo-timetable-update', array( $this, 'settings_page' ) );
		}

		/**
		 * Add menu item.
		 */
		public function import_demo_menu() {
			add_submenu_page( 'noo_timetable', __( 'NooTimetable Schedule', 'noo-timetable' ),  __( 'Import Demo', 'noo-timetable' ) , 'edit_theme_options', 'noo-timetable-import-demo', array( $this, 'import_demo' ) );
		}

		public function import_demo() {
			Noo__Timetable_Settup_Install::output();
		}

		/**
		 * Init the settings page.
		 */
		public function settings_page() {
			Noo__Timetable__Setting::output();
		}

	}

	add_action( 'plugins_loaded', array( 'Noo__Timetable__Main', 'init' ) );

}

if ( ! function_exists('noo_timetable_active_plugin') ) {
	function noo_timetable_active_plugin() {
		flush_rewrite_rules();
	}
	add_action( 'activated_plugin', 'noo_timetable_active_plugin' );
}
