<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Checkbox shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_checkbox_param' ) ) :

	function ns_checkbox_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$options = isset( $settings['value'] ) && ! empty( $settings['value'] ) ? $settings['value'] : $options = array( '' => true );
		$values  = explode( ',', $value );

		$output = '';
		foreach ( $options as $label => $option ) {
			$output .= '<label class="ns-checkbox">';
			$output .= '<input type="checkbox" value="' . esc_attr( $option ) . '"';
			if ( in_array( $option, $values ) ) {
				$output .= ' checked="checked"';
			}
			$output .= ' /> ' . $label . '</label>';
		}
		$output .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'checkbox', 'ns_checkbox_param' );

endif;
