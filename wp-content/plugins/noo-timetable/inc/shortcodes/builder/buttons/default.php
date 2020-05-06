<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'ns_mce_buttons' ) ) :

	/**
	 * Default WordPress editor Shortcode Builder
	 */
	add_filter( 'mce_buttons', 'ns_mce_buttons' );
	function ns_mce_buttons( $buttons ) {
		$index = array_search( 'wp_more', $buttons );
		if ( $index !== false ) {
			array_splice( $buttons, $index + 1, 0, 'nootheme' );
		} else {
			$buttons[] = 'nootheme';
		}

		return $buttons;
	}

endif;

if ( ! function_exists( 'ns_mce_external_plugins' ) ) :

	add_filter( 'mce_external_plugins', 'ns_mce_external_plugins' );
	function ns_mce_external_plugins( $mce_external_plugins ) {
		global $ns_uri;

		$mce_external_plugins['nootheme'] = $ns_uri . '/buttons/tinymce.js';

		return $mce_external_plugins;
	}

endif;

if ( ! function_exists( 'ns_quicktags_button' ) ) :

	add_action( 'admin_print_footer_scripts', 'ns_quicktags_button' );
	function ns_quicktags_button() {
		if ( wp_script_is( 'quicktags' ) ) {
			echo '<script id="ns_quicktags">' . file_get_contents( dirname( __FILE__ ) . '/quicktags.js' ) . '</script>';
		}
	}

endif;
