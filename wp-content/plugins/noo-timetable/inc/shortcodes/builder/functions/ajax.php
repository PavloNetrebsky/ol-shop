<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'ajax_ns_get_shortcode_list_html' ) ) :

	/**
	 * Load elements list HTML to choose from
	 */
	add_action( 'wp_ajax_ns_get_shortcode_list_html', 'ajax_ns_get_shortcode_list_html' );
	function ajax_ns_get_shortcode_list_html() {
		ns_load_template( 'shortcode-list', array() );

		// We don't use JSON to reduce data size
		die;
	}

endif;

if ( ! function_exists( 'ajax_ns_get_builder_html' ) ) :

	/**
	 * Load shortcodes builder forms
	 */
	add_action( 'wp_ajax_ns_get_builder_html', 'ajax_ns_get_builder_html' );
	function ajax_ns_get_builder_html() {
		$template_vars = array(
			'titles' => array(),
			'body'   => '',
		);

		// Loading all the forms HTML
		foreach ( ns_get_elements() as $name => $elm ) {
			$template_vars['titles'][ $name ] = isset( $elm['name'] ) ? $elm['name'] : $name;
			$template_vars['body']            .= ns_get_template( 'form', array(
				'name'             => $name,
				'params'           => $elm['params'],
				'field_id_pattern' => 'ns_builder_form_' . $name . '_%s',
			) );
		}

		ns_load_template( 'builder', $template_vars );

		// We don't use JSON to reduce data size
		die;
	}

endif;
