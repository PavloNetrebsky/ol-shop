<?php
/**
 * Timetable Shortcodes builder. Work with Visual Composer
 *
 * @author      NooTheme
 * @package     NooTimetable/Shortcodes
 * @since       2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'noo_timetable_shortcode' ) ) :

	function noo_timetable_shortcode() {

		// Shortcode builder library
		require_once Noo__Timetable__Main::plugin_path() . '/inc/shortcodes/builder/init.php';

		// Mapping shortcodes
		require_once Noo__Timetable__Main::plugin_path() . '/inc/shortcodes/map/new_params.php';
		require_once Noo__Timetable__Main::plugin_path() . '/inc/shortcodes/map/map.php';

		// Add_shortcode action files
		noo_timetable_require_file( 'shortcodes/ntt-class.php' );
		noo_timetable_require_file( 'shortcodes/ntt-class-coming.php' );
		noo_timetable_require_file( 'shortcodes/ntt-trainer.php' );
		noo_timetable_require_file( 'shortcodes/ntt-event.php' );
		noo_timetable_require_file( 'shortcodes/ntt-event-coming.php' );
		noo_timetable_require_file( 'shortcodes/ntt-schedule.php' );
	}

	add_action( 'init', 'noo_timetable_shortcode', 30 );
endif;

