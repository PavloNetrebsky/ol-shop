<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Default input shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_input_param' ) ) :

	function ns_input_param( $settings, $value ) {
		$name  = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id    = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];
		$type  = isset( $settings['type'] ) ? $settings['type'] : 'text';
		$class = 'wpb_vc_param_value wpb-input ' . $name . ' ' . $settings['type'] . '_field';

		$attributes = '';
		foreach ( array( 'min', 'max', 'step', 'maxlength', 'placeholder' ) as $attr ) {
			$attributes .= ( isset( $settings[ $attr ] ) && ! empty( $settings[ $attr ] ) ) ? ' ' . $attr . '="' . $settings[ $attr ] . '"' : '';
		}

		$output = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '" class="' . esc_attr( $class ) . '" ' . $attr . '/>';

		return $output;
	}

	ns_add_shortcode_param( 'text', 'ns_input_param' );
	ns_add_shortcode_param( 'url', 'ns_input_param' );
	ns_add_shortcode_param( 'number', 'ns_input_param' );
	ns_add_shortcode_param( 'email', 'ns_input_param' );
	ns_add_shortcode_param( 'password', 'ns_input_param' );
	ns_add_shortcode_param( 'date', 'ns_input_param' );
	ns_add_shortcode_param( 'datetime', 'ns_input_param' );
	ns_add_shortcode_param( 'time', 'ns_input_param' );
	ns_add_shortcode_param( 'week', 'ns_input_param' );
	ns_add_shortcode_param( 'month', 'ns_input_param' );
	ns_add_shortcode_param( 'range', 'ns_input_param' );

endif;
