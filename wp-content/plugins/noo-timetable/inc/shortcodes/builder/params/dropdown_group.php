<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Select ( dropdown ) with option group shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_dropdown_group_param' ) ) :

	function ns_dropdown_group_param( $param, $param_value ) {
		$name    = isset( $settings['param_name'] ) ? $settings['param_name'] : $settings['param_name'];
		$css_option = ns_get_dropdown_option( $param, $param_value );
		$param_line = '';
		$param_line .= '<select name="' . esc_attr( $name ) . '" class="wpb_vc_param_value wpb-input wpb-select ' . esc_attr( $name ) . ' ' . $param['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';
		foreach ( $param['optgroup'] as $text_opt => $opt ) {
			if ( is_array( $opt ) ) {
				$param_line .= '<optgroup label="' . $text_opt . '">';
				foreach ( $opt as $text_val => $val ) {
					if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
						$text_val = $val;
					}
					$selected = '';
					if ( $param_value !== '' && (string) $val === (string) $param_value ) {
						$selected = ' selected="selected"';
					}
					$param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' . htmlspecialchars( $text_val ) . '</option>';
				}
				$param_line .= '</optgroup>';
			} elseif ( is_string( $opt ) ) {
				if ( is_numeric( $text_opt ) && ( is_string( $opt ) || is_numeric( $opt ) ) ) {
					$text_opt = $opt;
				}
				$selected = '';
				if ( $param_value !== '' && (string) $opt === (string) $param_value ) {
					$selected = ' selected="selected"';
				}
				$param_line .= '<option class="' . $opt . '" value="' . $opt . '"' . $selected . '>' . htmlspecialchars( $text_opt ) . '</option>';
			}
		}
		$param_line .= '</select>';

		return $param_line;
	}

	ns_add_shortcode_param( 'dropdown_group', 'ns_dropdown_group_param', 'dropdown' );

endif;
