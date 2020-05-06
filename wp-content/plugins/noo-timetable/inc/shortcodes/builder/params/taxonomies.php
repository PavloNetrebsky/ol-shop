<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Checkboxes of Taxonomies shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_taxonomies_param' ) ) :

	function ns_taxonomies_param( $settings, $value ) {
		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$values = explode( ',', $value );

		$output     = '';
		$post_types = get_post_types( array( 'public' => false, 'name' => 'attachment' ), 'names', 'NOT' );
		foreach ( $post_types as $type ) {
			$taxonomies = get_object_taxonomies( $type, '' );
			foreach ( $taxonomies as $tax ) {
				$output .= '<label class="ns-checkbox">';
				$output .= '<input type="checkbox" value="' . esc_attr( $tax->name ) . '"';
				if ( in_array( $tax->name, $values ) ) {
					$output .= ' checked="checked"';
				}
				$output .= ' /> ' . $tax->label . '</label>';
			}
		}
		$output .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" />';

		return $output;
	}

	ns_add_shortcode_param( 'taxonomies', 'ns_taxonomies_param', 'checkbox' );

endif;
