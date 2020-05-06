<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Color picker shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_colorpicker_param' ) ) :

	function ns_colorpicker_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id   = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];

		$output = '<input id="' . esc_attr( $id ) . '" type="text" data-default-color="' . esc_attr( $value ) . '"';
		$output .= ' name="' . esc_attr( $name ) . '" class="ns-color-picker" value="' . esc_attr( $value ) . '"/>';

		return $output;
	}

	ns_add_shortcode_param( 'colorpicker', 'ns_colorpicker_param' );

endif;
