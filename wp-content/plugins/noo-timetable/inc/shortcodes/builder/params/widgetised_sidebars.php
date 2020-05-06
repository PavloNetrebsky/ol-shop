<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Dropdown of Widgets shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_widgetised_sidebars_param' ) ) :

	function ns_widgetised_sidebars_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id   = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];

		$sidebars = $GLOBALS['wp_registered_sidebars'];

		$output = '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">';
		foreach ( $sidebars as $sidebar ) {
			$output .= '<option value="' . esc_attr( $sidebar['id'] ) . '"' . selected( $value, $sidebar['id'],
					false ) . '>' . $sidebar['name'] . '</option>';
		}
		$output .= '</select>';

		return $output;
	}

	ns_add_shortcode_param( 'widgetised_sidebars', 'ns_widgetised_sidebars_param', 'dropdown' );

endif;

