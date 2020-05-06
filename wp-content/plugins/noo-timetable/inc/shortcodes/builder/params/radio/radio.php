<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Radio shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_radio_param' ) ) :

	function ns_radio_param( $settings, $value ) {
		$name    = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$options = isset( $settings['value'] ) && ! empty( $settings['value'] ) ? $settings['value'] : array();
		$value   = is_array( $value ) ? current( $value ) : $value;

		$output = '';
		foreach ( $options as $label => $option ) {
			$output .= ' <label class="ns-radio">';
			$output .= '<input type="radio" name="' . esc_attr( $name ) . '" value="' . $option . '" class="wpb_vc_param_value ' . esc_attr( $name ) . ' ' . $settings['type'] . '" style="width:auto"';
			$output .= checked( $value, $option, false ) . '> ';
			$output .= $label . '</label>';
		}

		return $output;
	}

	ns_add_shortcode_param( 'radio', 'ns_radio_param' );

endif;

/**
 * Radio image shortcode param
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_radio_image_param' ) ) :

	function ns_radio_image_param( $settings, $value ) {

		$name  = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$class = 'wpb_vc_param_value wpb-input wpb-select ' . $name . ' ' . $settings['type'] . '_field';
		$html  = array( '<div class="noo_vc_custom_param noo_radio_image ' . $class . '" >' );

		if ( ! empty( $settings['value'] ) && is_array( $settings['value'] ) ) {

			foreach ( $settings['value'] as $key => $val ) {

				$html[] = '<label>';
				$html[] = '    <input class="wpb_vc_param_value ' . $name . ' ' . $settings['type'] . '" type="radio" name="' . $name . '" value="' . $val . '" ' . ( checked( $value,
						$val, false ) ) . '/>';
				$html[] = '    <img src="' . $key . '" />';
				$html[] = '</label>';
			}
		}

		$html[] = '  </div>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'radio_image', 'ns_radio_image_param', 'radio' );

endif;

/**
 * Enqueue script for Radio param.
 */
if ( ! function_exists( 'ns_radio_param_enqueue' ) ) :

	function ns_radio_param_enqueue() {
		global $ns_uri;
		wp_enqueue_script( 'ns-radio-param', $ns_uri . '/params/radio/ns-radio.js', array( 'jquery' ), false, true );
	}

	add_action( 'vc_backend_editor_enqueue_js_css', 'ns_radio_param_enqueue' );
	add_action( 'vc_frontend_editor_enqueue_js_css', 'ns_radio_param_enqueue' );

endif;
