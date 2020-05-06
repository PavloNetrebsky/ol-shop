<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * URL Textfield shortcode param.
 *
 * @param $settings
 * @param $value
 *
 * @return string - html string.
 */
if ( ! function_exists( 'ns_href_param' ) ) :

	function ns_href_param( $settings, $value ) {
		if ( ! is_string( $value ) || strlen( $value ) === 0 ) {
			$value = 'http://';
		}

		return ns_textfield_param( $settings, $value );
	}

	ns_add_shortcode_param( 'href', 'ns_href_param', 'textfield' );

endif;
