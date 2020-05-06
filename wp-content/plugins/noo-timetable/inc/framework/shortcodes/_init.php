<?php
/**
 * NOO Shortcodes packages
 *
 * Initialize Admin funciton for NOO Shortcodes
 * This file initialize a button on the WP editor that enable NOO Shortcodes input.
 * 
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/Framework/Shortcodes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Add Admin Shortcode Button

if( !function_exists( 'noo_shortcodes_button_init' ) ) {

	function noo_shortcodes_button_init() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', 'noo_shortcodes_button' );
			add_filter( 'mce_buttons', 'noo_shortcodes_button_register' );
		}  
	}

	add_action( 'init', 'noo_shortcodes_button_init' );

}

if( !function_exists( 'noo_shortcodes_button' ) ) {

	function noo_shortcodes_button( $plugin_array ) {
		if ( floatval( get_bloginfo( 'version' ) ) >= 3.9 ) {
			$tinymce_js = Noo__Timetable__Main::plugin_url() .'/inc/framework/assets/js/noo_shortcodes.tinymce.js';
		} else {
			$tinymce_js = Noo__Timetable__Main::plugin_url() .'/inc/framework/assets/js/noo_shortcodes.tinymce3.js';
		}
		$plugin_array['noo_shortcodes'] = $tinymce_js;
		return $plugin_array;
	}
}

if( !function_exists( 'noo_shortcodes_button_register' ) ) {

	function noo_shortcodes_button_register ( $buttons ) {
		
		array_push( $buttons, 'noo_shortcodes_mce_button' );
		
		return $buttons;
	}
}

if( !function_exists( 'noo_shorcodes_data' ) ) {
	/** 
	 * Localize Data
	 */
	function noo_shorcodes_data() {
		$data = array(
				'url' => Noo__Timetable__Main::plugin_url() . '/inc/framework/shortcodes',
				'contact_form_7' => ( class_exists( 'WPCF7_ContactForm' ) ? 'true' : 'false' ),
				'rev_slider' => ( class_exists( 'RevSlider' ) ? 'true' : 'false' ),
			);

		return $data;
	}
}

if( !function_exists( 'noo_shorcodes_language_string' ) ) {
/** 
 * Localize String
 */
	function noo_shorcodes_language_string() {
		$string = array(
				'schedule'          => __( 'Schedule', 'noo-timetable'),
				'class_schedule'    => __( 'Class Schedule', 'noo-timetable'),
				
				'data_list'         => __( 'Data List', 'noo-timetable'),
				'trainer_list'      => __( 'Trainer List', 'noo-timetable'),
				'class_list'        => __( 'Class List', 'noo-timetable'),
				'class_coming_list' => __( 'Upcoming Class List', 'noo-timetable'),
				'event_list'        => __( 'Event List', 'noo-timetable'),
				'event_coming_list' => __( 'Upcoming Event List', 'noo-timetable')
			);

		return $string;
	}
}

// Enqueue style for shortcodes admin
if ( ! function_exists( 'noo_enqueue_shortcodes_admin_assets' ) ) :
	function noo_enqueue_shortcodes_admin_assets( $hook ) {
		
// 		if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
// 			return;
// 		}

		// Main style
		wp_register_style( 'noo-icon-bootstrap-modal-css', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/css/noo-icon-bootstrap-modal.css', null, null, 'all' );
		wp_register_style( 'noo-shortcodes-admin-css', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/css/noo-shortcodes-admin.css', array( 'wp-color-picker', 'font-awesome', 'noo-icon-bootstrap-modal-css', 'noo-jquery-ui-slider' ));
		wp_enqueue_style( 'noo-shortcodes-admin-css' );

		// Main script
		wp_register_script( 'noo-bootstrap-modal-js', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/js/bootstrap-modal.js', array('jquery'), '2.3.2', true );
		wp_register_script( 'noo-bootstrap-tab-js',Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/js/bootstrap-tab.js',array('jquery'), '2.3.2', true);
		wp_register_script( 'noo-font-awesome-js', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/js/font-awesome.js', array( 'noo-bootstrap-modal-js', 'noo-bootstrap-tab-js'), null, true );
		wp_register_script( 'noo-shortcodes-admin-js', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/js/noo-shortcodes-admin.js', array( 'jquery-ui-slider', 'wp-color-picker', 'noo-font-awesome-js' ), null, true );
		wp_localize_script( 'noo-shortcodes-admin-js', 'noo_shortcodes_data', noo_shorcodes_data() );
		wp_localize_script( 'noo-shortcodes-admin-js', 'noo_shortcodes_str', noo_shorcodes_language_string() );
		wp_enqueue_script( 'noo-shortcodes-admin-js' );
	}
	add_action( 'admin_enqueue_scripts', 'noo_enqueue_shortcodes_admin_assets' );
endif;


