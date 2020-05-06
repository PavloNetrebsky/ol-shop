<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post Tags multiple select shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_post_tags_param' ) ) :

	function ns_post_tags_param( $settings, $value ) {
		$name            = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id              = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];
		$selected_values = explode( ',', $value );
		$categories      = get_tags( array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$class           = 'wpb-input wpb-select ' . esc_attr( $name ) . ' ' . $settings['type'] . '_field';

		$html   = array( '<div class="ns-post_categories-wrapper">' );
		$html[] = '  <input type="hidden" name="' . esc_attr( $name ) . '" value="' . $value . '" class="wpb_vc_param_value" />';
		$html[] = '  <select id="' . esc_attr( $id ) . '-select" multiple="true" class="' . $class . '" >';
		$html[] = '    <option value="all" ' . ( in_array( 'all',
				$selected_values ) ? 'selected="true"' : '' ) . '>' . esc_html__( 'All',
				'ns-shortcode-builder' ) . '</option>';
		foreach ( $categories as $category ) {
			$html[] = '    <option value="' . $category->term_id . '" ' . ( in_array( $category->term_id,
					$selected_values ) ? 'selected="true"' : '' ) . '>';
			$html[] = '      ' . $category->name;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';
		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '	   jQuery( "select#' . esc_attr( $id ) . '-select" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . esc_attr( $name ) . '\']" ).val( selected_values );';
		$html[] = '	   } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'post_tags', 'ns_post_tags_param' );

endif;

