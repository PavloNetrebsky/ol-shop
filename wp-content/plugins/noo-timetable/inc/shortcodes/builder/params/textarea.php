<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Textarea shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_textarea_param' ) ) :

	function ns_textarea_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id   = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];

		$output = '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">';
		$output .= esc_textarea( $value );
		$output .= '</textarea>';

		return $output;
	}

	ns_add_shortcode_param( 'textarea', 'ns_textarea_param' );

endif;

/**
 * Textarea Raw HTML ( no WYSIWYG editor ) shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_textarea_raw_html_param' ) ) :

	function ns_textarea_raw_html_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$decoded_value = htmlentities( rawurldecode( base64_decode( $value ) ), ENT_COMPAT, 'UTF-8' );

		$output = '<textarea>';
		$output .= esc_textarea( $decoded_value );
		$output .= '</textarea>';
		$output .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'textarea_raw_html', 'ns_textarea_raw_html_param' );

endif;

/**
 * Textarea Safe shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_textarea_safe_param' ) ) :

	function ns_textarea_safe_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$value_from_safe = ns_value_from_safe( $value, true );

		$output = '<textarea>';
		$output .= esc_textarea( $value_from_safe );
		$output .= '</textarea>';
		$output .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'textarea_safe', 'ns_textarea_safe_param' );

endif;

/**
 * Exploded Textarea shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_exploded_textarea_param' ) ) :

	function ns_exploded_textarea_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$exploded_value = str_replace( ',', "\n", $value );

		$output = '<textarea>';
		$output .= esc_textarea( $exploded_value );
		$output .= '</textarea>';
		$output .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'exploded_textarea', 'ns_exploded_textarea_param' );

endif;

/**
 * Exploded Textarea Safe shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_exploded_textarea_safe_param' ) ) :

	function ns_exploded_textarea_safe_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$value_from_safe = ns_value_from_safe( $value, true );
		$exploded_value  = str_replace( ',', "\n", $value_from_safe );

		$output = '<textarea>';
		$output .= esc_textarea( $exploded_value );
		$output .= '</textarea>';
		$output .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'exploded_textarea_safe', 'ns_exploded_textarea_safe_param' );

endif;
