<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Package Name: NooTheme Shortcodes Builder
 * Version: 1.0.0
 * Description: Shortcodes builder for WordPress editor. Have some integration with Visual Composer.
 * Author: NooTheme
 * Author URI: https://noothemes.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: noo-shortcode-builder
 */

// Global variables for plugin usage (global declaration is needed here for WP CLI compatibility)
global $ns_dir, $ns_uri;
$ns_dir = apply_filters( 'ns_dir_path', plugin_dir_path( __FILE__ ) ); // Expect to run inside a plugin
$ns_uri = apply_filters( 'ns_dir_uri', plugins_url( '', __FILE__ ) ); // Expect to run inside a plugin

require $ns_dir . 'functions/helpers.php';
require $ns_dir . 'functions/class-nselements.php';
require $ns_dir . 'functions/class-nsparams.php';
require $ns_dir . 'buttons/default.php';

// Ajax requests
if ( is_admin() AND isset( $_POST['action'] ) AND substr( $_POST['action'], 0, 3 ) == 'ns_' ) {
	require $ns_dir . 'functions/ajax.php';
}

if ( ! function_exists( 'ns_admin_enqueue_scripts' ) ) :

	// Load admin scripts and styles
	add_action( 'admin_enqueue_scripts', 'ns_admin_enqueue_scripts', 12 );
	function ns_admin_enqueue_scripts() {
		global $ns_uri, $post_type, $wp_scripts;

		if ( ! wp_style_is( 'font-awesome', 'registered' ) ) {
			wp_register_style( 'font-awesome', $ns_uri . '/assets/vendor/font-awesome/css/font-awesome.min.css', null );
		}
		wp_register_style( 'ns-fontIconPicker',
			$ns_uri . '/assets/vendor/fontIconPicker/css/jquery.fonticonpicker.min.css', null );
		wp_register_style( 'ns-fontIconPicker-grey',
			$ns_uri . '/assets/vendor/fontIconPicker/themes/grey-theme/jquery.fonticonpicker.grey.min.css',
			array( 'ns-fontIconPicker' ) );
		wp_register_script( 'ns-fontIconPicker', $ns_uri . '/assets/vendor/fontIconPicker/jquery.fonticonpicker.min.js',
			array(
				'jquery',
			), null, true );

		wp_register_style( 'ns-editor', $ns_uri . '/assets/css/editor.css', array(
			'wp-color-picker',
			'editor-buttons',
			'ns-fontIconPicker-grey',
			'font-awesome',
		), null );
		wp_register_script( 'ns-editor', $ns_uri . '/assets/js/editor.js', array(
			'jquery-ui-sortable',
			'wp-color-picker',
			'wplink',
			'ns-fontIconPicker',
		), null, true );

		$is_content_editor = ( isset( $post_type ) AND post_type_supports( $post_type, 'editor' ) );

		// Extra JavaScript data
		$extra_js_data = 'if (window.$ns === undefined) window.$ns = {}; $ns.ajaxUrl = ' . wp_json_encode( admin_url( 'admin-ajax.php' ) ) . ";";
		if ( $is_content_editor ) {
			$extra_js_data .= '$ns.elements = ' . wp_json_encode( ns_get_elements() ) . ";\n";
		}
		$wp_scripts->add_data( 'ns-editor', 'data', $extra_js_data );

		if ( $is_content_editor ) {
			ns_enqueue_forms_assets();
		}
	}

endif;

if ( ! function_exists( 'ns_enqueue_forms_assets' ) ) :

	function ns_enqueue_forms_assets() {
		wp_enqueue_style( 'ns-editor' );
		wp_enqueue_script( 'ns-editor' );

		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		ns_maybe_load_wysiwyg();

		do_action( 'ns_enqueue_forms_assets' );
	}

endif;
