<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Checkboxes of Post Types shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_posttypes_param' ) ) :

	function ns_posttypes_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$values = explode( ',', $value );

		$output     = '';
		$args       = array(
			'public' => true,
		);
		$post_types = get_post_types( $args, false );
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' == $post_type->name ) {
				continue;
			}

			$output .= '<label class="ns-checkbox">';
			$output .= '<input type="checkbox" value="' . esc_attr( $post_type->name ) . '"';
			if ( in_array( $post_type->name, $values ) ) {
				$output .= ' checked="checked"';
			}
			$output .= ' /> ' . $post_type->label . '</label>';
		}
		$output .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'posttypes', 'ns_posttypes_param', 'checkbox' );

endif;
