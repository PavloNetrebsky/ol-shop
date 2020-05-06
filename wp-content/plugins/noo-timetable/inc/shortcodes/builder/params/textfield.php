<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Textfield shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_textfield_param' ) ) :

	function ns_textfield_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id   = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];

		$output = '<input type="text" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'textfield', 'ns_textfield_param' );
	ns_add_shortcode_param( 'el_id', 'ns_textfield_param', 'textfield' );
	ns_add_shortcode_param( 'tab_id', 'ns_textfield_param', 'textfield' );

endif;
