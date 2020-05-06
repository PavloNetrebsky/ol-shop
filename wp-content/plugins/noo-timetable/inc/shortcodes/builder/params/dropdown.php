<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Dropdown(select with options) shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_dropdown_param' ) ) :

	function ns_dropdown_param( $settings, $value ) {
		$name    = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id      = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];
		$options = isset( $settings['value'] ) && ! empty( $settings['value'] ) ? $settings['value'] : array();

		$output = '<select name="' . esc_attr( $name ) . '" class="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">';
		foreach ( $options as $title => $option ) {
			$output .= '<option value="' . esc_attr( $option ) . '"' . selected( $value, $option,
					false ) . '>' . $title . '</option>';
		}
		$output .= '</select>';

		return $output;
	}

	ns_add_shortcode_param( 'dropdown', 'ns_dropdown_param' );

endif;
