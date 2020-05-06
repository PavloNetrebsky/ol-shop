<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Params preset shortcode param.
 *
 * Allows to set preset values for the list of other params.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_params_preset_param' ) ) :

	function ns_params_preset_param( $settings, $value ) {
		$name         = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id           = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];
		$options      = isset( $settings['options'] ) && ! empty( $settings['options'] ) ? $settings['options'] : array();
		$value_string = (string) $value;

		$output = '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">';
		foreach ( $options as $option ) {
			if ( isset( $option['value'] ) ) {
				$option_value_string = (string) $option['value'];
				$selected            = '';
				if ( '' !== $value && $option_value_string === $value_string ) {
					$selected = ' selected';
				}
				$output .= '<option value="' . esc_attr( $option_value_string ) . '"' . $selected . ' data-params="' . esc_attr( json_encode( $option['params'] ) ) . '">' . esc_html( isset( $option['label'] ) ? $option['label'] : $option_value_string ) . '</option>';
			}
		}
		$output .= '</select>';

		return $output;
	}

	ns_add_shortcode_param( 'params_preset', 'ns_params_preset_param' );

endif;

