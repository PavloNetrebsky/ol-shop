<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Link field shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_vc_link_param' ) ) :

	function ns_vc_link_param( $settings, $value ) {
		// Loading tinymce-related components to output link dialog (part of _WP_Editors class)
		ns_maybe_load_wysiwyg();
		wp_enqueue_script( 'wplink' );
		wp_enqueue_style( 'editor-buttons' );

		$name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
		$id   = isset( $settings['id'] ) ? $settings['id'] : $settings['param_name'];
		$link = ns_parse_vc_link_value( $value );

		// Shortening the link
		if ( strlen( $link['url'] ) > 50 ) {
			$link['url'] = substr_replace( $link['url'], '...', 20, strlen( $link['url'] ) - 70 );
		}

		$output = '<div class="ns-linkdialog">';
		$output .= '<a class="ns-linkdialog-btn button button-default button-large" href="javascript:void(0)">' . __( 'Insert link',
				'noo-shortcode-builder' ) . '</a>';
		$output .= '<strong>' . __( 'Title',
				'noo-shortcode-builder' ) . ':</strong><span class="ns-linkdialog-title">' . $link['title'] . '</span><strong>' . __( 'URL',
				'noo-shortcode-builder' ) . ':</strong><span class="ns-linkdialog-url">' . $link['url'] . '</span>';
		$output .= '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '">' . esc_textarea( $value ) . '</textarea>';
		$output .= '</div>';

		return $output;
	}

	ns_add_shortcode_param( 'vc_link', 'ns_vc_link_param' );

endif;

if ( ! function_exists( 'ns_parse_vc_link_value' ) ) :
	/**
	 * Parsing vc_link field type properly
	 *
	 * @param string $value
	 * @param bool   $as_string Return prepared anchor attributes string instead of array
	 *
	 * @return mixed
	 */
	function ns_parse_vc_link_value( $value, $as_string = false ) {
		$result       = array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' );
		$params_pairs = explode( '|', $value );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = explode( ':', $pair, 2 );
				if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
					$result[ $param[0] ] = trim( rawurldecode( $param[1] ) );
				}
			}
		}

		if ( $as_string ) {
			$string = '';
			foreach ( $result as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$string .= ' ' . ( ( $attr == 'url' ) ? 'href' : $attr ) . '="' . esc_attr( $value ) . '"';
				}
			}

			return $string;
		}

		return $result;
	}
endif;
